<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Details | AI Builds My Website</title>
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
        h1 { margin-top: 20px; }
        .detail { margin: 18px 0; }
        .detail strong { display: block; margin-bottom: 6px; }
        a { color: #2563eb; }
        .status {
            display: inline-block;
            padding: 6px 12px;
            background: #fef3c7;
            border-radius: 20px;
        }
        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="/">← Home</a>

        <h1>{{ $business->name }}</h1>
        <p>Your business details have been saved successfully.</p>

        <div class="detail">
            <strong>City</strong>
            {{ $business->city }}
        </div>

        <div class="detail">
            <strong>Status</strong>
            <span class="status">{{ ucfirst($business->status) }}</span>
        </div>

        @if($business->google_business_url)
            <div class="detail">
                <strong>Google Business Profile</strong>
                <a href="{{ $business->google_business_url }}" target="_blank" rel="noopener noreferrer">View Profile</a>
            </div>
        @endif

        @if($business->facebook_url)
            <div class="detail">
                <strong>Facebook</strong>
                <a href="{{ $business->facebook_url }}" target="_blank" rel="noopener noreferrer">Visit Facebook</a>
            </div>
        @endif

        @if($business->instagram_url)
            <div class="detail">
                <strong>Instagram</strong>
                <a href="{{ $business->instagram_url }}" target="_blank" rel="noopener noreferrer">Visit Instagram</a>
            </div>
        @endif

        @if($business->website_url)
            <div class="detail">
                <strong>Existing Website</strong>
                <a href="{{ $business->website_url }}" target="_blank" rel="noopener noreferrer">Visit Website</a>
            </div>
        @endif

@include('businesses._uploads')
        <a class="button" href="{{ route('businesses.create') }}">Add Another Business</a>
    </div>
</body>
</html>
