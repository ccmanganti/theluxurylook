<?php
namespace App\Support;

use Illuminate\Support\Str;

/**
 * Site-wide local search metadata. Never invent service areas, review scores,
 * project prices, awards, testimonials, or office locations.
 */
final class LocalSeo
{
    private const CITY = 'Scottsdale, Arizona';
    private const ORIGIN = 'https://www.theluxurylook.com';

    public static function enrich(array $seo, array $data = []): array
    {
        $path = $seo['path'] ?? '/';
        $seo['path'] = $path;
        // Internal search, checkout and drafts must never be indexed.
        $seo['noindex'] = (bool)($seo['noindex'] ?? false);
        $seo['type'] = str_starts_with($path, '/blog/') ? 'article' : (str_starts_with($path,'/portfolios/') ? 'project' : 'page');
        $seo['location'] = null;
        $locationRoutes = [
            '/' => ['Interior Design & Luxury Home Staging in Scottsdale, AZ | The Luxury Look',
                'Explore luxury residential interior design, high-end home staging, curated furnishings and the work of Kristine Vowles in Scottsdale, Arizona.'],
            '/design' => ['Luxury Interior Design in Scottsdale, AZ | The Luxury Look',
                'Explore custom home design, thoughtful renovations and curated furnishings with The Luxury Look, an interior design house in Scottsdale, Arizona.'],
            '/home-staging' => ['Luxury Home Staging in Scottsdale, AZ | The Luxury Look',
                'Prepare distinctive vacant and occupied Scottsdale homes for photography, showings and buyers with The Luxury Look luxury staging studio.'],
            '/builders-and-agents' => ['Scottsdale Spec Home Design & Staging | The Luxury Look',
                'Explore luxury spec-home design, builder collaboration and market-ready property presentation with The Luxury Look in Scottsdale, Arizona.'],
            '/contact' => ['Contact Our Scottsdale Interior Design Studio | The Luxury Look',
                'Tell The Luxury Look about your interior design, luxury home staging or furnishing project. Contact our Scottsdale, Arizona studio.'],
            '/services' => ['Interior Design & Home Staging Services in Scottsdale | The Luxury Look',
                'Explore Scottsdale luxury interior design, residential styling, staging and curated furnishing services from The Luxury Look.'],
            '/portfolio' => ['Luxury Interior Design & Staging Portfolio | The Luxury Look',
                'Explore real project galleries and luxury interior design and staging work from The Luxury Look. Project locations are provided where verified.'],
            '/about' => ['Meet Kristine Vowles | The Luxury Look Scottsdale',
                'Meet Kristine Vowles, founder of The Luxury Look, and explore her design philosophy and Scottsdale interior design studio.'],
            '/the-studio' => ['The Luxury Look Design Studio | Scottsdale, Arizona',
                'Meet The Luxury Look interior design studio in Scottsdale, Arizona and learn how to discuss your next design project.'],
            '/collection' => ['Turnkey Furniture & Housewares | The Luxury Look Collection',
                'Explore The Luxury Look Collection of furniture, housewares and sourcing services for furnished residences and second homes.'],
            '/buying-trips' => ['Furniture Buying Trips & Curated Sourcing | The Luxury Look',
                'Explore curated furniture buying trips and sourcing journeys, with Dallas Market as one possible destination.'],
            '/blog' => ['The Journal: Interior Design, Staging & Living | The Luxury Look',
                'Discover original The Luxury Look articles on home styling, staging, real estate, travel and interior design.'],
            '/education' => ['Interior Design & Home Staging Workshops | The Luxury Look',
                'Explore design, staging, real estate and business education with The Luxury Look. Request details about current programs.'],
            '/curated-circle' => ['The Curated Circle | The Luxury Look',
                'Explore the proposed The Curated Circle design community and register interest in future experiences.'],
        ];
        if (isset($locationRoutes[$path])) {
            [$seo['title'],$seo['description']] = $locationRoutes[$path];
            if (in_array($path,['/','/design','/home-staging','/builders-and-agents','/contact','/services','/the-studio','/about'],true))
                $seo['location'] = self::CITY;
        }
        foreach (['project','category','service','page','post','offering'] as $key) {
            $record=$data[$key]??null;
            if (!$record)continue;
            if (!empty($record->meta_title))$seo['title']=$record->meta_title;
            if (!empty($record->meta_description))$seo['description']=$record->meta_description;
            if (!empty($record->seo_og_image) || !empty($record->seo_share_image)) $seo['image']=$record->seo_og_image ?: $record->seo_share_image;
            if (!empty($record->seo_location) || !empty($record->seo_locality) || !empty($record->seo_focus_location)) $seo['location']=$record->seo_location ?: $record->seo_locality ?: $record->seo_focus_location;
            if (!empty($record->seo_noindex))$seo['noindex']=true;
            if ($key==='project' && empty($record->seo_location) && empty($record->seo_locality) && empty($record->seo_focus_location) && !empty($record->location))
                $seo['location']=$record->location;
        }
        $seo['title']=Str::limit(trim(strip_tags((string)($seo['title']??'The Luxury Look'))),72,'');
        $seo['description']=Str::limit(trim(strip_tags((string)($seo['description']??'Design-led spaces and a more considered way of living.'))),165,'');
        $seo['canonical']=self::ORIGIN.($path==='/'?'/':$path);
        $seo['breadcrumbs']=self::breadcrumbs($path, $seo['title']);
        $seo['jsonld']=self::schema($seo,$data);
        return $seo;
    }

    private static function breadcrumbs(string $path,string $title): array
    {
        $items=[['name'=>'Home','url'=>self::ORIGIN.'/']];
        if($path==='/')return $items;
        $parents=['portfolios'=>'/portfolio','blog'=>'/blog','services'=>'/services','portfolio'=>'/portfolio','buying-trips'=>'/buying-trips','education'=>'/education'];
        $sections=explode('/',trim($path,'/'));
        if(count($sections)>1 && isset($parents[$sections[0]]) && $parents[$sections[0]]!==$path)
            $items[]=['name'=>Str::headline($sections[0]),'url'=>self::ORIGIN.$parents[$sections[0]]];
        $items[]=['name'=>Str::limit(Str::before($title,' | The Luxury Look'),90,''),'url'=>self::ORIGIN.$path];
        return $items;
    }

    private static function schema(array $seo,array $data): array
    {
        $home=self::ORIGIN.'/';
        $url=$seo['canonical'];
        $business=[
            '@type'=>'LocalBusiness','@id'=>$home.'#studio','name'=>'The Luxury Look','url'=>$home,
            'telephone'=>config('site.company.phone'),'email'=>config('site.company.email'),
            'address'=>['@type'=>'PostalAddress','streetAddress'=>'4343 N Scottsdale Rd Suite 150',
                'addressLocality'=>'Scottsdale','addressRegion'=>'AZ','postalCode'=>'85251','addressCountry'=>'US'],
            'areaServed'=>['@type'=>'Place','name'=>self::CITY]
        ];
        $crumbs=['@type'=>'BreadcrumbList','@id'=>$url.'#breadcrumbs','itemListElement'=>[]];
        foreach($seo['breadcrumbs'] as $i=>$c)
            $crumbs['itemListElement'][]=['@type'=>'ListItem','position'=>$i+1,'name'=>$c['name'],'item'=>$c['url']];
        $page=['@type'=>'WebPage','@id'=>$url.'#page','url'=>$url,'name'=>$seo['title'],
            'description'=>$seo['description'],'isPartOf'=>['@id'=>$home.'#website'],
            'breadcrumb'=>['@id'=>$url.'#breadcrumbs']];
        if($seo['location'])$page['spatialCoverage']=['@type'=>'Place','name'=>$seo['location']];
        $graph=[
            ['@type'=>'WebSite','@id'=>$home.'#website','name'=>'The Luxury Look','url'=>$home,'publisher'=>['@id'=>$home.'#studio']],
            $business,$crumbs,$page
        ];
        if(!empty($data['post']) && filled($data['post']->body)) {
            $post=$data['post'];$article=['@type'=>'BlogPosting','@id'=>$url.'#article',
                'headline'=>$post->title,'mainEntityOfPage'=>$url,'description'=>$seo['description'],
                'publisher'=>['@id'=>$home.'#studio']];
            if($post->published_at)$article['datePublished']=$post->published_at->toAtomString();
            if($post->updated_at)$article['dateModified']=$post->updated_at->toAtomString();
            $author=$post->source_attribution ?: $post->author_name;
            if($author)$article['author']=['@type'=>'Person','name'=>$author];
            if(!empty($seo['image']))$article['image']=$seo['image'];
            $graph[]=$article;
        } elseif(!empty($data['service'])) {
            $graph[]=['@type'=>'Service','name'=>$data['service']->title,'description'=>$seo['description'],
                'provider'=>['@id'=>$home.'#studio'],'url'=>$url];
        }
        return ['@context'=>'https://schema.org','@graph'=>$graph];
    }
}
