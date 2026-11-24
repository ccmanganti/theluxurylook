<?php
namespace Tests\Feature;
use App\Models\FounderProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
class FounderProfileTest extends TestCase {
    use RefreshDatabase;
    protected function setUp():void {parent::setUp();Config::set('site.launch_ready',false);$this->seed();}
    public function test_founder_is_prominent_on_home_and_about_with_real_source_photos():void {
        $this->assertSame(1,FounderProfile::count());
        $this->get('/')->assertOk()->assertSee('Meet Kristine')->assertSee('Kristine-Vowles-Headshot.jpg')->assertSee('NnwE6poxV1c');
        $this->get('/about')->assertOk()->assertSee('Kristine Vowles')->assertSee('IMG_4393_Enhanced')->assertSee('376382748_')
          ->assertSee('35+ years')->assertSee('2013')->assertSee('NnwE6poxV1c')->assertSee('gNeiecujx8Q');
    }
    public function test_editor_can_change_biography_and_source_without_changing_legacy_urls():void {
        $founder=FounderProfile::firstOrFail();
        $founder->headline='An authentic new editorial headline';
        $founder->portrait_url='https://example.com/approved-kristine.jpg';
        $founder->save();
        $this->get('/about')->assertOk()->assertSee('An authentic new editorial headline')->assertSee('approved-kristine.jpg');
        $this->get('/portfolios/mockingbird-paradise-valley')->assertOk();
        Config::set('site.launch_ready',true);
        $this->assertNull(FounderProfile::visibleOnSite()->first());
    }
}
