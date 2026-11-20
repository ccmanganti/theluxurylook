@extends('layouts.site')
@section('content')
<section class="page-hero page-hero--plain"><div class="container page-hero__inner"><p class="eyebrow">{{ $page->eyebrow ?: 'The Luxury Look' }}</p><h1>{{ $page->title }}</h1>@if($page->intro)<p>{{ $page->intro }}</p>@endif</div></section>
<section class="container page-editorial section-pad"><div><p class="eyebrow">A Thoughtful Approach</p><div class="line-accent"></div></div><div class="prose">{!! \Illuminate\Support\Str::markdown($page->body ?? '', ['html_input'=>'strip','allow_unsafe_links'=>false]) !!}<a class="pill-link pill-link--dark" href="{{ route('contact') }}">Start a Conversation <span>&nearr;</span></a></div></section>
@endsection
