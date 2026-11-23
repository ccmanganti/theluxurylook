<?php
namespace Database\Seeders;

use App\Models\{LegacyUrl, Page, PortfolioCategory, Post, Project, Service};
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $json = fn (string $file): array => json_decode(file_get_contents(database_path('data/'.$file)), true, flags: JSON_THROW_ON_ERROR);
        $catalog = $json('legacy-sitemap.json');
        $extra = $json('discovered-not-in-sitemap.json');
        $type = function (string $path): string {
            return match (true) {
                $path==='/' => 'home',
                str_starts_with($path,'/portfolios/')=>'project',
                str_starts_with($path,'/portfolio/')=>'portfolio_category',
                str_starts_with($path,'/blog/')=>'article',
                str_starts_with($path,'/product/')=>'legacy_product',
                str_starts_with($path,'/template-info/')=>'template_artifact',
                in_array($path,['/checkout','/paypal-checkout','/order-confirmation'])=>'transactional',
                default=>'page',
            };
        };
        foreach ($catalog as $url) {
            LegacyUrl::firstOrCreate(['path'=>$url['path']], [
                'source_url'=>$url['source'], 'content_type'=>$type($url['path']),
                'status'=>'needs_content','included_in_original_sitemap'=>true,
            ]);
        }
        foreach ($extra as $url) {
            LegacyUrl::firstOrCreate(['path'=>$url['path']], [
                'source_url'=>$url['source'], 'content_type'=>$type($url['path']),
                'status'=>'needs_content','included_in_original_sitemap'=>false,
                'migration_notes'=>$url['reason'],
            ]);
        }
        foreach ($json('pages-seed.json') as $p) Page::firstOrCreate(['path'=>$p['path']], $p);
        foreach ($json('services-seed.json') as $s) Service::firstOrCreate(['slug'=>$s['slug']], $s);
        $portfolio = $json('portfolio-seed.json');
        foreach ($portfolio['categories'] as $c) PortfolioCategory::firstOrCreate(['slug'=>$c['slug']], $c);
        foreach ($portfolio['projects'] as $p) {
            $categorySlug=$p['category_slug']; unset($p['category_slug']);
            $p['portfolio_category_id']=PortfolioCategory::where('slug',$categorySlug)->value('id');
            Project::firstOrCreate(['slug'=>$p['slug']], $p);
        }
        foreach (array_merge($catalog,$extra) as $url) {
            if (!str_starts_with($url['path'],'/blog/')) continue;
            $slug=Str::after($url['path'],'/blog/');
            $title=Str::headline(str_replace('---',' - ',$slug));
            Post::firstOrCreate(['slug'=>$slug],[
                'title'=>$title,'excerpt'=>'This article is listed on the existing The Luxury Look website. Full editorial content and publication metadata are pending migration.',
                'body'=>null,'is_published'=>true,'content_verified'=>false,
            ]);
        }
        // Staging-only photo and working-title backfill is non-destructive.
        $this->call(PreviewPhotographySeeder::class);
        $this->call(PreviewTitlesSeeder::class);
        // Every original path requires an explicit content and SEO review.
        // This migration preview deliberately approves no URL automatically.
    }
}
