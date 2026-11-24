<a class="work-card" data-category-slug="{{ $project->category?->slug ?? '' }}" href="{{ route('project.show',$project->slug) }}" aria-label="View {{ $project->display_title }} portfolio project">
    <figure class="work-card-photo media-frame">
        @if ($project->hero_url)
        <img loading="lazy" decoding="async" src="{{ $project->hero_url }}" alt="{{ $project->display_title }} — The Luxury Look portfolio">
        @else<span class="media-fallback">Explore our portfolio</span>@endif
        <span class="work-card-view">VIEW PROJECT <span aria-hidden="true">↗</span></span>
    </figure>
    <div class="work-card-caption"><div><span class="caption-discipline">{{ $project->category?->title ?? 'Selected Work' }}</span><h3>{{ $project->display_title }}</h3></div><span class="caption-arrow" aria-hidden="true">↗</span></div>
</a>
