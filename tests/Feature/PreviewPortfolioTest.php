<?php
namespace Tests\Feature;
use App\Models\{PortfolioCategory,Project,Service};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class PreviewPortfolioTest extends TestCase {
 use RefreshDatabase;
 protected function setUp():void {parent::setUp(); Config::set('site.launch_ready',false);$this->seed();}
 public function test_all_54_projects_get_unique_cms_editable_working_titles():void {
  $this->assertSame(54,Project::count());
  $this->assertSame(54,Project::whereNotNull('preview_title')->distinct('preview_title')->count('preview_title'));
  $p=Project::where('slug','mockingbird-paradise-valley')->firstOrFail();
  $this->assertSame('The Veranda Estate',$p->display_title);
  $this->assertSame('Mockingbird Paradise Valley',$p->title);
  $this->assertSame('mockingbird-paradise-valley',$p->slug);
  $p->preview_title='The Sample Archive House';$p->save();
  $this->get('/portfolios/mockingbird-paradise-valley')->assertOk()->assertSee('The Sample Archive House');
  Config::set('site.launch_ready',true);
  $this->assertSame('Mockingbird Paradise Valley',$p->fresh()->display_title);
 }
 public function test_archive_legacy_routes_and_new_design_hierarchy():void {
  $this->get('/portfolio')->assertOk()->assertSee('The Veranda Estate');
  $this->get('/portfolio/corporate-brandding-projects-the-luxury-look')->assertOk()->assertSee('Corporate Branding');
  foreach(['/design','/builders-and-agents','/collection','/curated-circle'] as $url)
   $this->get($url)->assertOk()->assertSee('noindex, nofollow, noarchive');
 }
 public function test_project_gallery_is_editable_and_result_metrics_are_guarded():void {
  $p=Project::where('slug','mockingbird-paradise-valley')->firstOrFail();
  $this->assertCount(8,$p->gallery_items);
  $this->get('/portfolios/mockingbird-paradise-valley')->assertOk()->assertSee('IMAGE PLACEHOLDER');
  $p->metrics=[['label'=>'Days on market','value'=>'11','source'=>'Not yet verified']];
  $p->result_statement='Example result pending approval';$p->save();
  $this->get('/portfolios/mockingbird-paradise-valley')->assertDontSee('Days on market');
  $p->gallery=[['url'=>'https://example.com/approved-image.jpg','alt'=>'Approved']];$p->save();
  $this->assertCount(1,$p->fresh()->gallery_items);
 }
 public function test_preview_images_and_working_titles_are_removed_in_live_mode():void {
  $p=Project::where('slug','mockingbird-paradise-valley')->firstOrFail();
  $category=PortfolioCategory::where('slug','home-staging-projects-the-luxury-look')->firstOrFail();
  $service=Service::where('slug','luxury-residential-staging')->firstOrFail();
  $this->assertNotEmpty($p->preview_gallery);
  Config::set('site.launch_ready',true);
  $this->assertSame($p->cover_image,$p->fresh()->hero_url);
  $this->assertSame([],$p->fresh()->gallery_items);
  $this->assertSame('Mockingbird Paradise Valley',$p->fresh()->display_title);
  $this->assertSame($category->hero_image,$category->fresh()->hero_url);
  $this->assertSame($service->cover_image,$service->fresh()->hero_url);
 }
 public function test_original_url_inventory_and_staging_indexing_guards():void {
  $this->get('/portfolio')->assertOk()->assertSee('noindex, nofollow, noarchive');
 }
}
