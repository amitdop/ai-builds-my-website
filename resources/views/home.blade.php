<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Builds My Website</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #172033;
        }

        nav {
            padding: 25px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
            color: #4f46e5;
        }

        .hero {
            min-height: 80vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
        }

        .tag {
            background: #e0e7ff;
            color: #4338ca;
            padding: 10px 18px;
            border-radius: 30px;
            margin-bottom: 25px;
            font-size: 14px;
        }

        h1 {
            font-size: clamp(36px, 6vw, 62px);
            max-width: 850px;
            line-height: 1.15;
            margin-bottom: 25px;
        }

        h1 span {
            color: #4f46e5;
        }

        .description {
            max-width: 650px;
            color: #64748b;
            font-size: 18px;
            line-height: 1.7;
            margin-bottom: 35px;
        }

        .button {
            display: inline-block;
            background: #4f46e5;
            color: white;
            padding: 16px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.2s;
        }

        .button:hover {
            background: #3730a3;
        }

        footer {
            text-align: center;
            padding: 25px;
            color: #94a3b8;
        }
    </style>
</head>

<body>

    <nav>
        <div class="logo">AI Builds My Website</div>
    </nav>

    <main class="hero">
        <div class="tag">AI-Powered Website Generator</div>

        <h1>
            Your Business Deserves
            <span>A Great Website.</span>
        </h1>

        <p class="description">
            Create a professional website for your business using AI.
            Share your business details and let our platform help you
            build your online presence.
        </p>

        <a href="/create" class="button">
            Build My Website →
        </a>
    </main>

    <footer>
        © 2026 AI Builds My Website. All rights reserved.
    </footer>

</body>
</html>
