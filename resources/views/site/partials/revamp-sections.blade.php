@if(!empty($page) && is_array($page->sections) && count($page->sections))
<section class="r8-cms-sections x-wrap x-section" aria-label="More from The Luxury Look">
@foreach($page->sections as $index=>$section)
@php
  $heading=$section['heading']??'';
  $image=$section['image_url']??null;
  $type=$section['type']??'editorial';
  $cta=$section['cta_url']??'';
  $allowedCta=is_string($cta) && (str_starts_with($cta,'/')&&!str_starts_with($cta,'//') || str_starts_with($cta,'https://'));
@endphp
<article class="r8-cms-card r8-cms-card--{{ $type }}">
 <div class="r8-cms-copy"><span class="x-eyebrow">{{ $section['eyebrow']??sprintf('EDITORIAL / %02d',$index+1) }}</span>
 <h2>{{ $heading }}</h2><p>{{ $section['body']??'' }}</p>
 @if($allowedCta && !empty($section['cta_text']))<a class="x-underlink" href="{{ $cta }}">{{ $section['cta_text'] }} ↗</a>@endif
 </div>
 @if($image)<div class="r8-cms-image"><img src="{{ $image }}" width="980" height="750" loading="lazy" decoding="async" alt="{{ $heading }}"></div>@endif
</article>
@endforeach
</section>
@endif
