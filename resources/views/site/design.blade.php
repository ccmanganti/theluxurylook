@extends('layouts.site')
@section('content')
@php $hero=$projects->first()?->hero_url; @endphp
<article class="r8-page r8-design">
<header class="r8-design-hero x-wrap">
 <div class="r8-design-copy"><span class="x-eyebrow">THE LUXURY LOOK / INTERIOR DESIGN HOUSE</span><h1>More than a beautiful home.<br><em>A way of living.</em></h1><p>{{ $page?->intro ?: 'Full-service interior design for primary residences, second homes, and carefully considered renovations.' }}</p>
 <div class="r8-actions"><a href="{{ route('contact') }}?service=Custom%20Home%20Design" class="x-pill">Begin Your Project <span>↗</span></a><a href="#design-journey" class="x-underlink">Discover our approach ↓</a></div></div>
 <div class="r8-design-image"><img src="{{ $hero ?: 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1400&q=80' }}" width="920" height="1160" fetchpriority="high" alt="Interior design portfolio photograph — The Luxury Look"><span>EVERY DETAIL IN ITS PLACE</span></div>
</header>
<section class="r8-manifesto x-wrap x-section"><span class="x-eyebrow">01 / OUR POINT OF VIEW</span><div><h2>Built around<br><em>the way you live.</em></h2><p>A meaningful home reflects more than a style. It responds to how you gather, retreat, work and welcome others. Kristine's approach brings together the planning, materials, furnishings and details that make a space personal.</p></div></section>
<section class="r8-process x-section" id="design-journey"><div class="x-wrap"><div class="r8-section-heading"><span class="x-eyebrow">02 / THE DESIGN EXPERIENCE</span><h2>From the first thought<br><em>to the final detail.</em></h2></div><div class="r8-process-grid">
@foreach([
 ['Discovery','We listen to your goals, how the home will be lived in, and the priorities that matter most.'],
 ['Direction','A cohesive approach brings spatial planning, material choices and furnishings into focus.'],
 ['Design & coordination','We discuss drawings, specifications, collaborators and timelines appropriate to the agreed project scope.'],
 ['Finishing touches','Furniture, textiles, lighting, art and considered details bring the home together.']
] as $i=>$step)
<article><span>{{ sprintf('%02d',$i+1) }}</span><h3>{{ $step[0] }}</h3><p>{{ $step[1] }}</p></article>
@endforeach</div><p class="r8-small">The final workflow and deliverables are confirmed in each project proposal.</p></div></section>
@if($projects->isNotEmpty())<section class="x-wrap x-section r8-work-preview"><div class="r8-section-heading"><span class="x-eyebrow">03 / SELECTED RESIDENCES</span><h2>A closer look<br><em>at the work.</em></h2></div><div class="r8-work-grid">@foreach($projects as $project)@include('site.partials.experience-card',['project'=>$project,'ratio'=>'portrait'])@endforeach</div><a href="{{ route('portfolio.index') }}?category=interior-design-projects" class="x-underlink">Explore all interior design projects ↗</a></section>@endif
<section class="r8-quote-strip"><div class="x-wrap"><span class="x-eyebrow">WORK DIRECTLY WITH THE STUDIO</span><h2>A home should be as individual<br><em>as the life unfolding within it.</em></h2><a class="x-pill x-pill--light" href="{{ route('contact') }}?service=Custom%20Home%20Design">Tell Us About Your Home <span>↗</span></a></div></section>
@include('site.partials.revamp-sections')
</article>
@endsection
