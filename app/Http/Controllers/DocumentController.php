<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Services\TextExtractor;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    // Receive uploaded files for one business and save them.
    public function store(Request $request, Business $business, TextExtractor $textExtractor)
    {
        $request->validate([
            'files' => ['required', 'array', 'max:10'],
            'files.*' => ['file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ], [
            'files.required' => 'Please choose at least one file.',
            'files.*.mimes' => 'Only PDF, JPG, PNG and WEBP files are allowed.',
            'files.*.max' => 'Each file must be 10 MB or smaller.',
        ]);

        foreach ($request->file('files') as $file) {
            // Read these first, before the file is moved.
            $mime = $file->getMimeType();
            $size = $file->getSize();
            $name = $file->getClientOriginalName();

            // Saved with a random name inside storage/app/public/documents/{id}
            $path = $file->store('documents/'.$business->id, 'public');

            $document = $business->documents()->create([
                'original_name' => $name,
                'stored_path' => $path,
                'mime_type' => $mime,
                'size' => $size,
                'kind' => str_starts_with($mime, 'image/') ? 'image' : 'document',
            ]);

            $textExtractor->extract($document);
        }

        return redirect()
            ->route('businesses.show', $business)
            ->with('status', 'Files uploaded successfully.');
    }
}
