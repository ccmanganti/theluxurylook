<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
class ExperienceOffering extends Model {
    protected $fillable=['kind','slug','title','eyebrow','audience','summary','body','cover_image','cover_upload','hero_video','video_poster','youtube_url','cta','highlights','gallery','featured','is_published','content_verified','sort_order','meta_title','meta_description'];
    protected function casts():array {return ['highlights'=>'array','gallery'=>'array','featured'=>'boolean','is_published'=>'boolean','content_verified'=>'boolean'];}
    public function getHeroUrlAttribute():?string {return $this->cover_upload ? Storage::disk('public')->url($this->cover_upload) : $this->cover_image;}
    public function scopeVisibleOnSite($query){return config('site.launch_ready') ? $query->where('is_published',true)->where('content_verified',true) : $query->where('is_published',true);}
}
