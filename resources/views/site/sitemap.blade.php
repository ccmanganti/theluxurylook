@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($paths as $path)
  <url><loc>{{ 'https://'.config('site.canonical_host').($path==='/'?'/':$path) }}</loc></url>
@endforeach
</urlset>
