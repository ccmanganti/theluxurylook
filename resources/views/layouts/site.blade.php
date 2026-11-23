<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f6f4f0">
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
    @if (config('site.google_site_verification'))
        <meta name="google-site-verification" content="{{ config('site.google_site_verification') }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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
    <a href="#main" class="skip-link">Skip to content</a>
    @if (!config('site.launch_ready'))
    <div class="preview-banner">Migration preview &mdash; search indexing disabled &mdash; production content is still being verified</div>
    @endif
    <header class="site-header" id="site-header">
        <a class="wordmark" href="{{ route('home') }}" aria-label="The Luxury Look home">
            <span class="wordmark__the">THE</span><span class="wordmark__main">LUXURY LOOK</span><span class="wordmark__tag">INTERIOR DESIGN &amp; STAGING</span>
        </a>
        <button class="nav-toggle" type="button" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation"><span></span><span></span></button>
        <nav class="main-nav" id="main-nav" aria-label="Primary navigation">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ url('/portfolio/interior-design-projects') }}">Portfolio</a>
            <a href="{{ route('services') }}">Services</a>
            <a href="{{ url('/about') }}">Our Studio</a>
            <a href="{{ route('blog') }}">Journal</a>
            <a class="nav-contact" href="{{ route('contact') }}">Start a Project <span aria-hidden="true">&nearr;</span></a>
        </nav>
    </header>
    <main id="main">@yield('content')</main>
    <footer class="site-footer">
        <div class="footer-intro">
            <p class="eyebrow eyebrow--light">We'd love to hear your ideas</p>
            <h2>Let's create something<br><em>extraordinary.</em></h2>
            <a href="{{ route('contact') }}" class="pill-link pill-link--light">Start a conversation <span>&nearr;</span></a>
        </div>
        <div class="footer-grid">
            <div><span class="footer-label">The Studio</span><p>4343 N Scottsdale Rd.<br>Suite 150<br>Scottsdale, AZ 85251</p><small>Visits by appointment only</small></div>
            <div><span class="footer-label">Connect</span><a href="tel:+16025786707">+1 (602) 578-6707</a><a href="mailto:kristine@theluxurylook.com">kristine@theluxurylook.com</a></div>
            <div><span class="footer-label">Explore</span><a href="{{ route('services') }}">Our Services</a><a href="{{ url('/portfolio/interior-design-projects') }}">The Portfolio</a><a href="{{ url('/awards') }}">Press &amp; Recognition</a></div>
            <div><span class="footer-label">Follow</span><a href="https://www.instagram.com/theluxurylook_/" rel="noopener noreferrer" target="_blank">Instagram &nearr;</a><a href="https://www.facebook.com/TheLuxuryLookLLC" rel="noopener noreferrer" target="_blank">Facebook &nearr;</a></div>
        </div>
        <div class="footer-bottom"><span>&copy; {{ date('Y') }} The Luxury Look. All rights reserved.</span><span>Intentional spaces. Lasting impressions.</span></div>
    </footer>
</body>
</html>
