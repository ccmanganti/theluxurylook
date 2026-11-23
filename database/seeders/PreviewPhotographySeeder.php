<?php
namespace Database\Seeders;

use App\Models\{PortfolioCategory,Project,Service};
use Illuminate\Database\Seeder;

/**
 * Staging-only image samples. Updates only empty preview fields, and will
 * never modify approved photographs or a record marked content_verified.
 * The same indexed selection is used by scripts/build_static_preview.py.
 */
class PreviewPhotographySeeder extends Seeder
{
    public function run(): void
    {
        $portfolio=json_decode(file_get_contents(database_path('data/portfolio-seed.json')),true,512,JSON_THROW_ON_ERROR);
        $catalog=json_decode(file_get_contents(database_path('data/preview-image-catalog.json')),true,512,JSON_THROW_ON_ERROR);
        $byCategory=[];
        foreach($portfolio['projects'] as $project) {
            $category=$project['category_slug'] ?: 'interior-design-projects';
            $byCategory[$category][]=$project;
        }
        foreach($portfolio['categories'] as $item) {
            $category=PortfolioCategory::where('slug',$item['slug'])->first();
            if($category && !$category->content_verified && !$category->preview_hero_image) {
                $category->preview_hero_image=$item['hero_image'];$category->save();
            }
        }
        foreach($portfolio['projects'] as $item) {
            $project=Project::where('slug',$item['slug'])->first();
            if(!$project || $project->content_verified) continue;
            if(!$project->preview_cover_image) $project->preview_cover_image=$item['cover_image'];
            if($project->preview_gallery===null) {
                $category=$item['category_slug'] ?: 'interior-design-projects';
                $group=$byCategory[$category] ?? [$item];
                $index=array_search($item['slug'],array_column($group,'slug'),true);
                $index=$index===false ? 0 : $index;
                $stock=$catalog['stock'][$category] ?? $catalog['stock']['interior-design-projects'];
                $others=array_values(array_filter(array_column($group,'cover_image'),fn($url)=>$url!==$item['cover_image']));
                $takeStock=fn($n)=>$stock[($index*2+$n)%count($stock)];
                $takeNeighbor=fn($n)=>count($others) ? $others[($index+$n)%count($others)] : $takeStock($n);
                $sources=[$takeStock(0),$takeNeighbor(1),$takeStock(1),$takeStock(2),$takeNeighbor(3),$takeStock(3),$takeNeighbor(5),$takeStock(4)];
                $project->preview_gallery=array_map(
                    fn($url,$i)=>['url'=>$url,'alt'=>'Temporary photograph '.($i+1).' — replace with approved gallery for '.$item['title']],
                    $sources,array_keys($sources)
                );
            }
            $project->save();
        }
        $services=json_decode(file_get_contents(database_path('data/services-seed.json')),true,512,JSON_THROW_ON_ERROR);
        $byService=['interior-design'=>'interior-design-projects','interior-styling'=>'interior-styling-projects-the-luxury-look','luxury-residential-staging'=>'home-staging-projects-the-luxury-look','commercial-staging'=>'commercial-staging-projects-the-luxury-look','airbnb-staging'=>'airbnb-staging-the-luxury-look','corporate-branding'=>'corporate-brandding-projects-the-luxury-look'];
        $catBySlug=array_column($portfolio['categories'],null,'slug');
        foreach($services as $item) {
            $service=Service::where('slug',$item['slug'])->first();
            if($service && !$service->content_verified && !$service->preview_cover_image) {
                $service->preview_cover_image=$catBySlug[$byService[$item['slug']]]['hero_image'];$service->save();
            }
        }
    }
}
