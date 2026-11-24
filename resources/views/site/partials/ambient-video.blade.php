@php
  // No Ken Burns video: the previous six-second clip itself zoomed and caused vibration.
  // Fade only between stationary still images. Production uses approved media only.
  $preview = !config('site.launch_ready');
  $posterSrc = $posterSrc ?? ($preview ? asset('media/atelier-motion-poster.jpg') : null);
  $fadeImages = $fadeImages ?? ($preview ? [
    'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1500&q=72',
    'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1500&q=72',
  ] : []);
@endphp
<div class="x-ambient x-ambient--still-fade" data-ambient-container>
  @if($posterSrc)<img class="x-ambient-poster" src="{{ $posterSrc }}" width="960" height="661" decoding="async" loading="eager" alt="" aria-hidden="true">@endif
  @foreach(array_slice($fadeImages,0,2) as $i=>$source)
    <img class="x-ambient-fade x-ambient-fade--{{ $i+1 }}" src="{{ $source }}" width="1500" height="1000" decoding="async" loading="lazy" alt="" aria-hidden="true">
  @endforeach
  <div class="x-ambient-tone" aria-hidden="true"></div>
</div>
