@php
  $video = $video ?? null;
  $videoId = $video?->youtube_id;
  $poster = $video?->poster_url ?: ($videoId ? 'https://i.ytimg.com/vi/'.$videoId.'/hqdefault.jpg' : null);
@endphp
@if($videoId)
<section class="x-film-feature x-wrap" aria-label="Video from The Luxury Look">
 <div class="x-film-feature__copy"><span class="x-eyebrow">AN INSIDE LOOK / THE STUDIO</span><h2>See the work<br><em>in motion.</em></h2><p>Step into the studio story through a film already featured by The Luxury Look. The video opens only when you select play.</p>
 <a class="x-underlink" href="https://www.youtube.com/channel/UCqPsB5QK-XuPWmksTDGoDrQ" target="_blank" rel="noopener noreferrer">Explore our YouTube channel ↗</a></div>
 <div class="x-film-feature__visual"><img src="{{ $poster }}" alt="{{ $video->title }} video preview" loading="lazy" decoding="async" width="1280" height="720">
 <button type="button" class="x-film-cta" data-youtube-url="{{ $video->youtube_url }}" data-video-title="{{ $video->title }}" aria-label="Play video: {{ $video->title }}"><span class="x-play-icon">▶</span> Watch the film</button></div>
</section>
@endif
