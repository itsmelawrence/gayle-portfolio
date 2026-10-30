<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $name }}’s Portfolio | Digital Marketing &amp; Operations Specialist</title>
<meta name="description" content="{{ $name }} is a digital marketing and operations specialist: Meta ads, creative production, social media, funnels, GoHighLevel CRM, and marketing automation.">
<meta property="og:title" content="{{ $name }} | Digital Marketing &amp; Operations Specialist">
<meta property="og:description" content="Ads, creative, and the systems behind them.">
<meta property="og:type" content="website">
<meta name="theme-color" content="#7C0E44">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300..600&family=Manrope:wght@400..700&display=swap">
<link rel="stylesheet" href="{{ asset('css/portfolio.css') }}?v={{ filemtime(public_path('css/portfolio.css')) }}">
</head>
<body class="no-js">
<a class="skip" href="#main">Skip to content</a>

<header class="site-header">
  <nav class="nav wrap" aria-label="Main">
    <a class="brand" href="#top">{{ $name }}</a>
    <button class="menu-btn" type="button" aria-expanded="false" aria-controls="nav-links">Menu</button>
    <div class="nav-links" id="nav-links">
      <a href="#services">Services</a>
      <a href="#work">Work</a>
      <a href="#experience">Experience</a>
      <a href="#about">About</a>
      <a class="btn btn-primary" href="#contact">Let’s work together</a>
    </div>
  </nav>
</header>

<main id="main">
@yield('content')
</main>

<footer class="site-footer">
  <div class="wrap">
    <p>&copy; {{ date('Y') }} {{ $name }}. Digital marketing &amp; operations.</p>
    <a href="#top">Back to top</a>
  </div>
</footer>

<script src="{{ asset('js/portfolio.js') }}?v={{ filemtime(public_path('js/portfolio.js')) }}"></script>
</body>
</html>
