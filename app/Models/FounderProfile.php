<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/** Single-source founder biography: no private biographical assumptions or generated portraits. */
class FounderProfile extends Model {
    protected $fillable=['name','role','eyebrow','headline','lead','short_bio','career_bio','approach_bio','beyond_bio','founding_year','experience_label','portrait_url','portrait_upload','secondary_photo_url','secondary_photo_upload','headshot_url','headshot_upload','interview_url','interview_poster_url','principles','source_notes','is_published','content_verified'];
    protected function casts():array{return ['principles'=>'array','is_published'=>'boolean','content_verified'=>'boolean'];}
    public function scopeVisibleOnSite($query){$query->where('is_published',true);if(config('site.launch_ready'))$query->where('content_verified',true);return $query;}
    public function getPortraitSrcAttribute():?string{return $this->portrait_upload?Storage::disk('public')->url($this->portrait_upload):$this->portrait_url;}
    public function getSecondaryPhotoSrcAttribute():?string{return $this->secondary_photo_upload?Storage::disk('public')->url($this->secondary_photo_upload):$this->secondary_photo_url;}
    public function getHeadshotSrcAttribute():?string{return $this->headshot_upload?Storage::disk('public')->url($this->headshot_upload):$this->headshot_url;}
}
