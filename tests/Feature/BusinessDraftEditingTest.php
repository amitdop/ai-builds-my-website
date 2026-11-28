<?php

namespace Tests\Feature;

use App\Models\Business;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessDraftEditingTest extends TestCase
{
    use RefreshDatabase;

    private const OWNER_TOKEN = 'business-owner-session';

    public function test_creator_session_can_edit_tagline_and_facts_without_losing_source_evidence(): void
    {
        $business = $this->createOwnedBusiness();

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => self::OWNER_TOKEN,
        ])->patch(route('businesses.draft.update', $business), [
            'tagline' => '<script>alert(1)</script>',
            'facts' => [
                ['value' => 'handcrafted coffee'],
            ],
        ]);

        $response->assertRedirect(route('businesses.show', $business));
        $response->assertSessionHas('draft_status');

        $content = $business->refresh()->generated_content;
        $this->assertSame('generated', $business->status);
        $this->assertSame('<script>alert(1)</script>', $content['tagline']);
        $this->assertSame('A warm welcome.', $content['tagline_original']);
        $this->assertSame(true, $content['tagline_owner_edited']);
        $this->assertSame('handcrafted coffee', $content['facts'][0]['value']);
        $this->assertSame('espresso', $content['facts'][0]['original_value']);
        $this->assertSame(true, $content['facts'][0]['owner_edited']);
        $this->assertSame('We serve espresso.', $content['facts'][0]['evidence']);
    }

    public function test_different_session_cannot_edit_the_business_draft(): void
    {
        $business = $this->createOwnedBusiness();

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => 'different-browser-session',
        ])->patch(route('businesses.draft.update', $business), [
            'tagline' => 'Changed by another visitor.',
            'facts' => [
                ['value' => 'changed'],
            ],
        ]);

        $response->assertNotFound();
        $this->assertSame('A warm welcome.', $business->refresh()->generated_content['tagline']);
    }

    public function test_businesses_created_before_owner_tracking_cannot_be_edited(): void
    {
        $business = $this->createOwnedBusiness();
        $business->update(['owner_session_token_hash' => null]);

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => self::OWNER_TOKEN,
        ])->patch(route('businesses.draft.update', $business), [
            'tagline' => 'Changed draft.',
            'facts' => [
                ['value' => 'changed'],
            ],
        ]);

        $response->assertNotFound();
        $this->assertSame('A warm welcome.', $business->refresh()->generated_content['tagline']);
    }

    public function test_invalid_edits_do_not_change_the_saved_draft(): void
    {
        $business = $this->createOwnedBusiness();

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => self::OWNER_TOKEN,
        ])->patch(route('businesses.draft.update', $business), [
            'tagline' => '',
            'facts' => [
                ['value' => 'invented fact', 'source' => 'attacker.pdf'],
            ],
        ]);

        $response->assertInvalid(['tagline', 'facts.0']);
        $content = $business->refresh()->generated_content;
        $this->assertSame('A warm welcome.', $content['tagline']);
        $this->assertSame('espresso', $content['facts'][0]['value']);
    }

    public function test_business_without_a_generated_draft_cannot_be_edited(): void
    {
        $business = Business::create([
            'name' => 'Sample Cafe',
            'city' => 'Pune',
            'status' => 'draft',
            'owner_session_token_hash' => hash('sha256', self::OWNER_TOKEN),
        ]);

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => self::OWNER_TOKEN,
        ])->patch(route('businesses.draft.update', $business), [
            'tagline' => 'A tagline.',
        ]);

        $response->assertRedirect(route('businesses.show', $business));
        $response->assertSessionHasErrors([
            'draft' => 'Generate a draft before editing it.',
        ]);
        $this->assertSame(null, $business->refresh()->generated_content);
    }

    public function test_returning_a_fact_to_its_ai_value_removes_its_owner_edit_marker(): void
    {
        $business = $this->createOwnedBusiness([
            'facts' => [[
                'field' => 'service',
                'value' => 'handcrafted coffee',
                'original_value' => 'espresso',
                'owner_edited' => true,
                'source' => 'menu.pdf',
                'evidence' => 'We serve espresso.',
            ]],
        ]);

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => self::OWNER_TOKEN,
        ])->patch(route('businesses.draft.update', $business), [
            'tagline' => 'A warm welcome.',
            'facts' => [
                ['value' => 'espresso'],
            ],
        ]);

        $response->assertRedirect(route('businesses.show', $business));
        $fact = $business->refresh()->generated_content['facts'][0];
        $this->assertSame('espresso', $fact['value']);
        $this->assertSame(false, array_key_exists('owner_edited', $fact));
        $this->assertSame(false, array_key_exists('original_value', $fact));
    }

    public function test_owner_edited_html_is_escaped_in_the_business_preview(): void
    {
        $business = $this->createOwnedBusiness([
            'tagline' => '<script>alert(1)</script>',
            'tagline_original' => 'A warm welcome.',
            'tagline_owner_edited' => true,
        ]);

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => self::OWNER_TOKEN,
        ])->get(route('businesses.show', $business));

        $response->assertOk()
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_a_different_session_cannot_view_the_private_business_workspace(): void
    {
        $business = $this->createOwnedBusiness();

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => 'another-browser-session',
        ])->get(route('businesses.show', $business));

        $response->assertNotFound()
            ->assertDontSee('We serve espresso.');
    }

    public function test_owner_session_can_view_the_private_business_workspace(): void
    {
        $business = $this->createOwnedBusiness();

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => self::OWNER_TOKEN,
        ])->get(route('businesses.show', $business));

        $response->assertOk()
            ->assertSee('We serve espresso.');
    }

    public function test_business_creation_stores_only_a_hash_of_the_owner_session_token(): void
    {
        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => self::OWNER_TOKEN,
        ])->post(route('businesses.store'), [
            'name' => 'New Sample Cafe',
            'city' => 'Pune',
        ]);

        $response->assertRedirect();
        $business = Business::query()->where('name', 'New Sample Cafe')->firstOrFail();
        $this->assertSame(hash('sha256', self::OWNER_TOKEN), $business->owner_session_token_hash);
        $this->assertSame(false, array_key_exists('owner_session_token_hash', $business->toArray()));

        $this->get(route('businesses.show', $business))
            ->assertOk()
            ->assertSee('Generate a draft')
            ->assertSee('Upload materials');
    }

    public function test_same_browser_reopens_an_existing_profile_with_the_same_name_and_city(): void
    {
        $existing = Business::create([
            'name' => 'Sample Cafe',
            'city' => 'Pune',
            'status' => 'draft',
            'owner_session_token_hash' => hash('sha256', self::OWNER_TOKEN),
        ]);

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => self::OWNER_TOKEN,
        ])->post(route('businesses.store'), [
            'name' => '  sample cafe  ',
            'city' => ' PUNE ',
        ]);

        $response->assertRedirect(route('businesses.show', $existing));
        $response->assertSessionHas('duplicate_status');
        $this->assertSame(1, Business::query()->count());
    }

    public function test_another_browser_session_creates_a_separate_profile(): void
    {
        Business::create([
            'name' => 'Sample Cafe',
            'city' => 'Pune',
            'status' => 'draft',
            'owner_session_token_hash' => hash('sha256', self::OWNER_TOKEN),
        ]);

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => 'another-browser-session',
        ])->post(route('businesses.store'), [
            'name' => 'Sample Cafe',
            'city' => 'Pune',
        ]);

        $response->assertRedirect();
        $this->assertSame(2, Business::query()->count());
    }

    private function createOwnedBusiness(array $contentOverrides = []): Business
    {
        return Business::create([
            'name' => 'Sample Cafe',
            'city' => 'Pune',
            'status' => 'generated',
            'owner_session_token_hash' => hash('sha256', self::OWNER_TOKEN),
            'generated_content' => array_merge([
                'title' => 'Sample Cafe',
                'city' => 'Pune',
                'tagline' => 'A warm welcome.',
                'facts' => [[
                    'field' => 'service',
                    'value' => 'espresso',
                    'source' => 'menu.pdf',
                    'evidence' => 'We serve espresso.',
                ]],
                'conflicts' => [],
                'missing_information' => [],
                'unread_links' => [],
            ], $contentOverrides),
        ]);
    }
}
