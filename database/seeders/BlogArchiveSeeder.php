<?php
namespace Database\Seeders;
use App\Models\{Post,LegacyUrl};
use Illuminate\Database\Seeder;

class BlogArchiveSeeder extends Seeder
{
    public function run(): void
    {
        $items=json_decode(file_get_contents(database_path('data/blog-archive.json')),true,512,JSON_THROW_ON_ERROR);
        foreach($items as $item){
            // Preserve existing editors' work; only replace the known empty migration placeholders.
            $post=Post::firstOrCreate(['slug'=>$item['slug']],[
                'title'=>$item['title'],
                'excerpt'=>$item['excerpt'],
                'body'=>$item['body'],
                'cover_image'=>$item['cover_image'],
                'published_at'=>$item['published_at'],
                'category'=>$item['category'],
                'source_url'=>$item['source_url'],
                'is_published'=>true,
                'content_verified'=>false,
            ]);
            if(!$post->content_verified){
                $patch=[];
                if(!$post->body || str_contains((string)$post->body,'Full editorial content')) $patch['body']=$item['body'];
                if(!$post->excerpt || str_starts_with((string)$post->excerpt,'This article is listed')) $patch['excerpt']=$item['excerpt'];
                if(!$post->cover_image && !$post->cover_upload && $item['cover_image']) $patch['cover_image']=$item['cover_image'];
                if(!$post->source_url) $patch['source_url']=$item['source_url'];
                if(!$post->category) $patch['category']=$item['category'];
                if(!$post->published_at && $item['published_at']) $patch['published_at']=$item['published_at'];
                if($patch)$post->fill($patch)->save();
            }
            // This article appeared in the live blog but was absent from the old sitemap.
            LegacyUrl::firstOrCreate(['path'=>'/blog/'.$item['slug']],[
                'source_url'=>$item['source_url'],'content_type'=>'article',
                'status'=>'needs_content','included_in_original_sitemap'=>false,
                'migration_notes'=>'Restored from existing Webflow article; requires copy, attribution and media approval.'
            ]);
        }
    }
}
