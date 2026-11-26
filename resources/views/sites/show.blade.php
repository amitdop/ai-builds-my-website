<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $content['tagline'] }}">
    <title>{{ $business->name }} | {{ $business->city }}</title>
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
            <span class="published-label">A BUSINESS WEBSITE</span>
        </header>

        <main class="published-site mx-auto">
            <section class="published-hero">
                <p class="eyebrow"><span class="eyebrow-dot"></span> {{ $business->city }}</p>
                <h1>{{ $business->name }}</h1>
                <p class="published-tagline">{{ $content['tagline'] }}</p>
                <span class="published-waypoint" aria-hidden="true">⌖</span>
            </section>

            @if($content['facts']->isNotEmpty())
                <section class="published-details" aria-labelledby="published-details-title">
                    <p class="section-kicker">THE BUSINESS</p>
                    <h2 id="published-details-title">A few details</h2>
                    <div class="published-facts">
                        @foreach($content['facts'] as $fact)
                            <article class="published-fact">
                                <h3>{{ ucfirst(str_replace('_', ' ', $fact['field'])) }}</h3>
                                <p>{{ $fact['value'] }}</p>
                                @if($fact['owner_edited'])
                                    <span>Business-provided</span>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($links->isNotEmpty())
                <section class="published-links" aria-labelledby="published-links-title">
                    <p class="section-kicker">FIND US ONLINE</p>
                    <h2 id="published-links-title">Stay connected</h2>
                    <div class="published-link-list">
                        @foreach($links as $link)
                            <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer">
                                {{ $link['label'] }} <span aria-hidden="true">↗</span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>

        <footer class="site-footer mx-auto flex flex-wrap items-center justify-between gap-4">
            <span class="footer-note">Business information shared by the owner.</span>
            <span class="footer-link">Made with AI Builds My Website</span>
        </footer>
    </div>
</body>
</html>
