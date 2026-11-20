<?php
use App\Models\LegacyUrl;
use Illuminate\Support\Facades\Artisan;

Artisan::command('migration:audit', function () {
    $expected=json_decode(file_get_contents(database_path('data/legacy-sitemap.json')),true);
    $all=array_column($expected,'path');
    $records=LegacyUrl::whereIn('path',$all)->get()->keyBy('path');
    $pending=[];
    foreach ($all as $path) {
        if (!isset($records[$path]) || !in_array($records[$path]->status,['ready','redirect','retired'],true)) $pending[]=$path;
    }
    $this->info('Original URLs: '.count($all));
    $this->info('Accounted for: '.(count($all)-count($pending)));
    $this->warn('Pending review: '.count($pending));
    foreach ($pending as $path) $this->line(' - '.$path);
    if (count($pending)) {
        $this->error('Not ready to switch DNS or publish the new production sitemap.');
        return 1;
    }
    $this->info('URL audit complete. Manually verify content, media, links, SEO and commerce before cutover.');
    return 0;
})->purpose('Audit exact Webflow legacy URL mapping and launch readiness');
