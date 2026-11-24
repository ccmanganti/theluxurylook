@php
  $isPreview = !config('site.launch_ready');
  $videoSrc = $videoSrc ?? ($isPreview ? asset('media/atelier-motion.mp4') : null);
  $posterSrc = $posterSrc ?? ($isPreview ? asset('media/atelier-motion-poster.jpg') : null);
@endphp
<div class="x-ambient" data-ambient-container>
 @if($posterSrc)<img class="x-ambient-poster" src="{{ $posterSrc }}" width="960" height="660" alt="" decoding="async" loading="lazy">@endif
 @if($videoSrc && $posterSrc)<video class="x-ambient-video" data-ambient-video muted loop playsinline preload="none" poster="{{ $posterSrc }}" tabindex="-1" aria-hidden="true"><source data-src="{{ $videoSrc }}" type="video/mp4"></video>@endif
</div>
