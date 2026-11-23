@extends('layouts.site')
@section('content')
<section class="archive-heading container-wide"><div class="archive-heading-left"><p class="section-kicker">IDEAS / STORIES / DESIGN</p><h1>The journal<span class="period">.</span></h1></div><div class="archive-heading-right"><p>Notes and ideas on interiors, styling, home staging and the spaces we inhabit.</p></div></section>
<section class="container-wide section-space"><div class="journal-grid">@foreach($posts as $post)<a href="{{ route('post.show',$post->slug) }}" class="journal-card"><span>THE LUXURY LOOK JOURNAL @if($post->published_at) / {{ $post->published_at->format('M Y') }}@endif</span><h2>{{ $post->title }}</h2><p>{{ $post->excerpt }}</p><span class="under-link">Read article ↗</span></a>@endforeach</div><div class="pagination">{{ $posts->links() }}</div></section>
@endsection
