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
                <a class="nav-link" href="#process">How it works</a>
                <a class="nav-cta" href="{{ route('businesses.create') }}">
                    Get started <span aria-hidden="true">↗</span>
                </a>
            </nav>
        </header>

        <main>
            <section class="hero mx-auto grid grid-cols-1 items-center gap-12 lg:grid-cols-2" aria-labelledby="hero-title">
                <div class="hero-copy">
                    <p class="eyebrow"><span class="eyebrow-dot"></span> A new home for your business</p>
                    <h1 id="hero-title">A website that feels <span>like it was made for you.</span></h1>
                    <p class="hero-description">
                        Start with your real business details and materials. Shape a polished online home from the information you already have.
                    </p>
                    <div class="hero-actions flex flex-wrap items-center gap-4">
                        <a class="primary-cta" href="{{ route('businesses.create') }}">
                            Build my website <span aria-hidden="true">→</span>
                        </a>
                        <a class="text-link" href="#process">See how it works <span aria-hidden="true">↓</span></a>
                    </div>
                    <div class="build-note">
                        <span class="note-icon" aria-hidden="true">i</span>
                        <p><strong>Early access, honestly.</strong> Profiles, PDF text extraction, AI drafts, editing, and publishing work today. Social scraping and image text recognition are not available yet.</p>
                    </div>
                </div>

                <div class="website-universe" role="img" aria-label="Illustrative previews of many possible business website designs">
                    <div class="universe-topline"><span>ONE STARTING POINT</span><span>A WORLD OF WEB DESIGN</span></div>
                    <div class="preview-orbit orbit-back" aria-hidden="true"></div>
                    <div class="preview-orbit orbit-front" aria-hidden="true"></div>
                    <div class="preview-satellite satellite-one" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
                    <div class="preview-satellite satellite-two" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
                    <div class="preview-satellite satellite-three" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
                    <div class="preview-satellite satellite-four" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
                    <div class="site-preview preview-left" aria-hidden="true">
                        <div class="preview-browser"><i></i><i></i><i></i><span>your-business.site</span></div>
                        <div class="preview-content preview-content-editorial">
                            <span class="preview-mini-kicker">A PLACE OF YOUR OWN</span>
                            <strong>Built around<br>your story.</strong>
                            <span class="preview-mini-link">EXPLORE <b>↗</b></span>
                        </div>
                    </div>
                    <div class="site-preview preview-right" aria-hidden="true">
                        <div class="preview-browser"><i></i><i></i><i></i><span>your-business.site</span></div>
                        <div class="preview-content preview-content-grid">
                            <span class="preview-mini-kicker">YOUR NEXT CHAPTER</span>
                            <div class="preview-mini-cards"><i></i><i></i><i></i></div>
                        </div>
                    </div>
                    <div class="site-preview preview-main" aria-hidden="true">
                        <div class="preview-browser"><i></i><i></i><i></i><span>your-business.site</span></div>
                        <div class="preview-content preview-content-main">
                            <span class="preview-mini-kicker">MADE FOR YOUR BUSINESS</span>
                            <strong>Your own little<br>corner of the web.</strong>
                            <div class="preview-mini-lines"><i></i><i></i></div>
                            <span class="preview-mini-button">MAKE IT YOURS <b>→</b></span>
                        </div>
                    </div>
                    <span class="universe-caption"><i></i> YOUR BUSINESS, IN ITS OWN LIGHT</span>
                </div>
            </section>

            <section class="journey-section mx-auto" id="process" aria-labelledby="journey-title">
                <div class="section-heading flex flex-wrap items-end justify-between gap-6">
                    <div>
                        <p class="section-kicker">FROM YOUR STORY TO YOUR SITE</p>
                        <h2 id="journey-title">Your next home online. <span>Built from what is real.</span></h2>
                    </div>
                    <p class="section-intro">A modern website starts with your details—not made-up business claims. Bring what you know; shape what comes next.</p>
                </div>
                <div class="steps-grid grid grid-cols-1 gap-4 md:grid-cols-3">
                    <article class="step-card">
                        <span class="step-number">01</span>
                        <span class="step-symbol" aria-hidden="true">⌖</span>
                        <h3>Start with your business</h3>
                        <p>Add your name, city, and the links where customers already find you.</p>
                        <span class="step-status"><i></i> Available now</span>
                    </article>
                    <article class="step-card">
                        <span class="step-number">02</span>
                        <span class="step-symbol" aria-hidden="true">⌁</span>
                        <h3>Bring your real details</h3>
                        <p>Upload readable PDFs and useful materials to ground your website draft.</p>
                        <span class="step-status"><i></i> Available now</span>
                    </article>
                    <article class="step-card step-card-next">
                        <span class="step-number">03</span>
                        <span class="step-symbol" aria-hidden="true">✳</span>
                        <h3>Make it your own</h3>
                        <p>Review and edit a draft, then publish your own modern home on the web.</p>
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
