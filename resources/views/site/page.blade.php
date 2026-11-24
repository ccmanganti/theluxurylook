@extends('layouts.site')
@php
    $slug = trim($page->path, '/');
    $pictures = config('editorial.pages.'.$slug, config('editorial.pages.fallback', []));
    $hero = $page->hero_image && \Illuminate\Support\Str::startsWith($page->hero_image, ['https://','http://']) ? $page->hero_image : ($page->hero_image ? \Illuminate\Support\Facades\Storage::disk('public')->url($page->hero_image) : ($pictures['image'] ?? ''));
@endphp
@section('content')
@if($slug === 'about')
 @include('site.partials.founder-about',['founder'=>$founder??null,'founderFilms'=>$founderFilms??collect()])
@elseif($slug === 'the-studio')
<article class="lux-studio"><header class="lux-studio-hero"><div class="lux-studio-hero__photo media-frame"><img src="{{ $hero }}" fetchpriority="high" alt="The Luxury Look Scottsdale design studio"><span>THE LUXURY LOOK / STUDIO</span></div><div class="lux-studio-hero__copy"><p class="lux-overline">Old Town Scottsdale / By appointment</p><h1>A place to<br><em>begin.</em></h1><p>{{ $page->intro }}</p><a href="{{ route('contact') }}" class="lux-btn">Arrange a conversation <span>&nearr;</span></a></div></header><section class="lux-wrap lux-studio-visit"><div><p class="lux-overline">01 / Visit the studio</p><h2>Ideas begin<br><em>in conversation.</em></h2></div><div><p>Our Scottsdale design studio is a place to explore ideas, talk through materials, and consider what your property could become. Appointments ensure we can give your project the attention it deserves.</p><address>4343 N Scottsdale Road, Suite 150<br>Scottsdale, AZ 85251</address><span>VISITS BY APPOINTMENT ONLY</span><a class="lux-line-action" href="mailto:kristine@theluxurylook.com">Contact the studio &nearr;</a></div></section><section class="lux-studio-double lux-wrap"><div class="media-frame"><img src="{{ $pictures['detail'] ?? $hero }}" loading="lazy" alt="Interior styling details"></div><div><span class="lux-overline">02 / What happens next</span><h2>Bring the questions.<br><em>We'll bring the perspective.</em></h2><p>Share photos, plans, timelines or simply a sense of what isn't working. We'll talk through the kind of design or staging support that makes sense.</p><a href="{{ route('services') }}" class="lux-line-action">Explore the services &nearr;</a></div></section></article>
@elseif($slug === 'awards')
<article class="lux-recognition"><header class="lux-recognition-hero"><div><p class="lux-overline">Recognition / The Luxury Look</p><h1>Work that<br><em>speaks for itself.</em></h1><p>The Luxury Look has received recognition for design and home staging. Explore the work behind the studio, and the perspective that continues to shape it.</p><a class="lux-btn" href="{{ route('portfolio.index') }}">Explore selected projects &nearr;</a></div><div class="media-frame"><img src="{{ $hero }}" fetchpriority="high" alt="Interior design portfolio photograph"></div></header><div class="lux-recognition-note lux-wrap"><span class="lux-overline">Press & Awards</span><div><h2>Recognition is part of the story.<br><em>The work is the proof.</em></h2><p>Our recognition reflects a longstanding commitment to distinctive spaces, client relationships and design excellence.</p><a href="{{ route('portfolio.index') }}" class="lux-line-action">See the portfolio &nearr;</a></div></div></article>
@else
<article class="lux-generic"><header class="lux-generic-hero"><div><p class="lux-overline">{{ $page->eyebrow ?: 'The Luxury Look' }}</p><h1>{{ $page->title }}</h1><p>{{ $page->intro }}</p><a href="#details" class="lux-text-link">Explore the details &darr;</a></div><div class="media-frame"><img src="{{ $hero }}" fetchpriority="high" alt="Interior design inspiration for {{ $page->title }}"></div></header><section id="details" class="lux-generic-details lux-wrap"><div><span class="lux-overline">01 / The details</span><h2>A closer look.</h2></div><div class="lux-richtext">{!! \Illuminate\Support\Str::markdown($page->body ?? '',['html_input'=>'strip','allow_unsafe_links'=>false]) !!}<a href="{{ route('contact') }}" class="lux-line-action">Ask the studio &nearr;</a></div></section></article>
@endif
@if(count($page->sections??[]))
<section class="x-page-modules x-wrap x-section" aria-label="More about this page">
 @foreach($page->sections as $i=>$section)
  @php $kind=$section['type']??'editorial'; @endphp
  <article class="x-page-module x-page-module--{{ $kind }}">
   <div><span class="x-eyebrow">{{ sprintf('%02d',$i+1) }} / {{ $section['eyebrow']??'THE LUXURY LOOK' }}</span><h2>{{ $section['heading']??'' }}</h2>
     @if(!empty($section['body']))<div class="x-prose">{{ $section['body'] }}</div>@endif
     @if(!empty($section['cta_text']) && !empty($section['cta_url']))<a class="x-underlink" href="{{ $section['cta_url'] }}">{{ $section['cta_text'] }} ↗</a>@endif</div>
   @if(!empty($section['image_url']) && empty($section['youtube_url']))<div class="x-media x-media--landscape"><img loading="lazy" width="960" height="720" src="{{ $section['image_url'] }}" alt="{{ $section['heading']??'Editorial photograph' }}"></div>@endif
   @php $inlineInfo=\App\Models\StudioVideo::sourceInfo($section['youtube_url']??null); @endphp
   @if($inlineInfo)
      <div class="x-film-feature__visual"><img loading="lazy" width="1280" height="720" src="{{ $section['image_url']??(($inlineInfo['provider']==='youtube'?'https://i.ytimg.com/vi/'.$inlineInfo['id'].'/hqdefault.jpg':asset('media/atelier-motion-poster.jpg'))) }}" alt="Video poster for {{ $section['heading']??'The Luxury Look' }}">
       <button type="button" class="x-film-cta" data-video-url="{{ $section['youtube_url'] }}" data-video-title="{{ $section['heading']??'The Luxury Look video' }}"><span class="x-play-icon">▶</span> Watch film</button></div>
   @endif
  </article>
 @endforeach
</section>
@endif
@php $heroVideoInfo=\App\Models\StudioVideo::sourceInfo($page->youtube_url); @endphp
@if($heroVideoInfo)
<section class="x-film-feature x-wrap" aria-label="Page video"><div class="x-film-feature__copy"><span class="x-eyebrow">THE LUXURY LOOK / ON FILM</span><h2>A closer look<br><em>at the work.</em></h2><p>Choose play to open this studio video.</p></div><div class="x-film-feature__visual"><img src="{{ $page->hero_video_poster ?: (($heroVideoInfo['provider']==='youtube'?'https://i.ytimg.com/vi/'.$heroVideoInfo['id'].'/hqdefault.jpg':asset('media/atelier-motion-poster.jpg'))) }}" width="1280" height="720" loading="lazy" alt="Video cover"><button type="button" class="x-film-cta" data-video-url="{{ $page->youtube_url }}" data-video-title="{{ $page->title }}"><span class="x-play-icon">▶</span> Play film</button></div></section>
@endif
<section class="lux-conversion lux-wrap"><p class="lux-overline">Every great project begins somewhere</p><h2>Let's talk about yours.</h2><p>Whether you have a clear plan or an idea in progress, we'd love to understand the space you're considering.</p><a class="lux-btn" href="{{ route('contact') }}">Make an inquiry <span>&nearr;</span></a></section>
@endsection
