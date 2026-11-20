@extends('layouts.site')
@section('content')
<section class="page-hero page-hero--plain"><div class="container page-hero__inner"><p class="eyebrow">Our Expertise / {{ $service->title }}</p><h1>{{ $service->title }}</h1><p>{{ $service->summary }}</p></div></section>
<section class="container page-editorial section-pad"><div><p class="eyebrow">The Luxury Look Approach</p></div><div class="prose">{!! \Illuminate\Support\Str::markdown($service->body ?? '', ['html_input'=>'strip','allow_unsafe_links'=>false]) !!}<a class="pill-link pill-link--dark" href="{{ route('contact') }}">Let's Work Together <span>&nearr;</span></a></div></section>
@endsection
