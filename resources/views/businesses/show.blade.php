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

            <p class="workspace-note"><span class="note-icon" aria-hidden="true">i</span> AI website generation and publishing are still in development. Your profile and uploaded materials are saved here.</p>
        </main>

        <footer class="site-footer mx-auto flex flex-wrap items-center justify-between gap-4">
            <span class="footer-note">A work in progress, built for real small businesses.</span>
            <a class="footer-link" href="{{ route('businesses.create') }}">Add another business <span aria-hidden="true">↗</span></a>
        </footer>
    </div>
</body>
</html>
