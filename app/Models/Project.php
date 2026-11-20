<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';
    protected $fillable = ['portfolio_category_id', 'slug', 'title', 'location', 'summary', 'story', 'cover_image', 'cover_upload', 'gallery', 'gallery_uploads', 'meta_title', 'meta_description', 'featured', 'is_published', 'content_verified', 'sort_order'];
    protected function casts(): array { return ['featured' => 'boolean', 'is_published' => 'boolean', 'content_verified' => 'boolean', 'gallery' => 'array', 'gallery_uploads' => 'array']; }

    public function getHeroUrlAttribute(): ?string
    {
        return $this->cover_upload
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->cover_upload)
            : $this->cover_image;
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
