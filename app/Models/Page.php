<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';
    protected $fillable = ['path', 'title', 'eyebrow', 'intro', 'body', 'hero_image', 'meta_title', 'meta_description', 'seo_locality', 'seo_share_image', 'seo_location', 'seo_og_image', 'seo_noindex', 'seo_focus_location', 'seo_image_alt', 'is_published', 'content_verified', 'sections', 'layout_variant', 'hero_video', 'hero_video_poster', 'youtube_url', 'cta_text', 'cta_url'];
    protected function casts(): array { return ['seo_noindex' => 'boolean', 'is_published' => 'boolean', 'content_verified' => 'boolean', 'sections' => 'array']; }

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
