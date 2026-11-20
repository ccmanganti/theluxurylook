@extends('layouts.site')
@section('content')
<section class="hero hero--home">
    <div class="hero__visual"><img src="https://cdn.prod.website-files.com/61900fb94bdf48d160f4ffef/6930cdb203e0e2dbea07f32a_MOCKINGBIRD%20PARADISE%20VALLEY_6.png" alt="Luxury residential interior - The Luxury Look design portfolio" fetchpriority="high"></div>
    <div class="hero__veil"></div>
    <div class="hero__content container">
        <p class="eyebrow eyebrow--light">THE ART OF TRANSFORMING SPACE &nbsp; / &nbsp; SCOTTSDALE, AZ</p>
        <h1>Curated living.<br><em>Beautifully</em> imagined.</h1>
        <p class="hero__lead">Distinguished interiors and thoughtfully styled spaces that reflect the people who live, work, and gather within them.</p>
        <div class="hero__actions"><a href="{{ url('/portfolio/interior-design-projects') }}" class="pill-link pill-link--light">Discover Our Work <span>&nearr;</span></a><a href="{{ route('services') }}" class="subtle-link">Explore Services <span>&rarr;</span></a></div>
    </div>
    <div class="hero__bottom container"><span>DESIGN WITH INTENTION.</span><span>SCROLL TO EXPLORE &darr;</span></div>
</section>

<section class="intro-section section-pad container">
    <div class="intro-section__side"><span class="eyebrow">01 / Our Philosophy</span><div class="line-accent"></div></div>
    <div class="intro-section__main"><h2>We believe a space should be <em>experienced,</em> not just seen.</h2><div class="intro-section__bottom"><p>From luxurious residences to evocative commercial spaces, The Luxury Look brings design expertise, thoughtful collaboration, and an unmistakable attention to detail.</p><a class="text-link" href="{{ url('/about') }}">Meet The Studio <span>&nearr;</span></a></div></div>
</section>

<section class="featured-section section-pad">
    <div class="container"><div class="section-heading"><div><span class="eyebrow">02 / The Portfolio</span><h2>Spaces with <em>a story.</em></h2></div><a class="text-link" href="{{ url('/portfolio/interior-design-projects') }}">Explore the Portfolio <span>&nearr;</span></a></div>
    <div class="project-grid">
    @forelse ($projects as $project)
        @include('site.partials.project-card',['project'=>$project])
    @empty
        <p>Our selected portfolio is being curated. Explore our categories below.</p>
    @endforelse
    </div>
    </div>
</section>

<section class="statements-section">
    <div class="statement-image"><img loading="lazy" src="https://cdn.prod.website-files.com/61900fb94bdf48d160f4ffef/6930cd2e6e621902d0d5006f_OLD%20TOWN%20SCOTTSDALE%20AIRBNB_24.jpg" alt="Curated Airbnb interior by The Luxury Look"></div>
    <div class="statement-copy"><span class="eyebrow eyebrow--light">03 / Our Expertise</span><h2>Considered design.<br><em>Extraordinary</em><br>outcomes.</h2><p>We create complete environments by looking beyond appearances. Every material, proportion, and detail contributes to a seamless experience.</p><a href="{{ route('services') }}" class="pill-link pill-link--light">What We Do <span>&nearr;</span></a></div>
</section>

<section class="services-section section-pad container"><div class="section-heading"><div><span class="eyebrow">04 / Services</span><h2>What we <em>do best.</em></h2></div><p class="section-subtitle">An integrated approach to beauty, function, and the feeling of being at home.</p></div>
    <div class="services-list">
    @foreach ($services as $i=>$service)
        <a href="{{ route('service.show',$service->slug) }}"><span class="service-num">{{ str_pad((string)($i+1),2,'0',STR_PAD_LEFT) }}</span><span class="service-title">{{ $service->title }}</span><span class="service-desc">{{ $service->summary }}</span><span class="service-arrow">&nearr;</span></a>
    @endforeach
    </div>
</section>

<section class="category-section section-pad"><div class="container"><div class="section-heading"><div><span class="eyebrow">05 / Explore by Specialty</span><h2>One studio.<br><em>Many perspectives.</em></h2></div></div><div class="category-grid">
    @foreach ($categories as $category)
        <a class="category-tile" href="{{ route('category.show',$category->slug) }}"><img src="{{ $category->hero_image }}" alt="{{ $category->title }} projects" loading="lazy"><span class="category-tile__scrim"></span><span class="category-tile__content"><span class="micro-label">The Portfolio</span><strong>{{ $category->title }}</strong><small>Explore <span>&nearr;</span></small></span></a>
    @endforeach
    </div></div></section>

<section class="about-callout section-pad container"><p class="eyebrow">The Founder</p><div><h2>Rooted in expertise.<br><em>Driven by passion.</em></h2><p>Led by founder and CEO Kristine Vowles, The Luxury Look blends decades of experience with a distinctive eye for possibilities.</p><a class="text-link" href="{{ url('/about') }}">Get to Know Us <span>&nearr;</span></a></div></section>
@endsection
