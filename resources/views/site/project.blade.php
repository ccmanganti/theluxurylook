@extends('layouts.site')
@section('content')
<section class="project-hero"><div class="project-hero__photo">@if($project->hero_url)<img src="{{ $project->hero_url }}" alt="{{ $project->title }} by The Luxury Look" fetchpriority="high">@endif</div><div class="project-hero__shade"></div><div class="container project-hero__title"><p class="eyebrow eyebrow--light">@if($project->category)<a href="{{ url('/portfolio/'.$project->category->slug) }}">{{ $project->category->title }}</a>@else Portfolio @endif / Selected Work</p><h1>{{ $project->title }}</h1></div></section>
<section class="project-intro section-pad container"><div><span class="eyebrow">A Luxury Look Project</span><p class="micro-label">{{ $project->location ?: 'Arizona and beyond' }}</p></div><div><h2>Designed to <em>inspire.</em></h2><p>{{ $project->summary }}</p>@if($project->story)<div class="prose">{!! \Illuminate\Support\Str::markdown($project->story,['html_input'=>'strip','allow_unsafe_links'=>false]) !!}</div>@endif</div></section>
@if(count($project->gallery_items))
<section class="gallery-section section-pad container"><p class="eyebrow">A Closer Look</p><h2>From every <em>angle.</em></h2><div class="gallery-grid">@foreach($project->gallery_items as $photo)<figure><img loading="lazy" src="{{ $photo['url'] ?? '' }}" alt="{{ $photo['alt'] ?? $project->title }}"></figure>@endforeach</div></section>
@elseif(!config('site.launch_ready'))
<section class="container"><p class="migration-note">The full gallery and case study for this legacy URL are pending photography migration. This note is visible only in the staging preview.</p></section>
@endif
@if($related->isNotEmpty())<section class="related-section section-pad container"><div class="section-heading"><div><p class="eyebrow">Keep Exploring</p><h2>More <em>stories.</em></h2></div></div><div class="project-grid">@foreach($related as $item)@include('site.partials.project-card',['project'=>$item])@endforeach</div></section>@endif
<section class="mini-cta"><span>READY TO BEGIN?</span><h2>Your space has a <em>story to tell.</em></h2><a class="text-link" href="{{ route('contact') }}">Discuss Your Project <span>&nearr;</span></a></section>
@endsection
