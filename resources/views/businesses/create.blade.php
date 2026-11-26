<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Add your business details and the places customers can find you online.">
    <title>Start Your Business Profile | AI Builds My Website</title>
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
            <a class="nav-link" href="/">← Back to home</a>
        </header>

        <main class="form-layout mx-auto grid grid-cols-1 gap-10 lg:grid-cols-2">
            <section class="form-intro">
                <p class="eyebrow"><span class="eyebrow-dot"></span> YOUR FIRST WAYPOINT</p>
                <h1>Every great journey starts <span>with the basics.</span></h1>
                <p class="hero-description">Tell us a little about your business. Add the places where customers can already find you.</p>

                <div class="route-list" aria-label="Getting started">
                    <div class="route-step active">
                        <span class="route-step-mark">01</span>
                        <div><strong>Your business</strong><span>Name and city</span></div>
                        <span class="route-step-state">YOU ARE HERE</span>
                    </div>
                    <div class="route-step">
                        <span class="route-step-mark">02</span>
                        <div><strong>Your materials</strong><span>Links, PDFs, and photos</span></div>
                    </div>
                    <div class="route-step">
                        <span class="route-step-mark">03</span>
                        <div><strong>Your website</strong><span>Review, edit, and publish your draft</span></div>
                    </div>
                </div>
                <p class="form-footnote">No website is generated yet. This step saves your business details so you can add supporting materials next.</p>
            </section>

            <section class="form-panel" aria-labelledby="form-title">
                <div class="panel-heading">
                    <span class="panel-count">STEP 01 <i>/ 03</i></span>
                    <h2 id="form-title">Where should we begin?</h2>
                    <p>Fields marked <span aria-hidden="true">*</span> are required.</p>
                </div>

                @if($errors->any())
                    <div class="form-alert" role="alert">
                        <strong>Please check the details below.</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('businesses.store') }}">
                    @csrf
                    <div class="field-grid">
                        <div class="field-group">
                            <label for="name">Business name <span>*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. The Corner Cafe" autocomplete="organization" required>
                            @error('name') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field-group">
                            <label for="city">City <span>*</span></label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}" placeholder="e.g. Mumbai" autocomplete="address-level2" required>
                            @error('city') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-divider"><span>YOUR ONLINE TRAIL</span></div>
                    <p class="field-hint">Add any links you already have. You can leave these blank.</p>

                    <div class="field-grid">
                        <div class="field-group">
                            <label for="google_business_url">Google Business Profile</label>
                            <input type="url" id="google_business_url" name="google_business_url" value="{{ old('google_business_url') }}" placeholder="https://..." inputmode="url">
                            @error('google_business_url') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field-group">
                            <label for="facebook_url">Facebook</label>
                            <input type="url" id="facebook_url" name="facebook_url" value="{{ old('facebook_url') }}" placeholder="https://..." inputmode="url">
                            @error('facebook_url') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field-group">
                            <label for="instagram_url">Instagram</label>
                            <input type="url" id="instagram_url" name="instagram_url" value="{{ old('instagram_url') }}" placeholder="https://..." inputmode="url">
                            @error('instagram_url') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="field-group">
                            <label for="website_url">Existing website</label>
                            <input type="url" id="website_url" name="website_url" value="{{ old('website_url') }}" placeholder="https://..." inputmode="url">
                            @error('website_url') <span class="field-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <button class="primary-cta form-submit" type="submit">
                        Save and continue <span aria-hidden="true">→</span>
                    </button>
                </form>
            </section>
        </main>

        <footer class="site-footer mx-auto flex flex-wrap items-center justify-between gap-4">
            <span class="footer-note">Your details stay on this project.</span>
            <a class="footer-link" href="/">Return home <span aria-hidden="true">↗</span></a>
        </footer>
    </div>
</body>
</html>
