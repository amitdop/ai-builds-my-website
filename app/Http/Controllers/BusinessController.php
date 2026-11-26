<?php

namespace App\Http\Controllers;

use App\Exceptions\WebsiteGenerationException;
use App\Models\Business;
use App\Services\GeminiWebsiteContentGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
            'google_business_url' => ['nullable', 'url', 'max:500'],
            'facebook_url' => ['nullable', 'url', 'max:500'],
            'instagram_url' => ['nullable', 'url', 'max:500'],
            'website_url' => ['nullable', 'url', 'max:500'],
        ]);

        $business = Business::create($validated);

        return redirect()->route('businesses.show', $business);
    }

    // Show one saved business.
    public function show(Business $business)
    {
        return view('businesses.show', ['business' => $business]);
    }

    public function generate(Business $business, GeminiWebsiteContentGenerator $generator): RedirectResponse
    {
        $business->update([
            'status' => 'generating',
            'error_message' => null,
        ]);

        try {
            $content = $generator->generate($business);
        } catch (WebsiteGenerationException $exception) {
            $business->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);

            return redirect()->route('businesses.show', $business);
        }

        $business->update([
            'status' => 'generated',
            'generated_content' => $content,
            'error_message' => null,
        ]);

        return redirect()
            ->route('businesses.show', $business)
            ->with('generation_status', 'Your AI content draft is ready to review.');
    }
}
