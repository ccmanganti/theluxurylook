@extends('layouts.site')
@section('content')
<header class="page-editorial-header container-wide"><p class="section-kicker">{{ strtoupper($page->eyebrow ?: 'THE LUXURY LOOK') }}</p><h1>{{ $page->title }}<span class="period">.</span></h1>@if($page->intro)<p>{{ $page->intro }}</p>@endif</header>
<section class="page-editorial-body section-space container-wide"><span class="section-kicker">DESIGN STUDIO / SCOTTSDALE</span><div><div class="prose">{!! \Illuminate\Support\Str::markdown($page->body ?? '', ['html_input'=>'strip','allow_unsafe_links'=>false]) !!}</div><a class="under-link" href="{{ route('contact') }}">Get in touch ↗</a></div></section>
@endsection
