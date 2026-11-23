<?php
namespace Database\Seeders;
use App\Models\{PortfolioCategory,Project,Service};
use Illuminate\Database\Seeder;
class PreviewPhotographySeeder extends Seeder {
 public function run(): void {
  $data=json_decode(file_get_contents(database_path('data/portfolio-seed.json')),true,512,JSON_THROW_ON_ERROR);
  $catalog=json_decode(file_get_contents(database_path('data/preview-image-catalog.json')),true,512,JSON_THROW_ON_ERROR);
  $groups=[];
  foreach($data['projects'] as $project) $groups[$project['category_slug']?:'interior-design-projects'][]=$project;
  foreach($data['categories'] as $entry) {
   $cat=PortfolioCategory::where('slug',$entry['slug'])->first();
   if($cat && !$cat->content_verified && !$cat->preview_hero_image){$cat->preview_hero_image=$entry['hero_image'];$cat->save();}
  }
  foreach($data['projects'] as $item){
   $p=Project::where('slug',$item['slug'])->first(); if(!$p || $p->content_verified)continue;
   if(!$p->preview_cover_image)$p->preview_cover_image=$item['cover_image'];
   if($p->preview_gallery===null){
    $group=$groups[$item['category_slug']?:'interior-design-projects'];
    $index=array_search($item['slug'],array_column($group,'slug'),true);$index=$index===false?0:$index;
    $stock=$catalog['stock'][$item['category_slug']?:'interior-design-projects']??$catalog['stock']['interior-design-projects'];
    $others=array_values(array_filter(array_column($group,'cover_image'),fn($u)=>$u!==$item['cover_image']));
    $takeStock=fn($n)=>$stock[($index*2+$n)%count($stock)];
    $takeNeighbor=fn($n)=>count($others)?$others[($index+$n)%count($others)]:$takeStock($n);
    $photos=[$takeStock(0),$takeNeighbor(1),$takeStock(1),$takeStock(2),$takeNeighbor(3),$takeStock(3),$takeNeighbor(5),$takeStock(4)];
    $p->preview_gallery=array_map(fn($url,$i)=>['url'=>$url,'alt'=>'Temporary layout image '.($i+1).' for '.$item['preview_title']],$photos,array_keys($photos));
   }
   $p->save();
  }
  $map=['interior-design'=>'interior-design-projects','interior-styling'=>'interior-styling-projects-the-luxury-look','luxury-residential-staging'=>'home-staging-projects-the-luxury-look','commercial-staging'=>'commercial-staging-projects-the-luxury-look','airbnb-staging'=>'airbnb-staging-the-luxury-look','corporate-branding'=>'corporate-brandding-projects-the-luxury-look'];
  $bySlug=array_column($data['categories'],null,'slug');
  foreach($map as $slug=>$catSlug){$s=Service::where('slug',$slug)->first();if($s && !$s->content_verified && !$s->preview_cover_image){$s->preview_cover_image=$bySlug[$catSlug]['hero_image'];$s->save();}}
 }
}
