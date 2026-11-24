<?php
namespace App\Support;

use Illuminate\Support\Str;

final class SiteSeo
{
    // Only Scottsdale is a verified office. Other names describe markets in actual work.
    public const ALLOWED_MARKETS=['Scottsdale','Paradise Valley','Phoenix','Cave Creek','Sedona'];

    public static function market(?string $location): ?string
    {
        if (!$location) return null;
        foreach(self::ALLOWED_MARKETS as $market) {
            if (preg_match('/\b'.preg_quote($market,'/').'\b/i',$location)) return $market;
        }
        return null;
    }

    public static function canonical(string $path): string
    {
        $path='/'.ltrim($path,'/');
        if($path!=='/')$path=rtrim($path,'/');
        return 'https://'.config('site.canonical_host').$path;
    }

    public static function defaultTitle(string $path): ?string
    {
        return [
            '/'=>'Luxury Interior Design in Scottsdale, Arizona | The Luxury Look',
            '/design'=>'Luxury Interior Design in Scottsdale, AZ | The Luxury Look',
            '/services'=>'Scottsdale Interior Design & Staging Services | The Luxury Look',
            '/home-staging'=>'Luxury Home Staging in Scottsdale, AZ | The Luxury Look',
            '/builders-and-agents'=>'Luxury Spec Home Design & Staging | Scottsdale, AZ',
            '/portfolio'=>'Luxury Interior Design Portfolio | The Luxury Look',
            '/contact'=>'Interior Design Consultation | Scottsdale, Arizona',
            '/blog'=>'The Journal | Interior Design, Staging & Inspired Living',
            '/about'=>'Meet Kristine Vowles | The Luxury Look',
            '/the-studio'=>'The Luxury Look Interior Design Studio | Scottsdale, AZ',
            '/buying-trips'=>'Curated Furniture Buying Trips | The Luxury Look',
            '/buying-trips/dallas-market'=>'Dallas Market Buying Experiences | The Luxury Look',
            '/collection'=>'Curated Furniture & Housewares | The Luxury Look',
        ][$path]??null;
    }

    public static function enrich(array $seo, array $data=[]): array
    {
        $path=$seo['path']??'/';
        $entity=$data['post']??$data['project']??$data['service']??$data['category']??$data['page']??null;
        $market=self::market($entity?->seo_focus_location??null);
        if (!$market && isset($data['project'])) $market=self::market($data['project']->location);
        if (!$market && (in_array($path,['/','/design','/home-staging','/builders-and-agents','/portfolio','/contact','/services','/the-studio'],true) || Str::startsWith($path,'/services/'))) $market='Scottsdale';
        $seo['market']=$market;
        if (self::defaultTitle($path) && (empty($seo['title']) || Str::startsWith($seo['title'],'The Luxury Look |'))) $seo['title']=self::defaultTitle($path);
        $description=trim(strip_tags((string)($seo['description']??'')));
        if(strlen($description)<40)$description='Explore thoughtfully designed interiors, projects, and perspectives from The Luxury Look.';
        if($market && !Str::contains(Str::lower($description),Str::lower($market)) && Str::startsWith($path,['/services','/design','/home-staging','/portfolio','/portfolios','/builders-and-agents','/contact'])) {
            $description=rtrim($description,'. ').'. Explore The Luxury Look in '.$market.', Arizona.';
        }
        $seo['description']=Str::limit(preg_replace('/\s+/',' ',$description),165,'');
        $seo['canonical']=self::canonical($path);
        $seo['type']=isset($data['post'])?'article':'website';
        $seo['published_at']=isset($data['post'])&&$data['post']->published_at?$data['post']->published_at->toAtomString():null;
        $seo['modified_at']=$entity?->updated_at?->toAtomString();
        $seo['image_alt']=$entity?->seo_image_alt??null;
        $seo['schema']=self::schema($seo,$data);
        return $seo;
    }

    public static function schema(array $seo,array $data=[]): array
    {
        $url=$seo['canonical'];$root='https://'.config('site.canonical_host');
        $business=['@type'=>['LocalBusiness','InteriorDesignBusiness'],'@id'=>$root.'/#business','name'=>'The Luxury Look','url'=>$root.'/',
            'telephone'=>config('site.company.phone'),'email'=>config('site.company.email'),
            'address'=>['@type'=>'PostalAddress','streetAddress'=>'4343 N Scottsdale Rd, Suite 150','addressLocality'=>'Scottsdale','addressRegion'=>'AZ','postalCode'=>'85251','addressCountry'=>'US']];
        $crumbs=[['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>$root.'/']];
        $segments=array_values(array_filter(explode('/',trim($seo['path']??'/','/'))));$progress='';
        foreach ($segments as $i=>$segment) {
            $progress.='/'.$segment;
            $name=match($segment){'portfolios'=>'Portfolio','portfolio'=>'Portfolio','blog'=>'Journal','services'=>'Services','buying-trips'=>'Buying Trips',default=>Str::headline($segment)};
            if($i===count($segments)-1)$name=$data['post']->title??$data['project']->title??$data['service']->title??$data['category']->title??$data['page']->title??$name;
            $crumbs[]=['@type'=>'ListItem','position'=>count($crumbs)+1,'name'=>$name,'item'=>self::canonical($progress)];
        }
        $page=['@type'=>isset($data['post'])?'BlogPosting':(isset($data['project'])?'CreativeWork':'WebPage'),
            '@id'=>$url.'#webpage','url'=>$url,'name'=>$seo['title'],'description'=>$seo['description'],
            'inLanguage'=>'en-US','isPartOf'=>['@id'=>$root.'/#website'],'publisher'=>['@id'=>$root.'/#business']];
        if(!empty($seo['image']))$page['image']=$seo['image'];
        if(!empty($seo['published_at']))$page['datePublished']=$seo['published_at'];
        if(!empty($seo['modified_at']))$page['dateModified']=$seo['modified_at'];
        if(isset($data['post'])) {
            $page['author']=['@type'=>'Organization','name'=>$data['post']->author_name?:'The Luxury Look'];
            if(!empty($data['post']->category))$page['articleSection']=$data['post']->category;
        }
        if(isset($data['service'])) {
            $page['@type']='Service';$page['serviceType']=$data['service']->title;
            $page['provider']=['@id'=>$root.'/#business'];
            if(!empty($seo['market']))$page['areaServed']=['@type'=>'Place','name'=>$seo['market'].', Arizona'];
        }
        return ['@context'=>'https://schema.org','@graph'=>[
            $business,['@type'=>'WebSite','@id'=>$root.'/#website','url'=>$root.'/','name'=>'The Luxury Look','publisher'=>['@id'=>$root.'/#business'],'inLanguage'=>'en-US'],
            $page,['@type'=>'BreadcrumbList','@id'=>$url.'#breadcrumbs','itemListElement'=>$crumbs]
        ]];
    }
}
