<?php
namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class PreviewTitlesSeeder extends Seeder
{
    public function run(): void
    {
        $source=json_decode(file_get_contents(database_path('data/portfolio-seed.json')), true, 512, JSON_THROW_ON_ERROR);
        foreach($source['projects'] as $item) {
            // Never overwrite an editor's working title or a verified case study.
            Project::query()->where('slug', $item['slug'])
                ->where('content_verified', false)
                ->where(function ($query) { $query->whereNull('preview_title')->orWhere('preview_title',''); })
                ->update(['preview_title'=>$item['preview_title']]);
        }
    }
}
