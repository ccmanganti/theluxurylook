@extends('layouts.site')
@section('content')
<section class="page-hero page-hero--plain"><div class="container page-hero__inner"><p class="eyebrow">Site Search</p><h1>Find your <em>inspiration.</em></h1><form action="{{ route('search') }}" method="get" class="search-form"><label for="q">Search projects and stories</label><input id="q" name="q" value="{{ $term }}" maxlength="100" placeholder="Try 'Scottsdale' or 'staging'"><button class="pill-link pill-link--dark">Search <span>&rarr;</span></button></form></div></section>
<section class="container section-pad"><div class="project-grid">@foreach($projects as $project)@include('site.partials.project-card',['project'=>$project])@endforeach</div>@foreach($posts as $post)<p><a class="text-link" href="{{ route('post.show',$post->slug) }}">{{ $post->title }} &nearr;</a></p>@endforeach@if($projects->isEmpty() && $posts->isEmpty() && $term)<p>No matching pages yet. Try a broader search.</p>@endif</section>
@endsection
