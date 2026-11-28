<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Contracts\View\View;

class PublishedSiteController extends Controller
{
    public function show(string $slug): View
    {
        $business = Business::query()
            ->where('slug', $slug)
            ->whereNotNull('published_content')
            ->firstOrFail();

        $content = $business->published_content;
        $conflictedFields = collect($content['conflicts'] ?? [])
            ->pluck('field')
            ->filter(fn (mixed $field): bool => is_string($field))
            ->all();

        $facts = collect($content['facts'] ?? [])
            ->filter(fn (mixed $fact): bool => is_array($fact)
                && is_string($fact['field'] ?? null)
                && is_string($fact['value'] ?? null)
                && ! in_array($fact['field'], $conflictedFields, true))
            ->map(fn (array $fact): array => [
                'field' => $fact['field'],
                'value' => $fact['value'],
                'owner_edited' => ($fact['owner_edited'] ?? false) === true,
            ])
            ->values();

        $links = collect(is_array($content['links'] ?? null) ? $content['links'] : [])
            ->filter(fn (mixed $url): bool => $this->isPublicWebUrl($url))
            ->map(fn (string $url, string $label): array => [
                'label' => $label,
                'url' => $url,
            ])
            ->values();

        return view('sites.show', [
            'business' => [
                'name' => $content['business_name'] ?? '',
                'city' => $content['city'] ?? '',
            ],
            'content' => [
                'tagline' => $content['tagline'],
                'facts' => $facts,
            ],
            'links' => $links,
        ]);
    }

    private function isPublicWebUrl(mixed $url): bool
    {
        if (! is_string($url)) {
            return false;
        }

        $parts = parse_url($url);

        return is_array($parts)
            && isset($parts['host'])
            && in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true);
    }
}
