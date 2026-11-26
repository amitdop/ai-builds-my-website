<?php

namespace App\Services;

use App\Models\Document;
use Smalot\PdfParser\Parser;

class TextExtractor
{
    // Read the text out of one uploaded file and save it on its row.
    public function extract(Document $doc): void
    {
        // Photos have no text to read here. The AI will look at them directly later.
        if ($doc->kind !== 'document') {
            $doc->update(['extraction_status' => 'skipped']);

            return;
        }

        try {
            $path = storage_path('app/public/'.$doc->stored_path);
            $text = (new Parser)->parseFile($path)->getText();

            // Clean up: fix bad characters, collapse extra spaces and line breaks.
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
            $text = trim(preg_replace('/\s+/u', ' ', $text));

            if ($text === '') {
                $doc->update([
                    'extraction_status' => 'failed',
                    'extraction_error' => 'No readable text found. This may be a scanned PDF.',
                ]);

                return;
            }

            $doc->update([
                'extracted_text' => mb_substr($text, 0, 50000),
                'extraction_status' => 'done',
                'extraction_error' => null,
            ]);
        } catch (\Exception $e) {
            $doc->update([
                'extraction_status' => 'failed',
                'extraction_error' => 'Could not read this PDF.',
            ]);
        }
    }
}
