<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    protected $fillable = ['slug', 'title', 'excerpt', 'body', 'cover_image', 'cover_upload', 'category', 'source_url', 'seo_location', 'seo_og_image', 'seo_noindex', 'meta_title', 'meta_description', 'is_published', 'content_verified', 'published_at', 'category', 'source_url', 'source_attribution', 'seo_locality', 'seo_share_image'];
    protected function casts(): array { return ['is_published' => 'boolean', 'content_verified' => 'boolean', 'published_at' => 'datetime', 'seo_noindex' => 'boolean']; }

    public function getHeroUrlAttribute(): ?string
    {
        return $this->cover_upload
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->cover_upload)
            : $this->cover_image;
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
