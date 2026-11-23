@extends('layouts.site')
@php
    $copy = config('editorial.categories.'.$category->slug, []);
    $available = $projects->getCollection();
    $spotlight = $available->first();
    $supporting = $available->skip(1)->first();
@endphp
@section('content')
<article class="lux-category">
    <header class="lux-category-hero">
        <div class="lux-category-hero__image media-frame">
            <img src="{{ $category->hero_url }}" fetchpriority="high" alt="{{ $category->title }} - {{ config('site.launch_ready') ? 'project photography' : 'temporary design reference' }}">
        </div>
        <div class="lux-category-hero__panel">
            <p class="lux-overline"><a href="{{ route('portfolio.index') }}">The portfolio</a> <span>/</span> {{ $category->title }}</p>
            <span class="lux-hero-rule"></span>
            <h1>{{ $category->title }}</h1>
            <p class="lux-category-hero__line">{{ $copy['headline'] ?? $category->tagline }}</p>
            <a class="lux-text-link" href="#collection">Discover the collection <span aria-hidden="true">&darr;</span></a>
            <div class="lux-category-hero__foot"><span>Selected work / Scottsdale & beyond</span><span>{{ sprintf('%02d', $projects->total()) }} projects</span></div>
        </div>
    </header>
    <section class="lux-category-intro lux-wrap">
        <div class="lux-vertical-index"><span>01</span><span>THE POINT OF VIEW</span></div>
        <div><p class="lux-overline">{{ $copy['eyebrow'] ?? 'A closer look' }}</p><h2>{{ $copy['promise'] ?? $category->tagline }}</h2></div>
        <div class="lux-category-intro__right"><p>{{ $copy['opening'] ?? $category->description }}</p><a href="{{ route('contact') }}?service={{ urlencode($category->title) }}" class="lux-line-action">Discuss your space <span aria-hidden="true">&nearr;</span></a></div>
    </section>
    <section class="lux-collage-intro lux-wrap" aria-label="Selected photography">
        @if($spotlight)
        <a class="lux-collage-intro__main media-frame" href="{{ route('project.show',$spotlight->slug) }}"><img src="{{ $spotlight->hero_url }}" loading="lazy" alt="{{ $spotlight->title }}"><span class="lux-image-caption"><b>{{ $spotlight->title }}</b> <span>View project &nearr;</span></span></a>
        @endif
        <div class="lux-collage-intro__aside">
            @if($supporting)<a class="media-frame" href="{{ route('project.show',$supporting->slug) }}"><img src="{{ $supporting->hero_url }}" loading="lazy" alt="{{ $supporting->title }}"><span class="lux-image-caption">{{ $supporting->title }}</span></a>@endif
            <div class="lux-collage-intro__note"><span class="lux-overline">Every detail matters</span><p>{{ $copy['headline'] ?? $category->tagline }}</p><span class="lux-aside-number">{{ sprintf('%02d', $projects->total()) }}</span></div>
        </div>
    </section>
    <section id="collection" class="lux-collection lux-wrap">
        <div class="lux-heading-row"><div><p class="lux-overline">02 / Explore the work</p><h2>The collection<span class="lux-dot">.</span></h2></div><p>Take a closer look at the individual spaces, settings and projects within this discipline.</p></div>
        <nav class="lux-category-nav" aria-label="Portfolio disciplines"><a href="{{ route('portfolio.index') }}">All work</a>@foreach($categories as $item)<a href="{{ route('category.show',$item->slug) }}" @if($item->slug === $category->slug) aria-current="page" @endif>{{ $item->title }}</a>@endforeach</nav>
        <div class="lux-editorial-grid">@forelse($projects as $i=>$project)<div class="lux-editorial-grid__item lux-editorial-grid__item--{{ $i % 7 }}">@include('site.partials.project-card',['project'=>$project])</div>@empty<p>Portfolio imagery is being prepared for this collection.</p>@endforelse</div>
        {{ $projects->links() }}
    </section>
    <section class="lux-values lux-wrap"><div><p class="lux-overline">What we consider</p><h2>The details<br><em>change everything.</em></h2></div><div class="lux-values__items">@foreach(($copy['focus'] ?? []) as $i=>$value)<div><span>0{{ $i+1 }}</span><p>{{ $value }}</p></div>@endforeach</div></section>
    <section class="lux-conversion lux-wrap"><span class="lux-overline">A space of your own</span><h2>{{ $copy['cta'] ?? 'Have something in mind?' }}</h2><p>Tell us what you're imagining. We'll talk through your goals and the next steps with the studio.</p><a href="{{ route('contact') }}?service={{ urlencode($category->title) }}" class="lux-btn">Begin a conversation <span>&nearr;</span></a></section>
</article>
@endsection
