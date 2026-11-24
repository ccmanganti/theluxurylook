<?php
namespace Database\Seeders;
use App\Models\FounderProfile;
use Illuminate\Database\Seeder;
class FounderProfileSeeder extends Seeder {
    public function run():void {
        $source=json_decode(file_get_contents(database_path('data/founder-profile.json')),true,512,JSON_THROW_ON_ERROR);
        FounderProfile::firstOrCreate(['name'=>'Kristine Vowles'],$source);
    }
}
