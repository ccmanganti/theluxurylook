<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegacyUrl extends Model
{
    protected $table = 'legacy_urls';
    protected $fillable = ['path', 'source_url', 'content_type', 'status', 'redirect_to', 'included_in_original_sitemap', 'migration_notes'];
    protected function casts(): array { return ['included_in_original_sitemap' => 'boolean']; }

}
