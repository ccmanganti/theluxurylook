<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Videos remain editable; only canonical YouTube video URLs are embedded. */
class StudioVideo extends Model
{
    protected $fillable=['title','youtube_url','poster_url','placement','caption','sort_order','is_published','content_verified'];
    protected function casts():array{return ['sort_order'=>'integer','is_published'=>'boolean','content_verified'=>'boolean'];}

    public static function youtubeId(?string $input): ?string
    {
        if (!$input) return null;
        $parts=parse_url($input);
        if (!$parts || !isset($parts['host'])) return null;
        $host=strtolower(preg_replace('/^www\./','',$parts['host']));
        $path=trim($parts['path']??'','/');
        $id=null;
        if ($host==='youtu.be') $id=explode('/',$path)[0]??null;
        elseif (in_array($host,['youtube.com','m.youtube.com','youtube-nocookie.com'],true)) {
            parse_str($parts['query']??'',$query);
            $id=$query['v']??null;
            if (!$id && preg_match('~^(?:embed|shorts|live)/([a-zA-Z0-9_-]{11})~',$path,$match))$id=$match[1];
        }
        return is_string($id)&&preg_match('/^[a-zA-Z0-9_-]{11}$/',$id)?$id:null;
    }

    public function getYoutubeIdAttribute():?string{return self::youtubeId($this->youtube_url);}
    public function scopeVisibleOnSite($query){return config('site.launch_ready') ? $query->where('is_published',true)->where('content_verified',true) : $query->where('is_published',true);}
}
