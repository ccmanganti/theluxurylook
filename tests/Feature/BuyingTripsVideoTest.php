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
    $this->get('/buying-trips/private-sourcing-journeys')->assertOk()->assertSee('Private Sourcing Journeys');
  }
  public function test_studio_video_is_click_to_play_and_embed_domain_is_checked():void {
    $video=StudioVideo::firstOrFail();
    $this->assertSame('UuUu8FOKULQ',$video->youtube_id);
    $this->assertSame(null,StudioVideo::youtubeId('https://notyoutube.com/watch?v=UuUu8FOKULQ'));
    $this->get('/')->assertOk()->assertSee('data-youtube-url')->assertSee('UuUu8FOKULQ')->assertDontSee('atelier-motion.mp4')->assertDontSee('data-ambient-video');
    $this->get('/services')->assertOk()->assertSee('data-youtube-url');
  }
  public function test_buying_pages_remain_noindex_until_migration_approval():void {
    foreach(['/buying-trips','/buying-trips/dallas-market','/buying-trips/private-sourcing-journeys'] as $url)
      $this->get($url)->assertOk()->assertSee('noindex,nofollow,noarchive');
  }
}
