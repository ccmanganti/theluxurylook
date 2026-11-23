<?php
namespace Tests\Feature;

use App\Models\{PortfolioCategory,Project,Service};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class PreviewPortfolioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('site.launch_ready',false);
        $this->seed();
    }

    public function test_archive_has_all_projects_and_legacy_category_routes(): void
    {
        $this->assertSame(54, Project::count());
        $this->get('/portfolio')->assertOk()->assertSee('Spaces worth')->assertSee('Mockingbird Paradise Valley');
        $this->get('/portfolio/corporate-brandding-projects-the-luxury-look')->assertOk()->assertSee('Corporate Branding');
    }

    public function test_temporary_photography_populates_gallery_and_is_editable(): void
    {
        $project=Project::where('slug','mockingbird-paradise-valley')->firstOrFail();
        $this->assertNotEmpty($project->preview_cover_image);
        $this->assertCount(8,$project->gallery_items);
        $this->get('/portfolios/mockingbird-paradise-valley')->assertOk()->assertSee('IMAGE PLACEHOLDER')->assertSee('Explore every angle');
        // An approved gallery URL overrides *all* sample photos on preview.
        $project->gallery=[['url'=>'https://example.com/real-client-photo.jpg','alt'=>'Approved photo']];
        $project->save();
        $this->assertCount(1,$project->fresh()->gallery_items);
    }

    public function test_preview_assets_are_never_used_after_launch(): void
    {
        $project=Project::where('slug','mockingbird-paradise-valley')->firstOrFail();
        $category=PortfolioCategory::where('slug','home-staging-projects-the-luxury-look')->firstOrFail();
        $service=Service::where('slug','luxury-residential-staging')->firstOrFail();
        $this->assertNotEmpty($project->preview_gallery);
        Config::set('site.launch_ready',true);
        $project=$project->fresh();
        $this->assertSame($project->cover_image,$project->hero_url);
        $this->assertSame([],$project->gallery_items);
        $this->assertSame($category->hero_image,$category->fresh()->hero_url);
        $this->assertSame($service->cover_image,$service->fresh()->hero_url);
    }

    public function test_new_portfolio_index_is_not_indexable_by_default(): void
    {
        $this->get('/portfolio')->assertOk()->assertSee('noindex, nofollow, noarchive');
    }
}
