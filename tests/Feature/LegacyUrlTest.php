<?php
namespace Tests\Feature;

use App\Models\LegacyUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegacyUrlTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void {parent::setUp(); $this->seed();}

    public function test_every_original_sitemap_path_is_registered(): void
    {
        $catalog=json_decode(file_get_contents(database_path('data/legacy-sitemap.json')),true);
        $this->assertCount(96,$catalog);
        foreach ($catalog as $url) $this->assertTrue(LegacyUrl::where('path',$url['path'])->exists(),$url['path']);
    }
    public function test_legacy_portfolio_and_article_paths_render_on_preview(): void
    {
        $this->get('/portfolio/corporate-brandding-projects-the-luxury-look')->assertOk()->assertSee('Corporate Branding');
        $this->get('/portfolios/petersen-residence')->assertOk()->assertSee('Petersen Residence');
        $this->get('/blog/from-drab-to-market-ready---a-living-room-reinvented')->assertOk();
    }
    public function test_preview_is_not_indexable(): void
    {
        $this->get('/')->assertHeader('X-Robots-Tag','noindex, nofollow, noarchive');
        $this->get('/robots.txt')->assertSee('Disallow: /');
    }
    public function test_unknown_pages_are_not_invented(): void { $this->get('/portfolios/non-existing-record')->assertNotFound(); }
    public function test_contact_form_stores_a_new_inquiry(): void
    {
        $this->post('/contact',['name'=>'Visitor','email'=>'visitor@example.com','message'=>'I want to explore an interior design project in Scottsdale.'])->assertRedirect('/contact');
        $this->assertDatabaseHas('inquiries',['email'=>'visitor@example.com','status'=>'new']);
    }
}
