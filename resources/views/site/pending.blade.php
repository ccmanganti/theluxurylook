@extends('layouts.site')
@section('content')
<section class="page-hero page-hero--plain"><div class="container page-hero__inner"><p class="eyebrow">Website Migration / URL Retained</p><h1>We're preparing <em>this space.</em></h1><p>This existing website address is reserved. Its original content, assets or functionality are being transferred and reviewed.</p><p class="micro-label">{{ $legacy->path }}</p><a class="pill-link pill-link--dark" href="{{ route('contact') }}">Contact The Studio <span>&nearr;</span></a></div></section>
@endsection
