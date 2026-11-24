@php $films = $filmCollection ?? collect(); @endphp
@if($films->isNotEmpty())
<section class="x-film-library x-section" aria-label="More films from The Luxury Look">
  <div class="x-wrap"><header class="x-film-library__heading"><div><span class="x-eyebrow">FROM THE FILM ARCHIVE</span><h2>The studio,<br><em>on film.</em></h2></div><p>Discover a closer look at selected spaces, staging projects, and conversations with Kristine. Choose any film to watch.</p></header>
    <div class="x-film-library__grid">
     @foreach($films as $film)
       @php $source=\App\Models\StudioVideo::sourceInfo($film->playable_url); @endphp
       @if($source)
         <article class="x-film-library__card"><div class="x-film-library__image"><img src="{{ $film->poster_url ?: ('https://i.ytimg.com/vi/'.$source['id'].'/hqdefault.jpg') }}" alt="{{ $film->title }} video cover" width="960" height="540" loading="lazy" decoding="async">
           <button class="x-film-cta" type="button" data-video-url="{{ $film->playable_url }}" data-video-title="{{ $film->title }}" aria-label="Play {{ $film->title }}"><span class="x-play-icon">▶</span> Play film</button></div>
           <div class="x-film-library__meta"><span>{{ strtoupper($source['provider']) }} / {{ sprintf('%02d',$loop->iteration) }}</span><h3>{{ $film->title }}</h3><p>{{ $film->caption }}</p></div>
         </article>
       @endif
     @endforeach
    </div>
  </div>
</section>
@endif
