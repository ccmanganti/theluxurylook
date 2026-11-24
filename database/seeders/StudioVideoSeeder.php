<?php
namespace Database\Seeders;
use App\Models\StudioVideo;
use Illuminate\Database\Seeder;

class StudioVideoSeeder extends Seeder {
    public function run():void {
        $data=json_decode(file_get_contents(database_path('data/studio-videos.json')),true,512,JSON_THROW_ON_ERROR);
        foreach($data as $item) StudioVideo::firstOrCreate(['youtube_url'=>$item['youtube_url']],$item);
    }
}
