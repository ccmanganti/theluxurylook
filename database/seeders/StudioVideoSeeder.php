<?php
namespace Database\Seeders;

use App\Models\StudioVideo;
use Illuminate\Database\Seeder;

class StudioVideoSeeder extends Seeder {
    public function run(): void {
        $data=json_decode(file_get_contents(database_path('data/studio-videos.json')),true,512,JSON_THROW_ON_ERROR);
        // Archive the original default player only if it has not been rearranged in Filament.
        StudioVideo::where('youtube_url','https://www.youtube.com/watch?v=UuUu8FOKULQ')
            ->where('sort_order',1)->update(['placement'=>'archive','is_published'=>false,'sort_order'=>99]);
        foreach ($data as $item) {
            if (!StudioVideo::sourceInfo($item['video_url'])) continue;
            StudioVideo::firstOrCreate(['video_url'=>$item['video_url']],$item);
        }
    }
}
