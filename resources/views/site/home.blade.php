@extends('layouts.site')
@section('content')
@php
$features = $projects->take(8);
$hero = $projects->first()?->hero_url ?? ($categories->first()?->hero_url ?? '');
$secondary = $categories->skip(2)->first()?->hero_url ?? $hero;
@endphp
<section class="home-hero container-wide">
    <div class="home-hero-copy">
        <p class="section-kicker">THE LUXURY LOOK <span>/</span> SCOTTSDALE, AZ</p>
        <h1>Designed<br>to <span>be lived in.</span></h1>
        <p class="home-hero-description">Interior design, home staging, and thoughtful styling for the spaces where life happens.</p>
        <div class="home-hero-actions"><a href="{{ route('portfolio.index') }}" class="button-ink">Explore Our Portfolio <span aria-hidden="true">↗</span></a><a href="{{ route('services') }}" class="under-link">Our Services <span aria-hidden="true">↗</span></a></div>
        <span class="hero-edge-note">EST. 2013 &nbsp; • &nbsp; FOUNDED BY KRISTINE VOWLES</span>
    </div>
    <div class="home-hero-visual media-frame"><img fetchpriority="high" src="{{ $hero }}" alt="{{ config('site.launch_ready') ? 'Featured project by The Luxury Look' : 'Temporary interior photograph for layout review' }}"><div class="hero-image-index"><span>FEATURED WORK</span><span>01 / 0{{ max(1,min($projects->count(),9)) }}</span></div></div>
</section>
<section class="intro-editorial section-space container-wide">
    <div class="intro-editorial-index"><p class="section-kicker">01 / THE STUDIO</p><div class="intro-rule"></div><span>DESIGN / STAGING / STYLING</span></div>
    <div class="intro-editorial-content"><h2>Spaces with a<br><em>point of view.</em></h2><div class="editorial-subrow"><p>Founded by Kristine Vowles, The Luxury Look works across residential design, home staging and commercial spaces. We bring experience in real estate and design together, with equal attention to how a space looks and how it works.</p><a class="under-link" href="{{ url('/about') }}">About the Studio ↗</a></div></div>
</section>
<section class="homepage-work section-space section-tint">
    <div class="container-wide"><div class="section-header"><div><p class="section-kicker">02 / SELECTED WORK</p><h2>Inside the portfolio.</h2></div><a class="under-link" href="{{ route('portfolio.index') }}">View all projects <span aria-hidden="true">↗</span></a></div>
        <div class="work-grid work-grid--home">@foreach($features as $project)@include('site.partials.project-card',['project'=>$project])@endforeach</div>
        <div class="section-end"><span>{{ $categories->sum('projects_count') ?: '54' }} PROJECTS ACROSS SIX DISCIPLINES</span><a class="link-with-arrow" href="{{ route('portfolio.index') }}">COMPLETE PORTFOLIO <span>↗</span></a></div>
    </div>
</section>
<section class="image-interlude"><div class="image-interlude-photo media-frame"><img loading="lazy" src="{{ $secondary }}" alt="Interior styling sample photography"></div><div class="image-interlude-copy"><p class="section-kicker section-kicker--light">A CLOSER LOOK</p><h2>Every space<br>has its own<br><em>character.</em></h2><p>Explore our work across interiors, residential and commercial staging, short-term rentals, and brand environments.</p><a class="link-light" href="{{ route('portfolio.index') }}">EXPLORE PROJECTS <span>↗</span></a></div></section>
<section class="discipline-section section-space container-wide"><div class="section-header"><div><p class="section-kicker">03 / DISCIPLINES</p><h2>What we work on.</h2></div><p class="header-description">Explore the portfolio by project type, from whole-home transformations to individual spaces and experiences.</p></div><div class="discipline-list">@foreach($categories as $i=>$category)<a href="{{ route('category.show',$category->slug) }}" class="discipline-row"><span class="discipline-number">{{ str_pad((string)($i+1),2,'0',STR_PAD_LEFT) }}</span><div class="discipline-thumb media-frame"><img loading="lazy" src="{{ $category->hero_url }}" alt="{{ $category->title }} sample project imagery"></div><div class="discipline-name"><h3>{{ $category->title }}</h3><span>{{ $category->tagline }}</span></div><span class="discipline-count">{{ str_pad((string)$category->projects_count,2,'0',STR_PAD_LEFT) }} PROJECTS</span><span class="discipline-arrow">↗</span></a>@endforeach</div></section>
<section class="process-section section-space"><div class="container-wide"><div class="section-header"><div><p class="section-kicker">04 / WORKING TOGETHER</p><h2>From the first conversation.</h2></div></div><div class="process-grid"><div><span>01</span><h3>Tell us about the space</h3><p>Share the property, your goals, and how you plan to use it.</p></div><div><span>02</span><h3>Find the right service</h3><p>We'll discuss whether your project calls for design, styling, staging, or a combination.</p></div><div><span>03</span><h3>Plan what comes next</h3><p>Connect with our studio to discuss scope, timing, and the next steps.</p></div></div><a href="{{ route('contact') }}" class="button-ink">Start a Project <span aria-hidden="true">↗</span></a></div></section>
@endsection
