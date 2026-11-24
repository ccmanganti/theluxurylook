<?php
namespace Tests\Feature;
use App\Models\{StudioVideo,ExperienceOffering};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class BuyingTripsVideoTest extends TestCase {
  use RefreshDatabase;
  protected function setUp():void {parent::setUp();Config::set('site.launch_ready',false);$this->seed();}
  public function test_buying_trips_are_parent_and_dallas_is_one_market():void {
    $this->assertSame(2,ExperienceOffering::where('kind','buying-trip')->count());
    $this->assertSame(4,ExperienceOffering::where('kind','dallas')->count());
    $this->get('/buying-trips')->assertOk()->assertSee('Dallas Market')->assertSee('Private Sourcing Journeys');
    $this->get('/buying-trips/dallas-market')->assertOk()->assertSee('Market Edit');
    $this->get('/dallas-market')->assertOk();
  }
  public function test_latest_vimeo_and_three_youtube_films_are_cms_editable_and_embeddable():void {
    $this->assertSame(4,StudioVideo::count());
    $featured=StudioVideo::orderBy('sort_order')->firstOrFail();
    $this->assertSame('vimeo',$featured->provider);
    $this->assertSame('420926c7fc',StudioVideo::sourceInfo($featured->playable_url)['hash']);
    $this->assertSame('1079407954',StudioVideo::sourceInfo($featured->playable_url)['id']);
    $this->assertSame(3,StudioVideo::where('placement','gallery')->count());
    $this->assertSame('gNeiecujx8Q',StudioVideo::youtubeId('https://youtu.be/gNeiecujx8Q'));
    $this->assertNull(StudioVideo::sourceInfo('https://fakevimeo.com/video/1079407954'));
    $this->assertNull(StudioVideo::sourceInfo('javascript:alert(1)'));
    $this->get('/')->assertOk()->assertSee('data-video-url')->assertSee('420926c7fc')
      ->assertSee('Studio 3125 Tour')->assertSee('Jb8Ye_MFJjA')->assertSee('NnwE6poxV1c')
      ->assertDontSee('atelier-motion.mp4')->assertDontSee('data-ambient-video');
    $this->get('/services')->assertOk()->assertSee('data-video-url')->assertSee('420926c7fc');
    Config::set('site.launch_ready',true);
    $this->assertSame(0,StudioVideo::visibleOnSite()->count());
  }
  public function test_buying_pages_remain_noindex_until_migration_approval():void {
    foreach(['/buying-trips','/buying-trips/dallas-market','/buying-trips/private-sourcing-journeys'] as $url)
      $this->get($url)->assertOk()->assertSee('noindex,nofollow,noarchive');
  }
}
