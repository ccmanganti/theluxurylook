<?php
namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class RestoreOriginalJournalSeeder extends Seeder
{
    public function run(): void
    {
        $source=json_decode(file_get_contents(database_path('data/blog-archive.json')),true,512,JSON_THROW_ON_ERROR);
        foreach($source as $article) {
            $post=Post::firstOrNew(['slug'=>$article['slug']]);
            // Never replace an editor's already saved content or media.
            foreach (['title','body','excerpt','cover_image','category','source_url','published_at'] as $field) {
                $value=$article[$field]??null;
                if ($value!==null && ($post->$field===null || $post->$field==='')) $post->$field=$value;
            }
            if (!$post->exists) {
                $post->is_published=true;
                $post->content_verified=false; // Requires editorial verification before a production launch.
            }
            $post->save();
        }
    }
}
