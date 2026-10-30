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
{{-- Loader: inlined so it shows before the stylesheet, web fonts, and scripts arrive --}}
<style>
.loader{--l-paper:#FBF5F1;--l-ink:#2A1520;--l-1:#7C0E44;--l-2:#AA5E7A;--l-3:#D49A9A;display:none}
@media (prefers-color-scheme:dark){.loader{--l-paper:#1C1016;--l-ink:#F4E4DC;--l-1:#D49A9A;--l-2:#AA5E7A;--l-3:#7C0E44}}
.preload body{overflow:hidden}
.preload .loader{position:fixed;inset:0;z-index:100;display:grid;place-content:center;justify-items:center;gap:22px;background:var(--l-paper);transition:opacity .6s ease,visibility .6s}
.loader-out .loader{opacity:0;visibility:hidden}
.loader-lamp,.loader-name{opacity:0;animation:l-in .4s ease .2s forwards}
.loader-lamp{position:relative;width:104px;height:104px;filter:url(#loader-goo)}
.loader-lamp i{position:absolute;left:50%;top:50%;width:36px;height:36px;margin:-18px;border-radius:50%;background:var(--l-1)}
.loader-lamp i:nth-child(2){background:var(--l-2);width:28px;height:28px;margin:-14px}
.loader-lamp i:nth-child(3){background:var(--l-3);width:24px;height:24px;margin:-12px}
.loader-name{margin:0;color:var(--l-ink);font:300 1.5rem/1 "Fraunces","Iowan Old Style",Georgia,serif;letter-spacing:.02em}
@media (prefers-reduced-motion:no-preference){
  .loader-lamp i:nth-child(1){animation:l-swell 2.4s ease-in-out infinite}
  .loader-lamp i:nth-child(2){animation:l-orbit 2.4s cubic-bezier(.45,0,.55,1) infinite}
  .loader-lamp i:nth-child(3){animation:l-orbit 3.2s cubic-bezier(.45,0,.55,1) -1.1s infinite reverse}
}
@keyframes l-in{to{opacity:1}}
@keyframes l-swell{50%{transform:scale(1.25)}}
@keyframes l-orbit{0%{transform:rotate(0) translateX(34px)}50%{transform:rotate(180deg) translateX(8px)}100%{transform:rotate(360deg) translateX(34px)}}
</style>
<script>
document.documentElement.classList.add('preload','loading');
(function(){
  var done=false, root=document.documentElement;
  function finish(){
    if(done) return; done=true;
    root.classList.remove('loading'); root.classList.add('loader-out');
    setTimeout(function(){root.classList.remove('preload','loader-out'); var el=document.getElementById('loader'); if(el) el.remove()},650);
  }
  window.addEventListener('load',function(){(document.fonts?document.fonts.ready:Promise.resolve()).then(finish,finish)});
  setTimeout(finish,8000);
})();
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
@php($styles = ['https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300..600&family=Manrope:wght@400..700&display=swap', asset('css/portfolio.css').'?v='.filemtime(public_path('css/portfolio.css'))])
{{-- Stylesheets load without blocking the first paint, so the loader shows while they download --}}
@foreach ($styles as $href)
<link rel="stylesheet" href="{{ $href }}" media="print" onload="this.media='all'">
@endforeach
<noscript>@foreach ($styles as $href)<link rel="stylesheet" href="{{ $href }}">@endforeach</noscript>
</head>
<body class="no-js">
<div class="loader" id="loader" role="status" aria-label="Loading">
  <svg width="0" height="0" aria-hidden="true" focusable="false" style="position:absolute"><filter id="loader-goo"><feGaussianBlur in="SourceGraphic" stdDeviation="7"/><feColorMatrix values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 22 -10"/></filter></svg>
  <div class="loader-lamp" aria-hidden="true"><i></i><i></i><i></i></div>
  <p class="loader-name" aria-hidden="true">{{ $name }}</p>
</div>
<a class="skip" href="#main">Skip to content</a>

<header class="site-header">
  <nav class="nav wrap" aria-label="Main">
    <a class="brand" href="#top">{{ $name }}</a>
    <button class="menu-btn" type="button" aria-expanded="false" aria-controls="nav-links">Menu</button>
    <div class="nav-links" id="nav-links">
      <a href="#work">Work</a>
      <a href="#services">Services</a>
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
