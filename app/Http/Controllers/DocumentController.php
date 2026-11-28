<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Document;
use App\Services\TextExtractor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends Controller
{
    // Receive uploaded files for one business and save them.
    public function store(Request $request, Business $business, TextExtractor $textExtractor): RedirectResponse
    {
        abort_unless(
            $business->isOwnedBySession($request->session()->get(Business::OWNER_SESSION_KEY)),
            404,
        );

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

            $path = $file->store('documents/'.$business->id, 'local');

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

    public function download(
        Request $request,
        Business $business,
        Document $document,
    ): BinaryFileResponse {
        abort_unless(
            $business->isOwnedBySession($request->session()->get(Business::OWNER_SESSION_KEY)),
            404,
        );

        $document = $business->documents()->findOrFail($document->getKey());
        $disk = Storage::disk('local');
        abort_unless($disk->exists($document->stored_path), 404);

        return response()->download(
            $disk->path($document->stored_path),
            basename(str_replace('\\', '/', $document->original_name)),
        );
    }
}
