<?php

namespace Tests\Feature;

use App\Models\Business;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_different_session_cannot_upload_to_a_business(): void
    {
        $business = Business::create([
            'name' => 'Sample Cafe',
            'city' => 'Pune',
            'owner_session_token_hash' => hash('sha256', 'owner-session'),
        ]);

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => 'different-session',
        ])->post(route('documents.store', $business));

        $response->assertNotFound();
        $this->assertSame(0, $business->documents()->count());
    }

    public function test_creator_session_can_upload_to_a_business(): void
    {
        Storage::fake('public');

        $ownerToken = 'owner-session';
        $business = Business::create([
            'name' => 'Sample Cafe',
            'city' => 'Pune',
            'owner_session_token_hash' => hash('sha256', $ownerToken),
        ]);

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => $ownerToken,
        ])->post(route('documents.store', $business), [
            'files' => [
                UploadedFile::fake()->createWithContent(
                    'menu.png',
                    base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGNgYAAAAAMAASsJTYQAAAAASUVORK5CYII=', true),
                ),
            ],
        ]);

        $response->assertRedirect(route('businesses.show', $business));
        $response->assertSessionHas('status', 'Files uploaded successfully.');

        $document = $business->documents()->firstOrFail();
        Storage::disk('public')->assertExists($document->stored_path);
    }
}
