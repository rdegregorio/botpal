<!DOCTYPE html>
<html lang="en" class="landing-page">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'aisupport.bot - AI-Powered Chatbots for Your Business')</title>
    <meta name="description" content="Create personalized AI chatbots for customer support. Harness the power of AI technology to transform your business communications.">
    <meta name="keywords" content="AI Chatbot, OpenAI, Business Chatbots, Customer Support, E-commerce Assistance, Personalized Chatbot, AI Technology">
    <meta property="og:title" content="aisupport.bot - AI-Powered Chatbots">
    <meta property="og:description" content="Create personalized AI chatbots for your business using cutting-edge AI technology.">
    <meta property="og:type" content="website">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link href="/landing.css" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

    <style>
        html, body {
            background: #ffffff !important;
            border-top: none !important;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
    </style>
</head>
<body>
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
