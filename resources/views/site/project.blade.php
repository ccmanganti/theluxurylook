@extends('layouts.site')
@php
    $photos = $project->gallery_items ?? [];
    $preview = !config('site.launch_ready') && !$project->content_verified;
    $other = $related->first();
@endphp
@section('content')
<article class="lux-case">
    <header class="lux-case-head lux-wrap">
        <p class="lux-overline"><a href="{{ route('portfolio.index') }}">The portfolio</a> <span>/</span> @if($project->category)<a href="{{ route('category.show',$project->category->slug) }}">{{ $project->category->title }}</a>@else Selected work @endif</p>
        <div class="lux-case-head__title"><h1>{{ $project->title }}</h1><div class="lux-case-head__index"><span>PROJECT STUDY</span><span>{{ $project->category?->title ?? 'The Luxury Look' }}</span></div></div>
        <div class="lux-case-head__strap"><p>A closer look at a space from the portfolio.</p><a href="#project-story" class="lux-text-link">Explore this project &darr;</a></div>
    </header>
    <section class="lux-case-hero"><div class="lux-case-hero__image media-frame"><img src="{{ $project->hero_url }}" alt="{{ $preview ? 'Temporary hero image for ' : 'Project photography of ' }}{{ $project->title }}" fetchpriority="high"><div class="lux-case-hero__tag"><span>THE LUXURY LOOK</span><span>{{ $project->title }}</span></div></div><div class="lux-case-hero__rail"><span>DESIGN ARCHIVE / 01</span><span>SCROLL TO EXPLORE &darr;</span></div></section>
    <section class="lux-case-story lux-wrap" id="project-story">
        <aside class="lux-case-story__meta"><span class="lux-overline">The project / At a glance</span><dl><div><dt>Project</dt><dd>{{ $project->title }}</dd></div><div><dt>Discipline</dt><dd>{{ $project->category?->title ?? 'Selected Work' }}</dd></div>@if($project->location)<div><dt>Location</dt><dd>{{ $project->location }}</dd></div>@endif</dl><a href="{{ route('contact') }}?service={{ urlencode($project->category?->title ?? 'Interior Design') }}" class="lux-line-action">Discuss a similar project &nearr;</a></aside>
        <div class="lux-case-story__copy"><p class="lux-overline">01 / Inside the project</p><h2>The story<br><em>behind the space.</em></h2>@if($project->summary)<p class="lux-large-copy">{{ $project->summary }}</p>@endif
            @if($project->story)<div class="lux-richtext">{!! \Illuminate\Support\Str::markdown($project->story,['html_input'=>'strip','allow_unsafe_links'=>false]) !!}</div>@elseif($preview)<p>Original project notes, styling decisions and materials are being confirmed for the full case study. These editorial layouts are ready for the final images and descriptions.</p>@endif
            @if($preview)<p class="lux-notice" role="note">Design-review imagery only. Gallery photos are temporary layout references and are not verified photographs of this property. Replace them in the Filament project editor before launch.</p>@endif
        </div>
    </section>
    @if(count($photos))
    <section class="lux-gallery-section" aria-label="Project gallery"><div class="lux-wrap lux-gallery-heading"><div><p class="lux-overline">02 / The visual story</p><h2>Explore every angle<span class="lux-dot">.</span></h2></div><span>{{ sprintf('%02d', count($photos)) }} PHOTOGRAPHS / REVIEW LAYOUT</span></div>
        <div class="lux-photo-story lux-wrap">
            @foreach($photos as $i=>$photo)
            <figure class="lux-photo-story__item lux-photo-story__item--{{ $i % 8 }} media-frame"><button type="button" class="gallery-open" data-gallery-image="{{ $photo['url'] ?? '' }}" data-gallery-alt="{{ $photo['alt'] ?? $project->title }}" aria-label="Enlarge photograph {{ $i + 1 }}"><img src="{{ $photo['url'] ?? '' }}" alt="{{ $photo['alt'] ?? $project->title }}" loading="lazy" decoding="async"><span class="lux-photo-story__view">View photograph &nearr;</span></button><figcaption><span>{{ sprintf('%02d',$i + 1) }} / {{ sprintf('%02d',count($photos)) }}</span>@if($preview)<span>IMAGE PLACEHOLDER</span>@endif</figcaption></figure>
            @endforeach
        </div>
    </section>
    @endif
    <section class="lux-case-bridge"><div class="lux-wrap lux-case-bridge__inner"><p class="lux-overline">A thoughtful space begins with a conversation</p><h2>What could we create<br><em>for your space?</em></h2><a href="{{ route('contact') }}" class="lux-btn lux-btn--light">Share your vision <span>&nearr;</span></a></div></section>
</article>
@if($related->isNotEmpty())<section class="lux-related lux-wrap"><div class="lux-heading-row"><div><p class="lux-overline">03 / Continue exploring</p><h2>More to discover<span class="lux-dot">.</span></h2></div><a href="{{ route('portfolio.index') }}" class="lux-line-action">View the full portfolio &nearr;</a></div><div class="lux-related__grid">@foreach($related as $item)@include('site.partials.project-card',['project'=>$item])@endforeach</div></section>@endif
<div class="lightbox" role="dialog" aria-modal="true" aria-label="Project gallery photograph" aria-hidden="true" hidden><button class="lightbox-close" type="button" aria-label="Close photograph">&times;</button><button class="lightbox-prev" type="button" aria-label="Previous photograph">&larr;</button><img class="lightbox-image" alt=""><button class="lightbox-next" type="button" aria-label="Next photograph">&rarr;</button><span class="lightbox-count"></span></div>
@endsection
