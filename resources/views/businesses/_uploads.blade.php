<div class="upload-panel">
    @if(session('status'))
        <div class="form-alert success-alert" role="status">{{ session('status') }}</div>
    @endif

    @if($errors->has('files') || $errors->has('files.*'))
        <div class="form-alert" role="alert">
            <strong>We couldn't upload those files.</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($canManageBusiness)
        <form class="upload-form" method="POST" action="{{ route('documents.store', $business) }}" enctype="multipart/form-data">
            @csrf
            <label class="file-picker" for="business-files">
                <span class="file-picker-icon" aria-hidden="true">↑</span>
                <strong>Choose files for your journey</strong>
                <span>PDF, JPG, PNG, or WEBP · Up to 10 files, 10 MB each</span>
            </label>
            <input class="file-input" id="business-files" type="file" name="files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.webp" required>
            <button class="primary-cta upload-submit" type="submit">Upload materials <span aria-hidden="true">→</span></button>
        </form>
    @endif

    <div class="uploaded-heading">
        <h3>Your uploaded files</h3>
        <span>{{ $business->documents->count() }} {{ \Illuminate\Support\Str::plural('file', $business->documents->count()) }}</span>
    </div>

    @if($business->documents->isEmpty())
        <div class="empty-files">
            <span aria-hidden="true">⌁</span>
            <p>No materials yet. Your first upload will show up here.</p>
        </div>
    @else
        <ul class="file-list">
            @foreach($business->documents as $doc)
                <li class="file-row">
                    <span class="document-icon" aria-hidden="true">{{ $doc->kind === 'image' ? '▧' : '▤' }}</span>
                    <div class="file-details">
                        <a href="{{ asset('storage/' . $doc->stored_path) }}" target="_blank" rel="noopener noreferrer">{{ $doc->original_name }}</a>
                        <span>{{ ucfirst($doc->kind) }} · {{ number_format($doc->size / 1024, 0) }} KB</span>
                        @if($doc->extraction_error)
                            <span class="extraction-error">{{ $doc->extraction_error }}</span>
                        @endif
                    </div>
                    <span class="extraction-status status-{{ $doc->extraction_status }}">
                        <i></i>{{ ucfirst($doc->extraction_status) }}
                    </span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
