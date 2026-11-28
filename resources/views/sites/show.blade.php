<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $content['tagline'] }}">
    <title>{{ $business['name'] }} | {{ $business['city'] }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen">
    <div class="site-shell">
        <header class="published-header">
            <a class="published-brand" href="#top" aria-label="{{ $business['name'] }} home">
                <span class="published-brand-mark" aria-hidden="true"><i></i><i></i><i></i></span>
                <span>
                    <strong>{{ $business['name'] }}</strong>
                    <small>{{ $business['city'] }}</small>
                </span>
            </a>
            <nav class="published-nav" aria-label="Website navigation">
                @if($content['facts']->isNotEmpty())
                    <a href="#details">Discover</a>
                @endif
                @if($links->isNotEmpty())
                    <a class="published-nav-cta" href="#connect">Find us online <span aria-hidden="true">↗</span></a>
                @endif
            </nav>
        </header>

        <main class="published-site" id="top">
            <section class="published-hero">
                <div class="published-hero-copy">
                    <p class="published-overline"><span></span> {{ $business['city'] }} <i>/</i> BUSINESS PROFILE</p>
                    <h1>{{ $business['name'] }}</h1>
                    <p class="published-tagline">{{ $content['tagline'] }}</p>
                    @if($content['facts']->isNotEmpty())
                        <a class="published-hero-cta" href="#details">Discover the details <span aria-hidden="true">↓</span></a>
                    @endif
                </div>
                <div class="published-artwork" aria-hidden="true">
                    <div class="published-art-orbit orbit-one"></div>
                    <div class="published-art-orbit orbit-two"></div>
                    <div class="published-art-core"><span></span></div>
                    <span class="published-art-label">A PLACE<br>OF YOUR OWN</span>
                </div>
            </section>

            @if($content['facts']->isNotEmpty())
                <section class="published-details" id="details" aria-labelledby="published-details-title">
                    <div class="published-section-heading">
                        <div>
                            <p class="published-overline"><span></span> WHAT THE BUSINESS SHARED</p>
                            <h2 id="published-details-title">The details that matter.</h2>
                        </div>
                        <p>Specifics, shared by the business and reviewed before publishing.</p>
                    </div>
                    <div class="published-facts">
                        @foreach($content['facts'] as $fact)
                            <article class="published-fact">
                                <span class="published-fact-index">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <div>
                                    <h3>{{ ucfirst(str_replace('_', ' ', $fact['field'])) }}</h3>
                                    <p>{{ $fact['value'] }}</p>
                                </div>
                                @if($fact['owner_edited'])
                                    <span class="published-fact-source">Business-provided</span>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($links->isNotEmpty())
                <section class="published-links" id="connect" aria-labelledby="published-links-title">
                    <div class="published-section-heading">
                        <div>
                            <p class="published-overline"><span></span> FOLLOW THE CONNECTIONS</p>
                            <h2 id="published-links-title">Find us online.</h2>
                        </div>
                    </div>
                    <div class="published-link-list">
                        @foreach($links as $link)
                            <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer">
                                <span>{{ $link['label'] }}</span><b aria-hidden="true">↗</b>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>

        <footer class="published-footer">
            <a class="published-brand" href="#top">
                <span class="published-brand-mark" aria-hidden="true"><i></i><i></i><i></i></span>
                <span><strong>{{ $business['name'] }}</strong><small>{{ $business['city'] }}</small></span>
            </a>
            <span>Business information shared by the owner.</span>
            <a class="published-credit" href="/">A website made with <strong>AI Builds My Website</strong></a>
        </footer>
    </div>
</body>
</html>
