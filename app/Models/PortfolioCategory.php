<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioCategory extends Model
{
    protected $table = 'portfolio_categories';
    protected $fillable = ['slug', 'title', 'tagline', 'description', 'hero_image', 'hero_upload', 'preview_hero_image', 'meta_title', 'meta_description', 'seo_location', 'seo_og_image', 'seo_noindex', 'seo_focus_location', 'seo_image_alt', 'sort_order', 'is_published', 'content_verified'];
    protected function casts(): array { return ['seo_noindex' => 'boolean', 'is_published' => 'boolean', 'content_verified' => 'boolean']; }

    public function getHeroUrlAttribute(): ?string
    {
        if ($this->hero_upload) return \Illuminate\Support\Facades\Storage::disk('public')->url($this->hero_upload);
        if (!config('site.launch_ready') && !$this->content_verified && $this->preview_hero_image) return $this->preview_hero_image;
        return $this->hero_image;
    }

    public function projects(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Project::class);
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
