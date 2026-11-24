<?php
namespace Database\Seeders;
use App\Models\{Post, LegacyUrl};
use Illuminate\Database\Seeder;
class JournalArchiveSeeder extends Seeder {
 public function run(): void {
  $items=json_decode(file_get_contents(database_path('data/blog-archive.json')),true,512,JSON_THROW_ON_ERROR);
  foreach($items as $item) {
   $p=Post::firstOrCreate(['slug'=>$item['slug']],[
    'title'=>$item['title'],'excerpt'=>$item['excerpt'],'body'=>$item['body'],
    'cover_image'=>$item['cover_image'],'category'=>$item['category'],
    'source_url'=>$item['source_url'],'published_at'=>$item['published_at'],
    'is_published'=>true,'content_verified'=>false
   ]);
   if(!$p->content_verified) {
    $changes=[];
    if(!$p->body)$changes['body']=$item['body'];
    if(!$p->cover_image&&!$p->cover_upload)$changes['cover_image']=$item['cover_image'];
    if(!$p->category)$changes['category']=$item['category'];
    if(!$p->source_url)$changes['source_url']=$item['source_url'];
    if(!$p->published_at&&$item['published_at'])$changes['published_at']=$item['published_at'];
    if(str_contains((string)$p->excerpt,'pending migration'))$changes['excerpt']=$item['excerpt'];
    if($changes)$p->fill($changes)->save();
   }
   LegacyUrl::firstOrCreate(['path'=>'/blog/'.$item['slug']],[
    'source_url'=>$item['source_url'],'content_type'=>'article',
    'status'=>'needs_content','included_in_original_sitemap'=>false,
    'migration_notes'=>'Original Webflow Journal listing; content awaiting editorial approval.'
   ]);
  }
 }
}
