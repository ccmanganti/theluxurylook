@extends('layouts.site')
@section('content')
@php
 $wordCount=str_word_count(strip_tags((string)$post->body));
 $readMinutes=max(1,(int)round($wordCount/210));
@endphp
<article class="journal-article"><header class="journal-article-header x-wrap"><span class="x-eyebrow">THE LUXURY LOOK / THE JOURNAL / {{ $post->category ?: 'EDITORIAL' }}</span><h1>{{ $post->title }}</h1><p>{{ $post->excerpt }}</p><div class="journal-article-meta"><span>{{ $readMinutes }} MIN READ</span>@if($post->published_at)<span>{{ $post->published_at->format('F j, Y') }}</span>@endif<a href="{{ route('blog') }}">All Journal stories ↗</a></div></header>
@if($post->hero_url)<figure class="journal-article-hero"><img src="{{ $post->hero_url }}" width="1600" height="850" fetchpriority="high" decoding="async" alt="Original editorial photograph for {{ $post->title }}"><figcaption>THE LUXURY LOOK / JOURNAL</figcaption></figure>@endif
<div class="journal-article-layout x-wrap" id="story"><aside><span class="x-eyebrow">IN THIS ARTICLE</span><nav data-article-toc aria-label="Article sections"></nav>
<button type="button" class="journal-share" data-copy-article-link>Copy article link <span aria-hidden="true">↗</span></button>
@if($post->source_attribution)<p>Original contribution: {{ $post->source_attribution }}</p>@endif

</aside><div class="journal-article-body" data-article-body>
@if($post->body){!! \Illuminate\Support\Str::markdown($post->body,['html_input'=>'strip','allow_unsafe_links'=>false]) !!}@else<p>Explore more ideas and stories in The Luxury Look Journal.</p>@endif
</div></div>
<section class="journal-related x-wrap x-section"><span class="x-eyebrow">THE NEXT CHAPTER</span><h2>Continue the<br><em>conversation.</em></h2><div><a href="{{ route('blog') }}">Read more from the Journal ↗</a><a href="{{ route('design') }}">Explore Interior Design ↗</a><a href="{{ route('contact') }}">Discuss Your Project ↗</a></div></section></article><div class="journal-read-progress" data-reading-progress aria-hidden="true"></div>
@endsection
