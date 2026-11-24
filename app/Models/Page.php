<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';
    protected $fillable = ['path', 'title', 'eyebrow', 'intro', 'body', 'hero_image', 'meta_title', 'meta_description', 'is_published', 'content_verified', 'sections', 'layout_variant', 'hero_video', 'hero_video_poster', 'cta_text', 'cta_url'];
    protected function casts(): array { return ['is_published' => 'boolean', 'content_verified' => 'boolean', 'sections' => 'array']; }

    public function scopeVisibleOnSite($query)
    {
        if (config('site.launch_ready')) {
            $query->where('is_published', true);
            if (in_array('content_verified', $this->getFillable(), true)) {
                $query->where('content_verified', true);
            }
        }
        return $query;
    }

}
