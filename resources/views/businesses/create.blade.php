<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Build Your Website | AI Builds My Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            margin: 0;
            padding: 40px 20px;
            color: #222;
        }
        .container {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.07);
        }
        h1 { margin-top: 0; }
        p { color: #666; line-height: 1.5; }
        label {
            display: block;
            margin-top: 18px;
            margin-bottom: 7px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }
        button {
            margin-top: 25px;
            padding: 13px 22px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
        .error { color: #dc2626; font-size: 14px; }
        a { color: #2563eb; }
    </style>
</head>
<body>
    <div class="container">
        <a href="/">← Back to Home</a>
        <h1>Tell us about your business</h1>
        <p>Enter your business details. We'll use this information to help build your website.</p>

        <form method="POST" action="{{ route('businesses.store') }}">
            @csrf

            <label for="name">Business Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name') <div class="error">{{ $message }}</div> @enderror

            <label for="city">City *</label>
            <input type="text" id="city" name="city" value="{{ old('city') }}" required>
            @error('city') <div class="error">{{ $message }}</div> @enderror

            <label for="google_business_url">Google Business Profile URL</label>
            <input type="url" id="google_business_url" name="google_business_url" value="{{ old('google_business_url') }}">

            <label for="facebook_url">Facebook URL</label>
            <input type="url" id="facebook_url" name="facebook_url" value="{{ old('facebook_url') }}">

            <label for="instagram_url">Instagram URL</label>
            <input type="url" id="instagram_url" name="instagram_url" value="{{ old('instagram_url') }}">

            <label for="website_url">Existing Website URL (Optional)</label>
            <input type="url" id="website_url" name="website_url" value="{{ old('website_url') }}">

            <button type="submit">Continue</button>
        </form>
    </div>
</body>
</html>
