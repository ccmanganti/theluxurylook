<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';
    protected $fillable = ['slug', 'title', 'summary', 'body', 'cover_image', 'cover_upload', 'meta_title', 'meta_description', 'sort_order', 'is_published', 'content_verified'];
    protected function casts(): array { return ['is_published' => 'boolean', 'content_verified' => 'boolean']; }

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
