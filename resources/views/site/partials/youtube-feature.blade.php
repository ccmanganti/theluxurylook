@php
  $video = $video ?? null;
  $url = $video?->playable_url;
  $info = \App\Models\StudioVideo::sourceInfo($url);
  $poster = $video?->poster_url ?: ($info && $info['provider']==='youtube' ? 'https://i.ytimg.com/vi/'.$info['id'].'/hqdefault.jpg' : asset('media/atelier-motion-poster.jpg'));
@endphp
@if($info)
<section class="x-film-feature x-wrap" aria-label="Featured studio film">
 <div class="x-film-feature__copy"><span class="x-eyebrow">AN INSIDE LOOK / THE LUXURY LOOK</span><h2>See the vision<br><em>in motion.</em></h2>
 <p>{{ $video->caption ?: 'Explore the studio story and the care behind each design decision.' }} Explore the studio's creative point of view through film.</p>
 <span class="x-film-provider">FEATURED FILM &nbsp;/&nbsp; THE LUXURY LOOK</span></div>
 <div class="x-film-feature__visual"><img src="{{ $poster }}" alt="{{ $video->title }} poster" loading="lazy" decoding="async" width="1280" height="720">
 <button type="button" class="x-film-cta" data-video-url="{{ $url }}" data-video-title="{{ $video->title }}" aria-label="Play {{ $video->title }}"><span class="x-play-icon">▶</span> Watch the latest film</button></div>
</section>
@endif
