<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Start building your business's online presence with your business details, links, and documents.">
    <title>Find Your Way Online | AI Builds My Website</title>
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
            <nav class="header-actions flex items-center gap-6" aria-label="Main navigation">
                <a class="nav-link" href="#journey">How it works</a>
                <a class="nav-cta" href="{{ route('businesses.create') }}">
                    Get started <span aria-hidden="true">↗</span>
                </a>
            </nav>
        </header>

        <main>
            <section class="hero mx-auto grid grid-cols-1 items-center gap-12 lg:grid-cols-2" aria-labelledby="hero-title">
                <div class="hero-copy">
                    <p class="eyebrow"><span class="eyebrow-dot"></span> A new path for small business</p>
                    <h1 id="hero-title">Your business has a story. <span>Let's find its way online.</span></h1>
                    <p class="hero-description">
                        Start with what you know: your business details, links, and useful documents.
                        We’ll help put the pieces in place for your online presence.
                    </p>
                    <div class="hero-actions flex flex-wrap items-center gap-4">
                        <a class="primary-cta" href="{{ route('businesses.create') }}">
                            Start your business profile <span aria-hidden="true">→</span>
                        </a>
                        <a class="text-link" href="#journey">Explore the path <span aria-hidden="true">↓</span></a>
                    </div>
                    <div class="build-note">
                        <span class="note-icon" aria-hidden="true">i</span>
                        <p><strong>Early access, honestly.</strong> Profiles, PDF text extraction, AI drafts, editing, and publishing work today. Social scraping and image text recognition are not available yet.</p>
                    </div>
                </div>

                <div class="maze-wrap" role="img" aria-label="An illuminated maze leading toward your business's online home">
                    <div class="maze-glow"></div>
                    <div class="maze-topline flex items-center justify-between">
                        <span>THE WAY FORWARD</span>
                        <span class="maze-coordinate">01 <i>/</i> 03</span>
                    </div>
                    <svg class="maze-art" viewBox="0 0 560 500" fill="none" aria-hidden="true">
                        <defs>
                            <linearGradient id="route-gradient" x1="120" y1="410" x2="414" y2="94" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#D2FF72" stop-opacity=".2"/>
                                <stop offset=".52" stop-color="#D2FF72"/>
                                <stop offset="1" stop-color="#B9F5D0"/>
                            </linearGradient>
                            <filter id="route-glow" x="35" y="12" width="480" height="470" filterUnits="userSpaceOnUse">
                                <feGaussianBlur stdDeviation="8" result="blur"/>
                                <feComposite in="SourceGraphic" in2="blur" operator="over"/>
                            </filter>
                        </defs>
                        <path class="maze-line maze-line-faint" d="M66 438V62h426v376H118V112h322v276H170V162h218v176H222V214h114v72h-62"/>
                        <path class="maze-line maze-line-faint" d="M118 62v28h322V62M170 112v26h218v-26M222 162v26h114v-26M274 286v40h-52M388 214h52v-50M440 338h52M118 388H66"/>
                        <path class="maze-route-glow" d="M66 438V62h426v376H118V112h322v276H170V162h218v176H222v-62h62" filter="url(#route-glow)"/>
                        <path class="maze-route" d="M66 438V62h426v376H118V112h322v276H170V162h218v176H222v-62h62"/>
                        <circle class="route-start" cx="66" cy="438" r="9"/>
                        <circle class="route-target" cx="284" cy="276" r="7"/>
                        <circle class="route-pulse" cx="284" cy="276" r="18"/>
                        <circle class="maze-star" cx="440" cy="112" r="3"/>
                        <circle class="maze-star" cx="118" cy="338" r="3"/>
                        <circle class="maze-star" cx="388" cy="388" r="3"/>
                    </svg>
                    <div class="maze-label label-start"><span class="label-pin"></span><span>START WITH YOUR STORY</span></div>
                    <div class="maze-label label-finish"><span class="label-pin"></span><span>YOUR BUSINESS, ONLINE</span></div>
                    <div class="maze-card">
                        <span class="maze-card-icon" aria-hidden="true">✳</span>
                        <div>
                            <span class="maze-card-kicker">THE FIRST TURN</span>
                            <strong>Your business details</strong>
                        </div>
                        <span class="maze-card-arrow" aria-hidden="true">↗</span>
                    </div>
                    <p class="maze-caption">Every good destination starts with a first step.</p>
                </div>
            </section>

            <section class="journey-section mx-auto" id="journey" aria-labelledby="journey-title">
                <div class="section-heading flex flex-wrap items-end justify-between gap-6">
                    <div>
                        <p class="section-kicker">NO WRONG TURNS</p>
                        <h2 id="journey-title">A simple start. <span>One step at a time.</span></h2>
                    </div>
                    <p class="section-intro">Bring the information you already have. Your business profile keeps it together through drafting and publishing.</p>
                </div>
                <div class="steps-grid grid grid-cols-1 gap-4 md:grid-cols-3">
                    <article class="step-card">
                        <span class="step-number">01</span>
                        <span class="step-symbol" aria-hidden="true">⌖</span>
                        <h3>Tell us the basics</h3>
                        <p>Add your business name, city, and the links where customers can find you.</p>
                        <span class="step-status"><i></i> Available now</span>
                    </article>
                    <article class="step-card">
                        <span class="step-number">02</span>
                        <span class="step-symbol" aria-hidden="true">⌁</span>
                        <h3>Bring your materials</h3>
                        <p>Upload price lists, brochures, and photos to keep your useful details in one place.</p>
                        <span class="step-status"><i></i> Available now</span>
                    </article>
                    <article class="step-card step-card-next">
                        <span class="step-number">03</span>
                        <span class="step-symbol" aria-hidden="true">✳</span>
                        <h3>Shape your website</h3>
                        <p>Generate a draft, review and edit the details, then publish a public snapshot.</p>
                        <span class="step-status"><i></i> Available now</span>
                    </article>
                </div>
            </section>
        </main>

        <footer class="site-footer mx-auto flex flex-wrap items-center justify-between gap-4">
            <a class="brand footer-brand flex items-center gap-3" href="/">
                <span class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 36 36" fill="none">
                        <path d="M5 11h15v7H12v8h14V12h5v19H7V14h13" />
                        <circle cx="27" cy="8" r="3" />
                    </svg>
                </span>
                <span>AI builds <strong>my website</strong></span>
            </a>
            <p>A work in progress, built for real small businesses.</p>
            <a class="footer-link" href="{{ route('businesses.create') }}">Take the first step <span aria-hidden="true">↗</span></a>
        </footer>
    </div>
</body>
</html>
