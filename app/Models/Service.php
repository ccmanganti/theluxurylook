<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';
    protected $fillable = ['slug', 'title', 'summary', 'body', 'cover_image', 'cover_upload', 'preview_cover_image', 'meta_title', 'meta_description', 'seo_locality', 'seo_share_image', 'seo_location', 'seo_og_image', 'seo_noindex', 'seo_focus_location', 'seo_image_alt', 'sort_order', 'is_published', 'content_verified'];
    protected function casts(): array { return ['seo_noindex' => 'boolean', 'is_published' => 'boolean', 'content_verified' => 'boolean']; }

    public function getHeroUrlAttribute(): ?string
    {
        if ($this->cover_upload) return \Illuminate\Support\Facades\Storage::disk('public')->url($this->cover_upload);
        if (!config('site.launch_ready') && !$this->content_verified && $this->preview_cover_image) return $this->preview_cover_image;
        return $this->cover_image;
    }

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
