@extends('layouts.site')
@section('content')
<article><header class="page-hero page-hero--plain post-header"><div class="container page-hero__inner"><p class="eyebrow">The Luxury Look Journal @if($post->published_at) / {{ $post->published_at->format('F j, Y') }}@endif</p><h1>{{ $post->title }}</h1>@if($post->excerpt)<p>{{ $post->excerpt }}</p>@endif</div></header>
@if($post->hero_url)<div class="article-hero container"><img src="{{ $post->hero_url }}" alt="{{ $post->title }}"></div>@endif
<div class="article-body container prose">@if($post->body){!! \Illuminate\Support\Str::markdown($post->body, ['html_input'=>'strip','allow_unsafe_links'=>false]) !!}@elseif(!config('site.launch_ready'))<p class="migration-note">Full original article content is pending migration. This staging preview is not indexable.</p>@endif</div></article>
<section class="mini-cta"><span>INSPIRED TO BEGIN?</span><h2>Let's imagine <em>what's possible.</em></h2><a class="text-link" href="{{ route('contact') }}">Work With Us <span>&nearr;</span></a></section>
@endsection
