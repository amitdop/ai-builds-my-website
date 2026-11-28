<?php

namespace Tests\Feature;

use App\Models\Business;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishWebsiteTest extends TestCase
{
    use RefreshDatabase;

    private const OWNER_TOKEN = 'publish-owner-session';

    public function test_owner_can_publish_a_reviewed_draft_at_a_unique_public_url(): void
    {
        $business = $this->createOwnedBusiness();

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => self::OWNER_TOKEN,
        ])->post(route('businesses.publish', $business), [
            'confirm_review' => 'yes',
        ]);

        $response->assertRedirect(route('businesses.show', $business))
            ->assertSessionHas('publish_status', 'Your website is live.');

        $business->refresh();
        $this->assertSame("sample-cafe-pune-{$business->id}", $business->slug);
        $this->assertSame($business->generated_content['tagline'], $business->published_content['tagline']);
        $this->assertSame($business->name, $business->published_content['business_name']);
        $this->assertSame($business->city, $business->published_content['city']);
        $this->assertSame('published', $business->status);
        $this->assertNotNull($business->published_at);
    }

    public function test_a_different_session_cannot_publish_the_business(): void
    {
        $business = $this->createOwnedBusiness();

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => 'another-browser-session',
        ])->post(route('businesses.publish', $business), [
            'confirm_review' => 'yes',
        ]);

        $response->assertNotFound();
        $this->assertNull($business->refresh()->published_content);
        $this->assertNull($business->slug);
    }

    public function test_owner_must_confirm_review_before_publishing(): void
    {
        $business = $this->createOwnedBusiness();

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => self::OWNER_TOKEN,
        ])->post(route('businesses.publish', $business));

        $response->assertInvalid(['confirm_review']);
        $this->assertNull($business->refresh()->published_content);
        $this->assertNull($business->slug);
    }

    public function test_a_business_without_a_published_snapshot_is_not_public(): void
    {
        $business = $this->createOwnedBusiness(['slug' => 'not-live-yet']);

        $this->get(route('sites.show', 'not-live-yet'))
            ->assertNotFound();
    }

    public function test_public_page_escapes_content_and_excludes_private_or_conflicted_information(): void
    {
        $business = $this->createOwnedBusiness([
            'name' => 'Sample <script>alert(1)</script>',
            'website_url' => 'https://example.com/business',
            'google_business_url' => 'javascript:alert(2)',
            'generated_content' => [
                'tagline' => 'A warm welcome <script>alert(3)</script>',
                'facts' => [
                    [
                        'field' => 'service',
                        'value' => 'Owner-confirmed coffee',
                        'original_value' => 'espresso',
                        'owner_edited' => true,
                        'source' => 'PRIVATE MENU.pdf',
                        'evidence' => 'We serve espresso.',
                    ],
                    [
                        'field' => 'opening_hours',
                        'value' => '9 am to 5 pm',
                        'source' => 'PRIVATE HOURS.pdf',
                        'evidence' => 'Hours: 9 am to 5 pm.',
                    ],
                ],
                'conflicts' => [[
                    'field' => 'opening_hours',
                    'claims' => [
                        ['value' => '9 am to 5 pm', 'source' => 'PRIVATE HOURS.pdf'],
                        ['value' => '10 am to 6 pm', 'source' => 'owner'],
                    ],
                ]],
            ],
        ]);
        $business->update([
            'slug' => 'sample-cafe-public',
            'published_content' => array_merge($business->generated_content, [
                'business_name' => $business->name,
                'city' => $business->city,
                'links' => [
                    'Google Business' => 'javascript:alert(2)',
                    'Website' => 'https://example.com/business',
                ],
            ]),
            'published_at' => now(),
            'status' => 'published',
        ]);
        $business->documents()->create([
            'original_name' => 'secret-price-list.pdf',
            'stored_path' => 'documents/'.$business->id.'/secret.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100,
            'kind' => 'document',
            'extracted_text' => 'Private source data.',
            'extraction_status' => 'done',
        ]);

        $response = $this->get(route('sites.show', $business->slug));

        $response->assertOk()
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertSee('&lt;script&gt;alert(3)&lt;/script&gt;', false)
            ->assertSee('Owner-confirmed coffee')
            ->assertSee('Business-provided')
            ->assertSee('https://example.com/business')
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertDontSee('<script>alert(3)</script>', false)
            ->assertDontSee('PRIVATE MENU.pdf')
            ->assertDontSee('We serve espresso.')
            ->assertDontSee('secret-price-list.pdf')
            ->assertDontSee(self::OWNER_TOKEN)
            ->assertDontSee('9 am to 5 pm')
            ->assertDontSee('10 am to 6 pm')
            ->assertDontSee('javascript:alert');
    }

    public function test_public_site_keeps_its_snapshot_until_the_owner_republishes(): void
    {
        $business = $this->createOwnedBusiness([
            'generated_content' => [
                'tagline' => 'A newer private draft.',
                'facts' => [],
                'conflicts' => [],
            ],
            'slug' => 'sample-cafe-public',
            'published_content' => [
                'business_name' => 'Sample Cafe',
                'city' => 'Pune',
                'links' => ['Website' => 'https://published.example'],
                'tagline' => 'A warm welcome.',
                'facts' => [],
            ],
            'published_at' => now(),
            'status' => 'published',
        ]);

        $this->withSession([
            Business::OWNER_SESSION_KEY => self::OWNER_TOKEN,
        ])->patch(route('businesses.draft.update', $business), [
            'tagline' => 'A newer private draft.',
        ])->assertRedirect(route('businesses.show', $business));

        $this->get(route('sites.show', $business->slug))
            ->assertOk()
            ->assertSee('A warm welcome.')
            ->assertSee('https://published.example')
            ->assertDontSee('A newer private draft.');

        $this->withSession([
            Business::OWNER_SESSION_KEY => self::OWNER_TOKEN,
        ])->post(route('businesses.publish', $business), [
            'confirm_review' => 'yes',
        ])->assertRedirect(route('businesses.show', $business));

        $this->get(route('sites.show', $business->slug))
            ->assertOk()
            ->assertSee('A newer private draft.')
            ->assertDontSee('A warm welcome.');
    }

    public function test_public_site_uses_profile_details_from_the_published_snapshot(): void
    {
        $business = $this->createOwnedBusiness([
            'name' => 'Current private name',
            'city' => 'Current private city',
            'website_url' => 'https://current.example',
            'slug' => 'sample-cafe-snapshot',
            'published_content' => [
                'business_name' => 'Approved business name',
                'city' => 'Approved city',
                'links' => ['Website' => 'https://approved.example'],
                'tagline' => 'Approved tagline.',
                'facts' => [],
            ],
        ]);

        $this->get(route('sites.show', $business->slug))
            ->assertOk()
            ->assertSee('Approved business name')
            ->assertSee('Approved city')
            ->assertSee('https://approved.example')
            ->assertDontSee('Current private name')
            ->assertDontSee('Current private city')
            ->assertDontSee('https://current.example');
    }

    public function test_business_creation_rejects_non_http_links(): void
    {
        $this->post(route('businesses.store'), [
            'name' => 'Sample Cafe',
            'city' => 'Pune',
            'website_url' => 'javascript:alert(1)',
        ])->assertInvalid(['website_url']);

        $this->assertDatabaseMissing('businesses', ['name' => 'Sample Cafe']);
    }

    private function createOwnedBusiness(array $attributes = []): Business
    {
        return Business::create(array_merge([
            'name' => 'Sample Cafe',
            'city' => 'Pune',
            'status' => 'generated',
            'owner_session_token_hash' => hash('sha256', self::OWNER_TOKEN),
            'generated_content' => [
                'title' => 'Sample Cafe',
                'city' => 'Pune',
                'tagline' => 'A warm welcome.',
                'facts' => [],
                'conflicts' => [],
                'missing_information' => [],
                'unread_links' => [],
            ],
        ], $attributes));
    }
}
