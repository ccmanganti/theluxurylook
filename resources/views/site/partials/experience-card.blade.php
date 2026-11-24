@php
  $ratio = $ratio ?? 'landscape';
  $label = $project->display_title;
@endphp
<a class="x-project-card x-project-card--{{ $ratio }}" data-category-slug="{{ $project->category?->slug ?? '' }}" href="{{ route('project.show',$project->slug) }}" aria-label="Explore {{ $label }}">
 <figure class="x-project-media x-media x-media--{{ $ratio }}">
  @if($project->hero_url)<img src="{{ $project->hero_url }}" loading="lazy" decoding="async" width="960" height="720" sizes="(max-width:720px) 100vw,(max-width:1100px) 50vw, 36vw" alt="{{ config('site.launch_ready') ? 'Portfolio photography: ' : 'Design inspiration: ' }}{{ $label }}">@else<div class="x-missing">Explore This Project</div>@endif
  <span class="x-project-open">VIEW CASE STUDY ↗</span>
 </figure>
 <div class="x-project-meta"><div><span>{{ $project->category?->title ?? 'Selected Work' }}</span><h3>{{ $label }}</h3></div><span aria-hidden="true">↗</span></div>
</a>
