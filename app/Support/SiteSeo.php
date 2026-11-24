<?php
namespace App\Support;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

final class SiteSeo
{
    private static function definitions(): array
    {
        static $config;
        return $config ??= json_decode(file_get_contents(database_path('data/local-seo-pages.json')), true, 512, JSON_THROW_ON_ERROR);
    }
    public static function prepare(array $seo, array $data=[]): array
    {
        $config=self::definitions();
        $path=$seo['path'] ?? ('/'.ltrim((string)request()->path(), '/'));
        $path=($path==='/'||$path==='')?'/':'/'.trim($path,'/');
        $siteUrl='https://'.config('site.canonical_host');
        $route=$config['routes'][$path]??null;
        $content=($data['post']??$data['project']??$data['category']??$data['service']??$data['offering']??$data['page']??null);
        $providedTitle=$seo['title']??'';
        $defaultTitle='The Luxury Look | Interior Design & Home Staging Scottsdale';
        $seo['title']=$content?->meta_title ?: ($route['title']??($providedTitle?:$defaultTitle));
        $seo['description']=trim((string)($content?->meta_description ?: ($route['description']??($seo['description']??''))));
        // Original project/article metadata has priority over a location keyword template.
        if(!$route && $content && $providedTitle && $providedTitle!==$defaultTitle){
            $seo['title']=$content->meta_title ?: $providedTitle;
        }
        $seo['title']=Str::limit(strip_tags($seo['title']),110,'');
        $seo['description']=Str::limit(preg_replace('/\s+/u',' ',strip_tags($seo['description'])),320,'');
        $seo['path']=$path;
        $seo['canonical']=$siteUrl.$path;
        $seo['image']=$content?->seo_share_image ?: ($seo['image']??config('site.default_og_image'));
        $seo['type']=str_starts_with($path,'/blog/')?'article':'website';
        $seo['breadcrumbs']=self::breadcrumbs($path,$data,$siteUrl);
        $seo['locality']=$content?->seo_locality ?: ($route && in_array($route['section'],['home','service'],true) ? 'Scottsdale' : null);
        $seo['local_section']=in_array($path,['/','/design','/home-staging','/builders-and-agents','/services','/contact','/the-studio'],true);
        $seo['graph']=self::graph($seo,$data,$config,$siteUrl,$route['section']??null);
        return $seo;
    }
    private static function breadcrumbs(string $path,array $data,string $base): array
    {
        if($path==='/')return [];
        $parts=array_values(array_filter(explode('/',$path)));
        $labels=['portfolio'=>'Portfolio','portfolios'=>'Portfolio','blog'=>'The Journal',
            'services'=>'Services','collection'=>'The Collection','buying-trips'=>'Buying Trips',
            'dallas-market'=>'Dallas Market','education'=>'Education','the-studio'=>'The Studio',
            'home-staging'=>'Home Staging','builders-and-agents'=>'Builders & Agents',
            'curated-circle'=>'The Curated Circle','about'=>'About','contact'=>'Contact'];
        $all=[['name'=>'Home','url'=>$base.'/']];
        if(count($parts)>1&&$parts[0]==='portfolios') {
            $all[]=['name'=>'Portfolio','url'=>$base.'/portfolio'];
        } else {
            for($i=0;$i<count($parts)-1;$i++){
                $prefix='/'.implode('/',array_slice($parts,0,$i+1));
                $all[]=['name'=>$labels[$parts[$i]]??Str::headline($parts[$i]),'url'=>$base.$prefix];
            }
        }
        $name=$data['post']->title??$data['project']->display_title??$data['category']->title??
            $data['service']->title??$data['offering']->title??$data['page']->title??$labels[end($parts)]??Str::headline(end($parts));
        $all[]=['name'=>$name,'url'=>$base.$path];
        return $all;
    }
    private static function graph(array $seo,array $data,array $config,string $base,?string $section): array
    {
        $business=$base.'/#business';
        $office=$config['office'];
        $graph=[];
        if(in_array($seo['path'],['/','/about','/the-studio','/contact'],true)){
            $graph[]=['@type'=>'LocalBusiness','@id'=>$business,'name'=>'The Luxury Look',
                'url'=>$base.'/','telephone'=>$office['phone'],'email'=>$office['email'],
                'address'=>['@type'=>'PostalAddress','streetAddress'=>$office['address'],
                    'addressLocality'=>$office['city'],'addressRegion'=>$office['region'],
                    'postalCode'=>$office['postal_code'],'addressCountry'=>$office['country']],
                'areaServed'=>array_map(fn($n)=>['@type'=>'Place','name'=>$n],$config['approved_service_area'])];
        }
        if($seo['path']==='/'){
            $graph[]=['@type'=>'WebSite','@id'=>$base.'/#website','url'=>$base.'/','name'=>'The Luxury Look','inLanguage'=>'en-US'];
        }
        $web=['@type'=>'WebPage','@id'=>$seo['canonical'].'#webpage','url'=>$seo['canonical'],
            'name'=>$seo['title'],'description'=>$seo['description'],'inLanguage'=>'en-US',
            'isPartOf'=>['@id'=>$base.'/#website']];
        if($seo['image'])$web['primaryImageOfPage']=['@type'=>'ImageObject','url'=>$seo['image']];
        if($seo['breadcrumbs'])$web['breadcrumb']=['@id'=>$seo['canonical'].'#breadcrumb'];
        $graph[]=$web;
        if($seo['breadcrumbs']){
            $graph[]=['@type'=>'BreadcrumbList','@id'=>$seo['canonical'].'#breadcrumb',
                'itemListElement'=>array_map(fn($x,$idx)=>['@type'=>'ListItem','position'=>$idx+1,'name'=>$x['name'],'item'=>$x['url']],$seo['breadcrumbs'],array_keys($seo['breadcrumbs']))];
        }
        $path=$seo['path'];
        if($section==='service'||str_starts_with($path,'/services/')){
            $name=$data['service']->title??$data['offering']->title??$seo['title'];
            $graph[]=['@type'=>'Service','@id'=>$seo['canonical'].'#service','name'=>$name,
                'description'=>$seo['description'],'url'=>$seo['canonical'],
                'provider'=>['@id'=>$business],
                'areaServed'=>array_map(fn($n)=>['@type'=>'Place','name'=>$n],$config['approved_service_area'])];
        }
        if(isset($data['post'])){
            $post=$data['post'];
            $article=['@type'=>'BlogPosting','@id'=>$seo['canonical'].'#article',
                'headline'=>$post->title,'description'=>$seo['description'],'mainEntityOfPage'=>$seo['canonical'],
                'publisher'=>['@id'=>$business]];
            if($post->published_at)$article['datePublished']=$post->published_at->toIso8601String();
            if($post->updated_at)$article['dateModified']=$post->updated_at->toIso8601String();
            if($post->source_attribution)$article['author']=['@type'=>'Person','name'=>$post->source_attribution];
            if($seo['image'])$article['image']=$seo['image'];
            $graph[]=$article;
        }
        if(isset($data['project'])){
            $project=$data['project'];
            $graph[]=['@type'=>'CreativeWork','@id'=>$seo['canonical'].'#project',
                'name'=>$project->display_title,'description'=>$seo['description'],'url'=>$seo['canonical'],
                'about'=>['@type'=>'Thing','name'=>$project->category?->title??'Interior Design']];
        }
        return ['@context'=>'https://schema.org','@graph'=>$graph];
    }
}
