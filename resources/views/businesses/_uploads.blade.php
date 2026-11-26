<style>
    .upload-box { border: 2px dashed #c7d2fe; border-radius: 10px; padding: 20px; background: #f8faff; }
    .upload-box p { margin-top: 0; color: #555; }
    .success { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin: 16px 0; }
    .error-box { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin: 16px 0; }
    .error-box ul { margin: 0; padding-left: 20px; }
    .files { list-style: none; padding: 0; margin: 0; }
    .files li { padding: 10px 0; border-bottom: 1px solid #eee; }
    .muted { color: #777; font-size: 14px; }
</style>

<h2 style="margin-top:36px">Documents and photos</h2>

@if(session('status'))
    <div class="success">{{ session('status') }}</div>
@endif

@if($errors->any())
    <div class="error-box">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form class="upload-box" method="POST" action="{{ route('documents.store', $business) }}" enctype="multipart/form-data">
    @csrf
    <p>Upload price lists, brochures (PDF) and business photos (JPG, PNG, WEBP). Up to 10 files, 10 MB each.</p>
    <input type="file" name="files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.webp" required>
    <br>
    <button class="button" type="submit">Upload files</button>
</form>

<h2 style="margin-top:36px">Uploaded files ({{ $business->documents->count() }})</h2>
@if($business->documents->isEmpty())
    <p class="muted">No files uploaded yet.</p>
@else
    <ul class="files">
        @foreach($business->documents as $doc)
            <li>
                <a href="{{ asset('storage/' . $doc->stored_path) }}" target="_blank" rel="noopener noreferrer">{{ $doc->original_name }}</a>
                <span class="muted">· {{ ucfirst($doc->kind) }} · {{ number_format($doc->size / 1024, 0) }} KB</span>
            </li>
        @endforeach
    </ul>
@endif
