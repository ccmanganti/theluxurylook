<?php
namespace Tests\Feature;
use App\Models\{ExperienceOffering,Project};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
class ExperienceSiteTest extends TestCase {
    use RefreshDatabase;
    protected function setUp():void {parent::setUp();Config::set('site.launch_ready',false);$this->seed();}
    public function test_dallas_and_education_programs_are_in_cms_and_render():void {
        $this->assertSame(4,ExperienceOffering::where('kind','dallas')->count());
        $this->assertSame(4,ExperienceOffering::where('kind','education')->count());
        $this->get('/dallas-market')->assertOk()->assertSee('Private Market Buying Trips');
        $this->get('/education')->assertOk()->assertSee('Business Growth');
        $this->get('/dallas-market/private-market-buying-trips')->assertOk()->assertSee('Private Market Buying Trips');
        $this->get('/education/design-and-staging')->assertOk()->assertSee('Interior Design');
    }
    public function test_all_portfolio_tiles_use_seo_safe_editable_working_titles():void {
        $p=Project::where('slug','mockingbird-paradise-valley')->firstOrFail();
        $this->assertSame('The Veranda Estate',$p->display_title);
        $this->get('/portfolio')->assertOk()->assertSee('The Veranda Estate')->assertDontSee('>Mockingbird Paradise Valley<');
        $this->get('/portfolio/home-staging-projects-the-luxury-look')->assertOk()->assertSee('The Veranda Estate');
        $p->preview_title='The New Private Estate';$p->save();
        $this->get('/portfolios/mockingbird-paradise-valley')->assertOk()->assertSee('The New Private Estate');
        Config::set('site.launch_ready',true);
        $this->assertSame('Mockingbird Paradise Valley',$p->fresh()->display_title);
    }
    public function test_new_pages_are_unindexed_while_preview_is_in_progress():void {
        foreach(['/','/dallas-market','/education','/dallas-market/private-market-buying-trips'] as $url)
            $this->get($url)->assertOk()->assertSee('noindex,nofollow,noarchive');
    }
}
