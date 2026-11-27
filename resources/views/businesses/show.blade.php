<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Your saved business profile and supporting materials.">
    <title>{{ $business->name }} | Business Workspace</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen">
    <div class="site-shell">
        <header class="site-header mx-auto flex items-center justify-between">
            <a class="brand flex items-center gap-3" href="/" aria-label="AI Builds My Website home">
                <span class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 36 36" fill="none">
                        <path d="M5 11h15v7H12v8h14V12h5v19H7V14h13" />
                        <circle cx="27" cy="8" r="3" />
                    </svg>
                </span>
                <span>AI builds <strong>my website</strong></span>
            </a>
            <a class="nav-cta" href="{{ route('businesses.create') }}">Add a business <span aria-hidden="true">↗</span></a>
        </header>

        <main class="workspace mx-auto">
            <div class="workspace-breadcrumb">
                <a href="/">Home</a><span aria-hidden="true">/</span><span>Business workspace</span>
            </div>

            <section class="workspace-hero">
                <div class="workspace-orbit" aria-hidden="true">
                    <span class="orbit-ring ring-one"></span>
                    <span class="orbit-ring ring-two"></span>
                    <span class="orbit-core">✳</span>
                </div>
                <div class="workspace-title">
                    <p class="eyebrow"><span class="eyebrow-dot"></span> YOUR BUSINESS WAYPOINT</p>
                    <h1>{{ $business->name }}</h1>
                    <p class="workspace-subtitle">A starting point for your business's online presence.</p>
                </div>
                <div class="workspace-status">
                    <span class="panel-count">PROFILE STATUS</span>
                    <span class="status-pill"><i></i>{{ ucfirst($business->status) }}</span>
                </div>
            </section>

            <section class="generation-section" aria-labelledby="generation-title">
                <div class="generation-heading">
                    <div>
                        <p class="section-kicker">AI-ASSISTED FIRST DRAFT</p>
                        <h2 id="generation-title">Turn your sources into a starting point</h2>
                    </div>
                    @if($canManageBusiness)
                        <form method="POST" action="{{ route('businesses.generate', $business) }}">
                            @csrf
                            <button class="primary-cta generation-button" type="submit">
                                {{ $business->generated_content ? 'Generate again' : 'Generate a draft' }}
                                <span aria-hidden="true">→</span>
                            </button>
                        </form>
                    @endif
                </div>

                @if(session('generation_status'))
                    <div class="generation-alert success-alert" role="status">{{ session('generation_status') }}</div>
                @endif
                @if(session('draft_status'))
                    <div class="generation-alert success-alert" role="status">{{ session('draft_status') }}</div>
                @endif
                @if(session('duplicate_status'))
                    <div class="generation-alert success-alert" role="status">{{ session('duplicate_status') }}</div>
                @endif
                @if(session('publish_status'))
                    <div class="generation-alert success-alert" role="status">
                        {{ session('publish_status') }}
                        <a href="{{ route('sites.show', $business->slug) }}" target="_blank" rel="noopener noreferrer">Open the public website</a>
                    </div>
                @endif
                @if($errors->has('draft') && ! $business->generated_content)
                    <div class="generation-alert form-alert" role="alert">{{ $errors->first('draft') }}</div>
                @endif
                @if($errors->has('publish') && ! $business->generated_content)
                    <div class="generation-alert form-alert" role="alert">{{ $errors->first('publish') }}</div>
                @endif
                @unless($canManageBusiness)
                    <p class="generation-privacy">
                        @if($business->owner_session_token_hash)
                            This profile is read-only in this browser session. Only the session that created it can edit, upload, or regenerate content.
                        @else
                            This profile was created before owner access was added, so it is read-only. Create a new business to edit it.
                        @endif
                    </p>
                @else
                    <p class="generation-privacy">Edit access is tied to this browser session. If the session expires or its cookies are cleared, this profile becomes read-only.</p>
                @endunless

                @if($business->error_message)
                    <div class="generation-alert form-alert" role="alert">
                        {{ $business->error_message }}
                        <span>Your business details and uploads are still saved. You can try again.</span>
                    </div>
                @endif

                <p class="generation-privacy">
                    Generate sends your business name, city, and text extracted from up to 10 recent readable documents
                    (up to 10,000 characters each) to Google Gemini's free tier. Google may use free-tier content
                    to improve its products. Avoid confidential documents. Social links are not opened and photos are not analyzed.
                </p>

                @if($business->generated_content)
                    <div class="generated-preview">
                        <div class="preview-heading">
                            <div>
                                <span class="panel-count">DRAFT PREVIEW</span>
                                <h3>{{ $business->generated_content['title'] }}</h3>
                                <p>{{ $business->generated_content['city'] }}</p>
                            </div>
                            <span class="draft-badge"><i></i> Review before use</span>
                        </div>

                        <div class="tagline-preview">
                            <span class="fact-label">{{ ($business->generated_content['tagline_owner_edited'] ?? false) ? 'OWNER-EDITED WELCOME · REVIEW BEFORE USE' : 'AI SUGGESTED WELCOME · REVIEW BEFORE USE' }}</span>
                            <p>{{ $business->generated_content['tagline'] }}</p>
                            @if($business->generated_content['tagline_owner_edited'] ?? false)
                                <span class="source-note">Original AI draft: {{ $business->generated_content['tagline_original'] }}</span>
                            @endif
                        </div>

                        @if($business->generated_content['review_notice'] ?? null)
                            <p class="review-notice">{{ $business->generated_content['review_notice'] }}</p>
                        @endif

                        @if(count($business->generated_content['facts'] ?? []))
                            <div class="preview-subsection">
                                <h4>Draft business details</h4>
                                <ul class="generated-facts">
                                    @foreach($business->generated_content['facts'] as $fact)
                                        <li>
                                            <span class="generated-field">{{ ucfirst(str_replace('_', ' ', $fact['field'])) }}</span>
                                            <strong>{{ $fact['value'] }}</strong>
                                            @if($fact['owner_edited'] ?? false)
                                                <span class="source-note">Owner-edited. This value is not automatically verified against the source. Original AI draft: {{ $fact['original_value'] }}</span>
                                            @else
                                                <span class="source-note">From {{ $fact['source'] }} · “{{ $fact['evidence'] }}”</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <p class="no-facts-note">No source-backed details were returned. Check the original documents before using this draft.</p>
                        @endif

                        @if(count($business->generated_content['conflicts'] ?? []))
                            <div class="preview-subsection conflict-subsection">
                                <h4>Conflicting details to review</h4>
                                @foreach($business->generated_content['conflicts'] as $conflict)
                                    <div class="conflict-item">
                                        <strong>{{ ucfirst(str_replace('_', ' ', $conflict['field'])) }}</strong>
                                        @foreach($conflict['claims'] as $claim)
                                            <span>{{ $claim['value'] }} <small>— {{ $claim['source'] }}</small></span>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if(count($business->generated_content['missing_information'] ?? []))
                            <div class="preview-subsection">
                                <h4>Not found in reviewed sources</h4>
                                <div class="missing-list">
                                    @foreach($business->generated_content['missing_information'] as $item)
                                        <span>{{ ucfirst(str_replace('_', ' ', $item)) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(count($business->generated_content['unread_links'] ?? []))
                            <p class="unread-note">Links provided but not read: {{ implode(', ', $business->generated_content['unread_links']) }}.</p>
                        @endif
                    </div>

                    @if($canManageBusiness)
                        <details class="draft-edit-panel">
                            <summary>Edit the draft</summary>
                            <form method="POST" action="{{ route('businesses.draft.update', $business) }}">
                                @csrf
                                @method('PATCH')
                                <p class="draft-edit-note">Your changes are saved as owner edits. Changed facts are no longer automatically source-verified. Generating again replaces this draft.</p>

                                @if($errors->has('draft') || $errors->has('facts'))
                                    <div class="form-alert" role="alert">
                                        {{ $errors->first('draft') ?: $errors->first('facts') }}
                                    </div>
                                @endif

                                <div class="draft-edit-fields">
                                    <label for="draft-tagline">Welcome tagline</label>
                                    <input id="draft-tagline" name="tagline" type="text" maxlength="255" required value="{{ old('tagline', $business->generated_content['tagline']) }}">
                                    @error('tagline')
                                        <span class="draft-field-error">{{ $message }}</span>
                                    @enderror

                                    @forelse($business->generated_content['facts'] ?? [] as $index => $fact)
                                        <label for="draft-fact-{{ $index }}">{{ ucfirst(str_replace('_', ' ', $fact['field'])) }}</label>
                                        <textarea id="draft-fact-{{ $index }}" name="facts[{{ $index }}][value]" maxlength="400" required>{{ old("facts.$index.value", $fact['value']) }}</textarea>
                                        <span class="source-note">
                                            @if($fact['owner_edited'] ?? false)
                                                Owner-edited · originally: {{ $fact['original_value'] }}
                                            @else
                                                Source: {{ $fact['source'] }} · “{{ $fact['evidence'] }}”
                                            @endif
                                        </span>
                                        @error("facts.$index.value")
                                            <span class="draft-field-error">{{ $message }}</span>
                                        @enderror
                                    @empty
                                        <p class="no-facts-note">No source-backed facts were returned. You can still edit the tagline.</p>
                                    @endforelse
                                </div>

                                <button class="primary-cta generation-button draft-save-button" type="submit">Save edits <span aria-hidden="true">→</span></button>
                            </form>
                        </details>

                        <div class="publish-panel">
                            <div>
                                <p class="section-kicker">PUBLIC WEBSITE</p>
                                <h3>{{ $business->published_content ? 'Your website is live' : 'Ready to publish?' }}</h3>
                                @if($business->published_content)
                                    <a class="published-preview-link" href="{{ route('sites.show', $business->slug) }}" target="_blank" rel="noopener noreferrer">
                                        View the current public version <span aria-hidden="true">↗</span>
                                    </a>
                                    <p class="draft-edit-note">Saved edits and regenerated drafts stay private until you publish the updates.</p>
                                @endif
                            </div>

                            @if($hasUnpublishedChanges)
                                <p class="publish-disclosure">Unresolved conflicting details and uploaded files are excluded from the public page. Owner-edited facts are shown as business-provided.</p>

                                @if($errors->has('publish') || $errors->has('confirm_review'))
                                    <div class="form-alert" role="alert">
                                        {{ $errors->first('publish') ?: $errors->first('confirm_review') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('businesses.publish', $business) }}">
                                    @csrf
                                    <label class="publish-confirmation">
                                        <input type="checkbox" name="confirm_review" value="yes" required {{ old('confirm_review') ? 'checked' : '' }}>
                                        I reviewed this draft and confirm I want this version to be public.
                                    </label>
                                    <button class="primary-cta generation-button" type="submit">
                                        {{ $business->published_content ? 'Publish updates' : 'Publish website' }}
                                        <span aria-hidden="true">→</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                @endif
            </section>

            <section class="business-facts" aria-labelledby="facts-title">
                <div class="content-heading">
                    <div>
                        <p class="section-kicker">THE DETAILS YOU SHARED</p>
                        <h2 id="facts-title">Your business map</h2>
                    </div>
                    <span class="fact-location"><span aria-hidden="true">⌖</span> {{ $business->city }}</span>
                </div>
                <div class="facts-grid">
                    <div class="fact-card">
                        <span class="fact-icon" aria-hidden="true">⌖</span>
                        <span class="fact-label">CITY</span>
                        <strong>{{ $business->city }}</strong>
                    </div>
                    @if($business->google_business_url)
                        <a class="fact-card fact-link" href="{{ $business->google_business_url }}" target="_blank" rel="noopener noreferrer">
                            <span class="fact-icon" aria-hidden="true">G</span>
                            <span class="fact-label">GOOGLE BUSINESS</span>
                            <strong>Open profile <span aria-hidden="true">↗</span></strong>
                        </a>
                    @endif
                    @if($business->facebook_url)
                        <a class="fact-card fact-link" href="{{ $business->facebook_url }}" target="_blank" rel="noopener noreferrer">
                            <span class="fact-icon" aria-hidden="true">f</span>
                            <span class="fact-label">FACEBOOK</span>
                            <strong>Open page <span aria-hidden="true">↗</span></strong>
                        </a>
                    @endif
                    @if($business->instagram_url)
                        <a class="fact-card fact-link" href="{{ $business->instagram_url }}" target="_blank" rel="noopener noreferrer">
                            <span class="fact-icon" aria-hidden="true">◎</span>
                            <span class="fact-label">INSTAGRAM</span>
                            <strong>Open profile <span aria-hidden="true">↗</span></strong>
                        </a>
                    @endif
                    @if($business->website_url)
                        <a class="fact-card fact-link" href="{{ $business->website_url }}" target="_blank" rel="noopener noreferrer">
                            <span class="fact-icon" aria-hidden="true">↗</span>
                            <span class="fact-label">EXISTING WEBSITE</span>
                            <strong>Visit website <span aria-hidden="true">↗</span></strong>
                        </a>
                    @endif
                </div>
            </section>

            <section class="materials-section" aria-labelledby="materials-title">
                <div class="content-heading">
                    <div>
                        <p class="section-kicker">YOUR SUPPORTING MATERIALS</p>
                        <h2 id="materials-title">Bring the useful details</h2>
                    </div>
                    <p class="materials-intro">Upload documents and photos to keep your business information together.</p>
                </div>
                @include('businesses._uploads')
            </section>

        </main>

        <footer class="site-footer mx-auto flex flex-wrap items-center justify-between gap-4">
            <span class="footer-note">A work in progress, built for real small businesses.</span>
            <a class="footer-link" href="{{ route('businesses.create') }}">Add another business <span aria-hidden="true">↗</span></a>
        </footer>
    </div>
</body>
</html>
