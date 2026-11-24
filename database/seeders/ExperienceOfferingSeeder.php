<?php
namespace Database\Seeders;
use App\Models\ExperienceOffering;
use Illuminate\Database\Seeder;
class ExperienceOfferingSeeder extends Seeder {
    public function run():void {
        $items=json_decode(file_get_contents(database_path('data/experience-offerings.json')),true,512,JSON_THROW_ON_ERROR);
        foreach($items as $item){$item['content_verified']=false;$item['is_published']=true;ExperienceOffering::firstOrCreate(['kind'=>$item['kind'],'slug'=>$item['slug']],$item);}
    }
}
