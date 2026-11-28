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
        Storage::fake('local');
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
        Storage::disk('local')->assertExists($document->stored_path);
        Storage::disk('public')->assertMissing($document->stored_path);
    }

    public function test_creator_upload_extracts_pdf_text_from_private_storage(): void
    {
        Storage::fake('local');
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
                    'catalogue.pdf',
                    $this->pdfWithText('Catalogue: espresso and filter coffee'),
                ),
            ],
        ]);

        $response->assertRedirect(route('businesses.show', $business));

        $document = $business->documents()->firstOrFail();
        Storage::disk('local')->assertExists($document->stored_path);
        Storage::disk('public')->assertMissing($document->stored_path);
        $this->assertSame('done', $document->extraction_status);
        $this->assertStringContainsString('espresso and filter coffee', $document->extracted_text);
    }

    public function test_a_different_session_cannot_download_a_private_document(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('documents/1/private.pdf', 'private source evidence');

        $business = Business::create([
            'name' => 'Sample Cafe',
            'city' => 'Pune',
            'owner_session_token_hash' => hash('sha256', 'owner-session'),
        ]);
        $document = $business->documents()->create([
            'original_name' => 'private.pdf',
            'stored_path' => 'documents/1/private.pdf',
            'mime_type' => 'application/pdf',
            'size' => 23,
            'kind' => 'document',
            'extracted_text' => 'private source evidence',
            'extraction_status' => 'done',
        ]);

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => 'different-session',
        ])->get(route('documents.download', [$business, $document]));

        $response->assertNotFound()
            ->assertDontSee('private source evidence');
    }

    public function test_creator_session_can_download_a_private_document(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('documents/1/private.pdf', 'private file bytes');

        $ownerToken = 'owner-session';
        $business = Business::create([
            'name' => 'Sample Cafe',
            'city' => 'Pune',
            'owner_session_token_hash' => hash('sha256', $ownerToken),
        ]);
        $document = $business->documents()->create([
            'original_name' => 'private.pdf',
            'stored_path' => 'documents/1/private.pdf',
            'mime_type' => 'application/pdf',
            'size' => 17,
            'kind' => 'document',
            'extracted_text' => 'private source evidence',
            'extraction_status' => 'done',
        ]);

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => $ownerToken,
        ])->get(route('documents.download', [$business, $document]));

        $response->assertDownload('private.pdf');
    }

    public function test_owner_cannot_download_a_document_through_another_business_route(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('documents/2/private.pdf', 'private file bytes');

        $ownerToken = 'owner-session';
        $business = Business::create([
            'name' => 'Sample Cafe',
            'city' => 'Pune',
            'owner_session_token_hash' => hash('sha256', $ownerToken),
        ]);
        $otherBusiness = Business::create([
            'name' => 'Another Cafe',
            'city' => 'Mumbai',
            'owner_session_token_hash' => hash('sha256', 'another-owner-session'),
        ]);
        $document = $otherBusiness->documents()->create([
            'original_name' => 'private.pdf',
            'stored_path' => 'documents/2/private.pdf',
            'mime_type' => 'application/pdf',
            'size' => 17,
            'kind' => 'document',
            'extraction_status' => 'done',
        ]);

        $response = $this->withSession([
            Business::OWNER_SESSION_KEY => $ownerToken,
        ])->get(route('documents.download', [$business, $document]));

        $response->assertNotFound();
    }

    private function pdfWithText(string $text): string
    {
        $stream = 'BT /F1 18 Tf 20 100 Td ('.$text.') Tj ET';
        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 300 144] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            '<< /Length '.strlen($stream)." >>\nstream\n".$stream."\nendstream",
        ];
        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($index + 1)." 0 obj\n".$object."\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 6\n0000000000 65535 f \n";

        foreach (array_slice($offsets, 1) as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        return $pdf
            ."trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n"
            .$xrefOffset."\n%%EOF";
    }
}
