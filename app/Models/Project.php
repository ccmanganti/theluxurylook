<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';
    protected $fillable = ['portfolio_category_id', 'slug', 'title', 'location', 'summary', 'story', 'cover_image', 'cover_upload', 'preview_cover_image', 'gallery', 'gallery_uploads', 'preview_gallery', 'meta_title', 'meta_description', 'featured', 'is_published', 'content_verified', 'sort_order'];
    protected function casts(): array { return ['featured' => 'boolean', 'is_published' => 'boolean', 'content_verified' => 'boolean', 'gallery' => 'array', 'gallery_uploads' => 'array', 'preview_gallery' => 'array']; }

    public function getHeroUrlAttribute(): ?string
    {
        if ($this->cover_upload) return \Illuminate\Support\Facades\Storage::disk('public')->url($this->cover_upload);
        if (!config('site.launch_ready') && !$this->content_verified && $this->preview_cover_image) return $this->preview_cover_image;
        return $this->cover_image;
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PortfolioCategory::class, 'portfolio_category_id');
    }
    public function getGalleryItemsAttribute(): array
    {
        $items = $this->gallery ?? [];
        foreach ($this->gallery_uploads ?? [] as $path) {
            $items[] = ['url' => \Illuminate\Support\Facades\Storage::disk('public')->url($path), 'alt' => $this->title];
        }
        // Temporary imagery is visible only in development, never after public launch.
        if (!$items && !config('site.launch_ready') && !$this->content_verified) {
            return $this->preview_gallery ?? [];
        }
        return $items;
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
