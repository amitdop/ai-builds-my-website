<?php

namespace App\Http\Controllers;

use App\Exceptions\WebsiteGenerationException;
use App\Models\Business;
use App\Services\GeminiWebsiteContentGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    // Show the empty form.
    public function create()
    {
        return view('businesses.create');
    }

    // Check the submitted form, save it, then go to the business page.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'google_business_url' => ['nullable', 'url:http,https', 'max:500'],
            'facebook_url' => ['nullable', 'url:http,https', 'max:500'],
            'instagram_url' => ['nullable', 'url:http,https', 'max:500'],
            'website_url' => ['nullable', 'url:http,https', 'max:500'],
        ]);

        $ownerToken = $request->session()->get(Business::OWNER_SESSION_KEY);

        if (! is_string($ownerToken) || $ownerToken === '') {
            $ownerToken = Str::random(64);
            $request->session()->put(Business::OWNER_SESSION_KEY, $ownerToken);
        }

        $validated['owner_session_token_hash'] = hash('sha256', $ownerToken);
        $normalizedName = Str::lower(trim($validated['name']));
        $normalizedCity = Str::lower(trim($validated['city']));

        $existingBusiness = Business::query()
            ->where('owner_session_token_hash', $validated['owner_session_token_hash'])
            ->orderByDesc('id')
            ->get()
            ->first(function (Business $business) use ($normalizedName, $normalizedCity): bool {
                return Str::lower(trim($business->name)) === $normalizedName
                    && Str::lower(trim($business->city)) === $normalizedCity;
            });

        if ($existingBusiness) {
            return redirect()
                ->route('businesses.show', $existingBusiness)
                ->with('duplicate_status', 'This profile already exists in this browser, so we reopened it instead of creating a duplicate.');
        }

        $business = Business::create($validated);

        return redirect()->route('businesses.show', $business);
    }

    // Show one saved business.
    public function show(Request $request, Business $business)
    {
        $this->authorizeOwnerSession($business, $request);

        return view('businesses.show', [
            'business' => $business,
            'canManageBusiness' => true,
            'hasUnpublishedChanges' => is_array($business->generated_content)
                && (
                    $business->published_content === null
                    || $business->published_content != $business->generated_content
                ),
        ]);
    }

    public function generate(Request $request, Business $business, GeminiWebsiteContentGenerator $generator): RedirectResponse
    {
        $this->authorizeOwnerSession($business, $request);
        $hasPublishedSite = is_array($business->published_content);

        $business->update([
            'status' => 'generating',
            'error_message' => null,
        ]);

        try {
            $content = $generator->generate($business);
        } catch (WebsiteGenerationException $exception) {
            $business->update([
                'status' => $hasPublishedSite ? 'published' : 'failed',
                'error_message' => $exception->getMessage(),
            ]);

            return redirect()->route('businesses.show', $business);
        }

        $business->update([
            'status' => $hasPublishedSite ? 'published' : 'generated',
            'generated_content' => $content,
            'error_message' => null,
        ]);

        return redirect()
            ->route('businesses.show', $business)
            ->with('generation_status', 'Your AI content draft is ready to review.');
    }

    public function updateDraft(Request $request, Business $business): RedirectResponse
    {
        $this->authorizeOwnerSession($business, $request);

        $content = $business->generated_content;

        if (! is_array($content) || ! is_array($content['facts'] ?? [])) {
            return redirect()
                ->route('businesses.show', $business)
                ->withErrors(['draft' => 'Generate a draft before editing it.']);
        }

        $validated = $request->validate([
            'tagline' => ['required', 'string', 'max:255'],
            'facts' => ['sometimes', 'array', 'max:20'],
            'facts.*' => ['required', 'array:value'],
            'facts.*.value' => ['required', 'string', 'max:400'],
        ]);

        $existingFacts = $content['facts'] ?? [];
        $submittedFacts = $validated['facts'] ?? [];
        $existingIndices = array_keys($existingFacts);
        $submittedIndices = array_keys($submittedFacts);
        sort($existingIndices);
        sort($submittedIndices);

        if ($existingIndices !== $submittedIndices) {
            return back()
                ->withErrors(['facts' => 'Keep the draft details in place while editing.'])
                ->withInput();
        }

        foreach ($existingFacts as $index => $fact) {
            if (! is_array($fact) || ! is_string($fact['value'] ?? null)) {
                return back()
                    ->withErrors(['draft' => 'This draft cannot be edited safely. Generate a new draft and try again.'])
                    ->withInput();
            }

            $originalValue = $fact['original_value'] ?? $fact['value'];
            $editedValue = trim($submittedFacts[$index]['value']);

            if ($editedValue === $originalValue) {
                unset($fact['original_value'], $fact['owner_edited']);
            } else {
                $fact['original_value'] = $originalValue;
                $fact['owner_edited'] = true;
            }

            $fact['value'] = $editedValue;
            $existingFacts[$index] = $fact;
        }

        $originalTagline = $content['tagline_original'] ?? $content['tagline'] ?? '';
        $editedTagline = trim($validated['tagline']);

        if ($editedTagline === $originalTagline) {
            unset($content['tagline_original'], $content['tagline_owner_edited']);
        } else {
            $content['tagline_original'] = $originalTagline;
            $content['tagline_owner_edited'] = true;
        }

        $content['tagline'] = $editedTagline;
        $content['facts'] = $existingFacts;

        $business->update([
            'generated_content' => $content,
        ]);

        return redirect()
            ->route('businesses.show', $business)
            ->with('draft_status', 'Your edits are saved. Review owner-edited facts against their source before using them.');
    }

    public function publish(Request $request, Business $business): RedirectResponse
    {
        $this->authorizeOwnerSession($business, $request);

        $content = $business->generated_content;

        if (! is_array($content) || ! is_string($content['tagline'] ?? null) || ! is_array($content['facts'] ?? null)) {
            return redirect()
                ->route('businesses.show', $business)
                ->withErrors(['publish' => 'Generate a valid draft before publishing a website.']);
        }

        $request->validate([
            'confirm_review' => ['accepted'],
        ]);

        $slug = $business->slug;

        if (! is_string($slug) || $slug === '') {
            $slugBase = Str::slug($business->name.'-'.$business->city);
            $slugBase = $slugBase === '' ? 'business' : mb_substr($slugBase, 0, 180);
            $slug = $slugBase.'-'.$business->getKey();
        }

        $business->update([
            'slug' => $slug,
            'published_content' => array_merge($content, [
                'business_name' => $business->name,
                'city' => $business->city,
                'links' => [
                    'Google Business' => $business->google_business_url,
                    'Facebook' => $business->facebook_url,
                    'Instagram' => $business->instagram_url,
                    'Website' => $business->website_url,
                ],
            ]),
            'published_at' => now(),
            'status' => 'published',
            'error_message' => null,
        ]);

        return redirect()
            ->route('businesses.show', $business)
            ->with('publish_status', 'Your website is live.');
    }

    private function authorizeOwnerSession(Business $business, Request $request): void
    {
        abort_unless(
            $business->isOwnedBySession($request->session()->get(Business::OWNER_SESSION_KEY)),
            404,
        );
    }
}
