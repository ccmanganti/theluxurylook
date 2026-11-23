<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f3f0ea">
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    <link rel="canonical" href="{{ 'https://'.config('site.canonical_host').($seo['path'] === '/' ? '/' : $seo['path']) }}">
    @if (!config('site.launch_ready') || ($seo['noindex'] ?? false))
        <meta name="robots" content="noindex, nofollow, noarchive">
    @else
        <meta name="robots" content="index, follow, max-image-preview:large">
    @endif
    <meta property="og:site_name" content="The Luxury Look">
    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:description" content="{{ $seo['description'] }}">
    <meta property="og:url" content="{{ 'https://'.config('site.canonical_host').($seo['path'] === '/' ? '/' : $seo['path']) }}">
    <meta property="og:type" content="website">
    @if (!empty($seo['image']))<meta property="og:image" content="{{ $seo['image'] }}">@endif
    <meta name="twitter:card" content="summary_large_image">
    @if (config('site.google_site_verification'))<meta name="google-site-verification" content="{{ config('site.google_site_verification') }}">@endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Cormorant+Garamond:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/site.css') }}">
    <script defer src="{{ asset('assets/site.js') }}"></script>
    @if (config('site.ga4_id') && config('site.launch_ready'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('site.ga4_id') }}"></script>
        <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag('js',new Date());gtag('config',@json(config('site.ga4_id')));</script>
    @endif
    @if (request()->is('/'))
        <script type="application/ld+json">{!! json_encode(['@context'=>'https://schema.org','@type'=>'InteriorDesignBusiness','name'=>'The Luxury Look','url'=>'https://'.config('site.canonical_host'),'telephone'=>'+1 (602) 578-6707','email'=>'kristine@theluxurylook.com','address'=>['@type'=>'PostalAddress','streetAddress'=>'4343 N Scottsdale Rd Suite 150','addressLocality'=>'Scottsdale','addressRegion'=>'AZ','postalCode'=>'85251','addressCountry'=>'US']],JSON_UNESCAPED_SLASHES|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!}</script>
    @endif
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>
    @unless (config('site.launch_ready'))
        <div class="preview-ribbon" role="note">DESIGN REVIEW <span aria-hidden="true">/</span> IMAGE SETS ARE TEMPORARY &amp; WILL BE REPLACED <span aria-hidden="true">/</span> NOT INDEXED</div>
    @endunless
    <header class="masthead" id="top">
        <div class="masthead-top container-wide"><span>SCOTTSDALE, ARIZONA <i aria-hidden="true"></i> INTERIOR DESIGN &amp; STAGING</span><a href="{{ route('contact') }}">INQUIRE ABOUT A PROJECT <span aria-hidden="true">↗</span></a></div>
        <div class="masthead-bottom container-wide">
            <a href="{{ route('home') }}" class="brand" aria-label="The Luxury Look Home"><span class="brand-overline">THE</span><span class="brand-name">LUXURY LOOK</span><span class="brand-subline">DESIGN STUDIO</span></a>
            <button type="button" class="nav-toggle" aria-label="Open navigation" aria-expanded="false" aria-controls="primary-nav"><span></span><span></span></button>
            <nav id="primary-nav" class="primary-nav" aria-label="Main navigation">
                <a href="{{ route('portfolio.index') }}" @if(request()->is('portfolio*')) aria-current="page" @endif>Portfolio</a>
                <a href="{{ route('services') }}" @if(request()->is('services*')) aria-current="page" @endif>Services</a>
                <a href="{{ url('/about') }}">Studio</a>
                <a href="{{ route('blog') }}">Journal</a>
                <a class="nav-outline" href="{{ route('contact') }}">Let's Talk <span aria-hidden="true">↗</span></a>
            </nav>
        </div>
    </header>
    <main id="main">@yield('content')</main>
    <footer class="footer-main">
        <div class="container-wide footer-top"><div><p class="section-kicker section-kicker--light">THE NEXT PROJECT</p><h2>Let's talk about<br><span>your space.</span></h2></div><a href="{{ route('contact') }}" class="footer-action">SEND AN INQUIRY <span aria-hidden="true">↗</span></a></div>
        <div class="container-wide footer-columns">
            <div class="footer-identity"><span class="brand-overline">THE</span><strong>LUXURY LOOK</strong><small>DESIGN STUDIO</small><p>Interior design, home staging and property styling based in Scottsdale, Arizona.</p></div>
            <div><h3>Explore</h3><a href="{{ route('portfolio.index') }}">All Projects</a><a href="{{ route('services') }}">Services</a><a href="{{ url('/about') }}">Our Studio</a><a href="{{ route('blog') }}">Journal</a></div>
            <div><h3>Visit</h3><p>4343 N Scottsdale Road<br>Suite 150<br>Scottsdale, AZ 85251</p><small>By appointment only</small></div>
            <div><h3>Get in touch</h3><a href="mailto:kristine@theluxurylook.com">kristine@theluxurylook.com</a><a href="tel:+16025786707">+1 (602) 578-6707</a><div class="footer-social"><a href="https://www.instagram.com/theluxurylook_/" target="_blank" rel="noopener noreferrer">Instagram ↗</a><a href="https://www.facebook.com/TheLuxuryLookLLC" target="_blank" rel="noopener noreferrer">Facebook ↗</a></div></div>
        </div>
        <div class="container-wide footer-bottom"><small>© {{ date('Y') }} THE LUXURY LOOK</small><small>DESIGN &amp; STAGING — SCOTTSDALE, AZ</small><a href="#top">BACK TO TOP ↑</a></div>
    </footer>
</body>
</html>
