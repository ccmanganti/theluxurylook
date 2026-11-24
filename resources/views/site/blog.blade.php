@extends('layouts.site')
@section('content')
@php
 $categories=$posts->getCollection()->pluck('category')->filter()->unique()->sort()->values();
@endphp
<header class="x-portfolio-lead x-wrap journal-heading"><div><span class="x-eyebrow">THE LUXURY LOOK / THE JOURNAL</span><h1>Notes on the<br><em>art of living well.</em></h1><p>Interiors, real-estate perspective, considered staging, travel, and the small details that shape how we live.</p><a href="{{ route('portfolio.index') }}" class="x-underlink">Explore our work ↗</a></div><div class="x-portfolio-cover x-media x-media--portrait"><img src="{{ $posts->first()?->hero_url ?: config('editorial.pages.about.image') }}" alt="The Luxury Look Journal source photography" width="920" height="1180" loading="eager"><span>THE JOURNAL / ORIGINAL STORIES</span></div></header>
<section class="journal-explore x-wrap x-section" id="stories"><div class="x-heading"><div><span class="x-eyebrow">THE EDITORIAL ARCHIVE</span><h2>Explore the<br><em>Journal.</em></h2></div><p>Original stories about homes and the places that inspire them.</p></div>
<div class="journal-tools"><label for="journal-search">Search articles</label><input id="journal-search" type="search" data-journal-search-input placeholder="Search design, staging, travel..." autocomplete="off">
<div class="journal-filters" role="group" aria-label="Filter Journal articles"><button type="button" data-journal-category="All" aria-pressed="true" class="is-active">All</button>@foreach($categories as $category)<button type="button" data-journal-category="{{ $category }}" aria-pressed="false">{{ $category }}</button>@endforeach</div></div>
<div class="journal-list">
@forelse($posts as $post)
<a class="journal-entry" href="{{ route('post.show',$post->slug) }}" data-journal-entry data-journal-category-value="{{ $post->category ?: 'Design' }}" data-journal-search="{{ strtolower($post->title.' '.$post->excerpt) }}"><div class="journal-entry-image"><img src="{{ $post->hero_url ?: config('editorial.pages.about.image') }}" width="900" height="620" loading="lazy" decoding="async" alt="Journal article photography for {{ $post->title }}"></div><div class="journal-entry-caption"><span>{{ $post->category ?: 'The Journal' }} @if($post->published_at) / {{ $post->published_at->format('M Y') }} @endif</span><h3>{{ $post->title }}</h3><p>{{ $post->excerpt }}</p><b>Read the article ↗</b></div></a>
@empty<p>Journal articles are being reviewed for publication.</p>@endforelse
</div><p class="journal-empty" data-journal-empty hidden role="status">No articles match those terms. Try another topic.</p>{{ $posts->links() }}</section>
<section class="r8-quote-strip"><div class="x-wrap"><span class="x-eyebrow">THE LUXURY LOOK STUDIO</span><h2>Inspired by an idea?<br><em>Make it your own.</em></h2><a class="x-pill x-pill--light" href="{{ route('contact') }}">Begin a conversation ↗</a></div></section>
@endsection
