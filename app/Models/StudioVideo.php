<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** CMS video from a validated YouTube or Vimeo URL; no arbitrary iframe HTML. */
class StudioVideo extends Model {
    protected $fillable = ['title','video_url','youtube_url','poster_url','placement','caption','sort_order','is_published','content_verified'];
    protected function casts(): array {return ['sort_order'=>'integer','is_published'=>'boolean','content_verified'=>'boolean'];}

    public static function sourceInfo(?string $url): ?array {
        if (!$url) return null;
        $parts = parse_url(trim($url));
        if (!$parts || strtolower($parts['scheme'] ?? '') !== 'https' || empty($parts['host'])) return null;
        $host = strtolower(preg_replace('/^www\./','',$parts['host']));
        $path = trim($parts['path'] ?? '', '/');
        if (isset($parts['user']) || isset($parts['pass']) || isset($parts['port'])) return null;
        if ($host === 'youtu.be') $id = explode('/',$path)[0] ?? null;
        elseif (in_array($host,['youtube.com','m.youtube.com','youtube-nocookie.com'],true)) {
            parse_str($parts['query'] ?? '', $params);
            $id = $params['v'] ?? null;
            if (!$id && preg_match('~^(?:embed|shorts|live)/([a-zA-Z0-9_-]{11})$~',$path,$m)) $id=$m[1];
        } else $id = null;
        if (is_string($id) && preg_match('/^[a-zA-Z0-9_-]{11}$/',$id)) return ['provider'=>'youtube','id'=>$id,'hash'=>null];
        if (!in_array($host,['vimeo.com','player.vimeo.com'],true)) return null;
        $regex = $host === 'player.vimeo.com' ? '~^video/([0-9]+)$~' : '~^([0-9]+)(?:/([a-fA-F0-9]{6,32}))?$~';
        if (!preg_match($regex,$path,$m)) return null;
        parse_str($parts['query'] ?? '', $params);
        $hash = $params['h'] ?? ($m[2] ?? null);
        if ($hash !== null && (!is_string($hash) || !preg_match('/^[a-fA-F0-9]{6,32}$/',$hash))) return null;
        return ['provider'=>'vimeo','id'=>$m[1],'hash'=>$hash];
    }
    public static function youtubeId(?string $url): ?string {
        $source=self::sourceInfo($url);
        return $source && $source['provider']==='youtube' ? $source['id'] : null;
    }
    public function getPlayableUrlAttribute(): ?string {return $this->video_url ?: $this->youtube_url;}
    public function getProviderAttribute(): ?string {return self::sourceInfo($this->playable_url)['provider'] ?? null;}
    public function getYoutubeIdAttribute(): ?string {return self::youtubeId($this->playable_url);}
    public function scopeVisibleOnSite($query) {
        return config('site.launch_ready')
            ? $query->where('is_published',true)->where('content_verified',true)
            : $query->where('is_published',true);
    }
}
