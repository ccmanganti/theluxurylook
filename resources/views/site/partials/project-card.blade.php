<a class="project-card" href="{{ route('project.show',$project->slug) }}" aria-label="View {{ $project->title }} project">
    <figure class="project-card__image">
        @if ($project->hero_url)<img loading="lazy" src="{{ $project->hero_url }}" alt="{{ $project->title }} - The Luxury Look {{ $project->category?->title ?? 'portfolio' }} project">@else<span class="photo-placeholder">THE LUXURY LOOK</span>@endif
        <span class="project-card__overlay">Explore project <span aria-hidden="true">&nearr;</span></span>
    </figure>
    <div class="project-card__meta"><div><span class="micro-label">{{ $project->category?->title ?? 'Selected Work' }}</span><h3>{{ $project->title }}</h3></div><span class="project-card__arrow" aria-hidden="true">&nearr;</span></div>
</a>
