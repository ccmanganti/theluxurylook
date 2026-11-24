<!doctype html>
<html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="theme-color" content="#eceae5">
<title>{{ $seo['title'] ?? 'The Luxury Look | Interior Design Studio' }}</title>
<meta name="description" content="{{ $seo['description'] ?? 'Distinctive interiors, curated sourcing and design expertise.' }}">
<link rel="canonical" href="{{ 'https://'.config('site.canonical_host').(($seo['path'] ?? '/')==='/' ? '/' : $seo['path']) }}">
@if(!config('site.launch_ready') || ($seo['noindex'] ?? false))<meta name="robots" content="noindex,nofollow,noarchive">@else<meta name="robots" content="index,follow,max-image-preview:large">@endif
<meta property="og:site_name" content="The Luxury Look">
<meta property="og:title" content="{{ $seo['title'] ?? 'The Luxury Look' }}">
<meta property="og:description" content="{{ $seo['description'] ?? '' }}">
<meta property="og:url" content="{{ 'https://'.config('site.canonical_host').(($seo['path'] ?? '/')==='/' ? '/' : $seo['path']) }}">
<meta property="og:type" content="website">@if(!empty($seo['image']))<meta property="og:image" content="{{ $seo['image'] }}">@endif
<meta name="twitter:card" content="summary_large_image">
@if(config('site.google_site_verification'))<meta name="google-site-verification" content="{{ config('site.google_site_verification') }}">@endif
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/site.css') }}"><link rel="stylesheet" href="{{ asset('assets/editorial.css') }}"><link rel="stylesheet" href="{{ asset('assets/revamp.css') }}"><link rel="stylesheet" href="{{ asset('assets/experience.css') }}"><link rel="stylesheet" href="{{ asset('assets/media-stable.css') }}?v=20261124-films-v4">
<script defer src="{{ asset('assets/site.js') }}"></script><script defer src="{{ asset('assets/revamp.js') }}"></script><script defer src="{{ asset('assets/experience.js') }}"></script><script defer src="{{ asset('assets/media-stable.js') }}?v=20261124-films-v4"></script>
@if(config('site.ga4_id') && config('site.launch_ready'))<script async src="https://www.googletagmanager.com/gtag/js?id={{ config('site.ga4_id') }}"></script><script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config',@json(config('site.ga4_id')));</script>@endif
@if(($seo['path']??'')==='/')<script type="application/ld+json">{!! json_encode(['@context'=>'https://schema.org','@type'=>'InteriorDesignBusiness','name'=>'The Luxury Look','url'=>'https://'.config('site.canonical_host'),'telephone'=>'+1 (602) 578-6707','email'=>'kristine@theluxurylook.com','address'=>['@type'=>'PostalAddress','streetAddress'=>'4343 N Scottsdale Rd Suite 150','addressLocality'=>'Scottsdale','addressRegion'=>'AZ','postalCode'=>'85251','addressCountry'=>'US']],JSON_UNESCAPED_SLASHES|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!}</script>@endif
</head>
<body class="x-site"><a class="skip-link" href="#main">Skip to content</a>
@unless(config('site.launch_ready'))<div class="preview-ribbon" role="note">PRIVATE DESIGN REVIEW <span>/</span> TEMPORARY IMAGES &amp; VIDEO <span>/</span> NOT INDEXED</div>@endunless
<header class="x-header masthead" id="top"><div class="x-topline x-wrap"><span>THE LUXURY LOOK &nbsp; / &nbsp; INTERIOR DESIGN HOUSE</span><a href="{{ route('contact') }}">PROJECT INQUIRIES <span aria-hidden="true">↗</span></a></div>
 <div class="x-nav-shell x-wrap"><a class="x-wordmark" href="{{ route('home') }}" aria-label="The Luxury Look home"><span>THE</span><b>LUXURY LOOK</b><small>DESIGN HOUSE</small></a>
 <button class="nav-toggle x-menu-toggle" type="button" aria-label="Open navigation" aria-controls="primary-nav" aria-expanded="false"><span></span><span></span></button>
 <nav id="primary-nav" class="primary-nav x-nav" aria-label="Primary navigation">
  <a href="{{ route('design') }}">Design</a><a href="{{ route('portfolio.index') }}">Portfolio</a><a href="{{ route('builders') }}">For Builders</a><a href="{{ route('buying') }}">Buying Trips</a><a href="{{ route('education') }}">Education</a><a href="{{ route('collection') }}">Collection</a><a href="{{ route('blog') }}">Journal</a>
  <details class="x-more"><summary>More</summary><div class="x-more-panel"><a href="{{ url('/about') }}">The Studio</a><a href="{{ route('circle') }}">The Curated Circle</a><a href="{{ url('/awards') }}">Recognition</a></div></details>
  <a class="x-nav-cta" href="{{ route('contact') }}">Begin Your Project <span aria-hidden="true">↗</span></a>
 </nav></div></header>
<main id="main">@yield('content')</main>
<footer class="x-footer"><div class="x-footer-top x-wrap"><span class="x-eyebrow">A NEW CHAPTER STARTS HERE</span><div class="x-footer-hero"><h2>Ready for a home that<br><em>feels like yours?</em></h2><a class="x-pill x-pill--light" href="{{ route('contact') }}">Begin Your Project <span>↗</span></a></div></div><div class="x-footer-bottom x-wrap"><div><a class="x-footer-logo" href="{{ route('home') }}">THE LUXURY LOOK</a><p>Design with intention. Furnished for living.</p></div><nav aria-label="Footer"><a href="{{ route('portfolio.index') }}">Portfolio</a><a href="{{ route('buying') }}">Buying Trips</a><a href="{{ route('education') }}">Education</a><a href="{{ route('contact') }}">Work with us</a></nav><div><p>4343 N Scottsdale Rd, Suite 150<br>Scottsdale, AZ 85251</p><a href="mailto:kristine@theluxurylook.com">kristine@theluxurylook.com</a><a href="tel:+16025786707">+1 (602) 578-6707</a></div></div><div class="x-footer-legal x-wrap"><small>© {{ date('Y') }} The Luxury Look</small><small>Photography and moving imagery in the staging preview are illustrative until approved</small><a href="#top">Back to top ↑</a></div></footer>
</body></html>
