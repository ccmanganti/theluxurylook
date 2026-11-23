@extends('layouts.site')
@section('content')
<header class="page-editorial-header container-wide"><p class="section-kicker">THE LUXURY LOOK JOURNAL</p><h1>{{ $post->title }}<span class="period">.</span></h1>@if($post->excerpt)<p>{{ $post->excerpt }}</p>@endif</header>
<section class="page-editorial-body section-space container-wide"><span class="section-kicker">NOTES FROM THE STUDIO</span><div class="prose">@if($post->body){!! \Illuminate\Support\Str::markdown($post->body,['html_input'=>'strip','allow_unsafe_links'=>false]) !!}@elseif(!config('site.launch_ready'))<p>The original article body is being transferred from the existing website. This draft is not indexed.</p>@endif<a href="{{ route('blog') }}" class="under-link">Back to journal ↗</a></div></section>
@endsection
