@extends('layouts.site')
@section('content')
<section class="page-hero page-hero--plain"><div class="container page-hero__inner"><p class="eyebrow">Thoughts & Inspiration</p><h1>Our <em>Journal.</em></h1><p>Ideas, perspective, and inspiration from the world of interiors and meaningful spaces.</p></div></section>
<section class="container section-pad"><div class="post-grid">@foreach($posts as $post)<a class="post-card" href="{{ route('post.show',$post->slug) }}"><figure>@if($post->hero_url)<img loading="lazy" src="{{ $post->hero_url }}" alt="{{ $post->title }}">@else<div class="post-blank"><span>THE LUXURY LOOK<br>JOURNAL</span></div>@endif</figure><span class="eyebrow">Journal @if($post->published_at)/ {{ $post->published_at->format('M Y') }}@endif</span><h2>{{ $post->title }}</h2><span class="text-link">Read the Story <span>&nearr;</span></span></a>@endforeach</div><div class="pagination">{{ $posts->links() }}</div></section>
@endsection
