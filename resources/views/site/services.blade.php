@extends('layouts.site')
@section('content')
<section class="page-hero page-hero--plain"><div class="container page-hero__inner"><p class="eyebrow">What We Do / The Luxury Look</p><h1>Elevating every<br><em>experience.</em></h1><p>From the finest details to complete environments, we design spaces that invite connection and leave lasting impressions.</p></div></section>
<section class="container service-showcase section-pad">@foreach($services as $i=>$service)<article class="service-showcase__row"><div class="service-showcase__number">{{ str_pad((string)($i+1),2,'0',STR_PAD_LEFT) }} / 0{{ $services->count() }}</div><div><h2>{{ $service->title }}</h2><p>{{ $service->summary }}</p><a class="text-link" href="{{ route('service.show',$service->slug) }}">Discover the Service <span>&nearr;</span></a></div><span class="service-showcase__decor" aria-hidden="true">&nearr;</span></article>@endforeach</section>
<section class="mini-cta"><span>LET'S DISCUSS YOUR VISION</span><h2>Exceptional spaces <em>begin together.</em></h2><a class="text-link" href="{{ route('contact') }}">Start Your Project <span>&nearr;</span></a></section>
@endsection
