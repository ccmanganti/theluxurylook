<?php
namespace App\Http\Controllers;

use App\Models\{Inquiry, LegacyUrl, Page, PortfolioCategory, Post, Project, Service, ExperienceOffering};
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\StudioVideo;
use App\Support\LocalSeo;
use App\Models\FounderProfile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SiteController extends Controller
{
    private function view(string $template, array $data = [], array $seo = [])
    {
        $seo += [
            'title' => 'The Luxury Look | Interior Design & Home Staging Scottsdale',
            'description' => 'Award-winning interior design, styling, home staging and branded environments in Scottsdale, Arizona.',
            'path' => request()->path() === '/' ? '/' : '/'.request()->path(),
            'image' => null,
            'noindex' => false,
        ];
        $urlDecision = LegacyUrl::where('path', $seo['path'])->value('status');
        if ($urlDecision && $urlDecision !== 'ready') $seo['noindex'] = true;
        $seo = \App\Support\SiteSeo::prepare($seo, $data);
        $seo = LocalSeo::enrich($seo, $data);
        return view($template, $data + compact('seo'));

    }

    public function home()
    {
        return $this->view('site.home', [
            'projects' => Project::visibleOnSite()->with('category')->where('featured', true)->orderBy('sort_order')->take(6)->get(),
            'categories' => PortfolioCategory::visibleOnSite()->withCount('projects')->orderBy('sort_order')->get(),
            'services' => Service::visibleOnSite()->orderBy('sort_order')->get(),
            'featuredVideo' => StudioVideo::visibleOnSite()->whereIn('placement',['home','services'])->orderBy('sort_order')->first(),
            'founder' => FounderProfile::visibleOnSite()->first(),
            'filmCollection' => StudioVideo::visibleOnSite()->where('placement','gallery')->orderBy('sort_order')->get(),
            'journal' => Post::visibleOnSite()->orderByDesc('published_at')->take(3)->get(),
        ], ['path'=>'/', 'description'=>'The Luxury Look is an award-winning interior design and home staging firm in Scottsdale, Arizona. Explore our projects and services.']);
    }

    public function portfolioIndex()
    {
        return $this->view('site.portfolio-index', [
            'projects' => Project::visibleOnSite()->with('category')->orderBy('sort_order')->get(),
            'categories' => PortfolioCategory::visibleOnSite()->withCount('projects')->orderBy('sort_order')->get(),
        ], [
            'title' => 'Projects | The Luxury Look',
            'description' => 'Browse luxury interior design, home staging, hospitality and select commercial work.',
            'noindex' => !config('site.index_new_pages'),
        ]);
    }

    public function design()
    {
        $page=Page::where('path','/design')->first();
        $projects=Project::visibleOnSite()->with('category')->whereHas('category',fn($query)=>$query->where('slug','interior-design-projects'))->orderBy('sort_order')->take(3)->get();
        $founder=FounderProfile::visibleOnSite()->first();
        return $this->view('site.design',compact('page','projects','founder'), [
            'title'=>'Luxury Interior Design | The Luxury Look',
            'description'=>'Full-service luxury residential design, renovation and second-home interiors led by Kristine Vowles.',
            'noindex'=>true,
        ]);
    }

    public function builders()
    {
        $page=Page::where('path','/builders-and-agents')->first();
        $projects=Project::visibleOnSite()->with('category')->where('builder_featured',true)->orderBy('sort_order')->take(6)->get();
        return $this->view('site.builders',compact('page','projects'),[
            'title'=>'For Builders & Agents | The Luxury Look',
            'description'=>'Luxury specifications, market-ready design and premium home staging for builders and real estate professionals.',
            'noindex'=>true,
        ]);
    }

    public function homeStaging()
    {
        $page=Page::where('path','/home-staging')->first();
        $projects=Project::visibleOnSite()->with('category')
            ->whereHas('category',fn($query)=>$query->where('slug','home-staging-projects-the-luxury-look'))
            ->orderBy('sort_order')->take(4)->get();
        return $this->view('site.home-staging',compact('page','projects'),[
            'title'=>'Luxury Home Staging | The Luxury Look',
            'description'=>'Vacant and occupied luxury home staging, property presentation, and styling for listing photography.',
            'noindex'=>true,
        ]);
    }

    public function collection()
    {
        $page=Page::where('path','/collection')->first();
        $projects=Project::visibleOnSite()->with('category')->where('featured',true)->orderBy('sort_order')->take(3)->get();
        return $this->view('site.collection',compact('page','projects'), [
            'title'=>'The Collection | The Luxury Look',
            'description'=>'Furniture and housewares lookbook curated by The Luxury Look.',
            'noindex'=>true,
        ]);
    }

    public function circle()
    {
        return $this->view('site.circle', [], [
            'title'=>'The Curated Circle | The Luxury Look',
            'description'=>'Discover The Curated Circle, a forthcoming design and lifestyle community.',
            'noindex'=>true,
        ]);
    }

    public function buyingTrips()
    {
        $page=Page::where('path','/buying-trips')->first();
        $offerings=ExperienceOffering::visibleOnSite()->where('kind','buying-trip')->orderBy('sort_order')->get();
        return $this->view('site.buying-trips',compact('page','offerings'),[
            'title'=>'Buying Trips & Curated Sourcing | The Luxury Look',
            'description'=>'Buying trips and custom sourcing journeys, including Dallas Market, tailored to your design and furnishing goals.',
            'noindex'=>true,
        ]);
    }

    /** New pages are excluded from the legacy XML sitemap until a launch review approves them. */
    public function dallas()
    {
        $kind='dallas';
        $offerings=ExperienceOffering::visibleOnSite()->where('kind',$kind)->orderBy('sort_order')->get();
        return $this->view('site.offerings-index',compact('offerings','kind'),[
            'title'=>'Dallas Market Buying Experiences | The Luxury Look',
            'description'=>'Explore private buying trips, guided sourcing and market selection conversations with The Luxury Look.',
            'noindex'=>true,
        ]);
    }
    public function education()
    {
        $kind='education';
        $offerings=ExperienceOffering::visibleOnSite()->where('kind',$kind)->orderBy('sort_order')->get();
        return $this->view('site.offerings-index',compact('offerings','kind'),[
            'title'=>'Design Education & Workshops | The Luxury Look',
            'description'=>'Learn about home staging, interior design, business growth and real estate education from The Luxury Look.',
            'noindex'=>true,
        ]);
    }
    public function offering(string $kind,string $slug)
    {
        abort_unless(in_array($kind,['dallas','education','buying-trip'],true),404);
        $offering=ExperienceOffering::visibleOnSite()->where('kind',$kind)->where('slug',$slug)->firstOrFail();
        return $this->view('site.offering-detail',compact('offering'),[
            'title'=>$offering->meta_title ?: $offering->title.' | The Luxury Look',
            'description'=>$offering->meta_description ?: $offering->summary,
            'image'=>$offering->hero_url,
            'noindex'=>true,
        ]);
    }

    public function services()
    {
        return $this->view('site.services', [
            'services'=>Service::visibleOnSite()->orderBy('sort_order')->get(),
            'featuredVideo'=>StudioVideo::visibleOnSite()->whereIn('placement',['home','services'])->orderBy('sort_order')->first(),
            'filmCollection'=>StudioVideo::visibleOnSite()->where('placement','gallery')->orderBy('sort_order')->get(),
        ], ['title'=>'Services - The Luxury Look | Interior Design, Scottsdale, AZ','description'=>'Explore interior design, interior styling, home staging, Airbnb staging, commercial staging and corporate branding services in Scottsdale.']);
    }

    public function service(string $slug)
    {
        $service = Service::visibleOnSite()->where('slug',$slug)->firstOrFail();
        $categorySlug = [
            'interior-design'=>'interior-design-projects','interior-styling'=>'interior-styling-projects-the-luxury-look',
            'luxury-residential-staging'=>'home-staging-projects-the-luxury-look','commercial-staging'=>'commercial-staging-projects-the-luxury-look',
            'airbnb-staging'=>'airbnb-staging-the-luxury-look','corporate-branding'=>'corporate-brandding-projects-the-luxury-look'
        ][$slug] ?? '';
        $category=PortfolioCategory::where('slug',$categorySlug)->first();
        $projects=$category ? $category->projects()->visibleOnSite()->with('category')->take(3)->get() : collect();
        return $this->view('site.service',compact('service','projects'),[
            'title'=>$service->meta_title ?: $service->title.' | The Luxury Look',
            'description'=>$service->meta_description ?: $service->summary,
            'image'=>$service->hero_url,
            'noindex'=>!$service->content_verified || !config('site.index_new_pages'), // Hold new URLs until reviewed and approved in a later SEO phase.
        ]);
    }

    public function category(string $slug)
    {
        $category = PortfolioCategory::visibleOnSite()->where('slug',$slug)->firstOrFail();
        return $this->view('site.category',[
            'category'=>$category,
            'projects'=>$category->projects()->visibleOnSite()->with('category')->orderBy('sort_order')->paginate(60),
            'categories'=>PortfolioCategory::visibleOnSite()->orderBy('sort_order')->get(),
        ], [
            'title'=>$category->meta_title ?: $category->title.' Portfolio | The Luxury Look',
            'description'=>$category->meta_description ?: $category->description,
            'image'=>$category->hero_image,
        ]);
    }

    public function project(string $slug)
    {
        $project = Project::visibleOnSite()->with('category')->where('slug',$slug)->firstOrFail();
        $related = Project::visibleOnSite()->where('id','!=',$project->id)
            ->when($project->portfolio_category_id,fn($q)=>$q->where('portfolio_category_id',$project->portfolio_category_id))
            ->orderBy('sort_order')->take(3)->get();
        return $this->view('site.project',compact('project','related'),[
            'title'=>$project->meta_title ?: $project->title.' | The Luxury Look',
            'description'=>$project->meta_description ?: $project->summary,
            'image'=>$project->hero_url,
        ]);
    }

    public function blog()
    {
        return $this->view('site.blog',[
            'posts'=>Post::visibleOnSite()->orderByDesc('published_at')->paginate(12),
        ], ['title'=>'Blog | The Luxury Look','description'=>'Interior design insights, staging tips and stories from The Luxury Look.']);
    }

    public function post(string $slug)
    {
        $post = Post::visibleOnSite()->where('slug',$slug)->firstOrFail();
        $relatedPosts=Post::visibleOnSite()->where('id','!=',$post->id)->where('category',$post->category)->limit(3)->get();
        return $this->view('site.post',compact('post','relatedPosts'),[
            'title'=>$post->meta_title ?: $post->title.' | The Luxury Look',
            'description'=>$post->meta_description ?: $post->excerpt,
            'image'=>$post->hero_url,
            'noindex'=>!$post->content_verified,
        ]);
    }

    public function page(Request $request)
    {
        $path = '/'.ltrim($request->path(),'/');
        $page = Page::visibleOnSite()->where('path',$path)->first();
        if (!$page) {
            // Never invent a new legacy URL outside the exact sitemap allowlist.
            $legacy = LegacyUrl::where('path',$path)->firstOrFail();
            return $this->view('site.pending',compact('legacy'),['title'=>Str::headline(basename($path)).' | The Luxury Look','noindex'=>true]);
        }
        $founder = $path==='/about' ? FounderProfile::visibleOnSite()->first() : null;
        $founderFilms = $path==='/about' ? StudioVideo::visibleOnSite()->where('video_url','like','%gNeiecujx8Q%')->orderBy('sort_order')->get() : collect();
        return $this->view($path==='/contact'?'site.contact':'site.page',compact('page','founder','founderFilms'),[
            'title'=>$page->meta_title ?: $page->title.' | The Luxury Look',
            'description'=>$page->meta_description ?: $page->intro,
            'image'=>$page->hero_image,
            'noindex'=>!$page->content_verified,
        ]);
    }

    public function contact(Request $request)
    {
        $page = Page::where('path','/contact')->first();
        return $this->view('site.contact',compact('page'),[
            'title'=>'Contact Us - The Luxury Look | Interior Design, Scottsdale, AZ',
            'description'=>'Discuss your interior design, luxury staging, Airbnb styling or corporate branding project with The Luxury Look in Scottsdale.',
        ]);
    }

    public function submitContact(Request $request)
    {
        // Honeypot helps reduce bot submissions without affecting visitors.
        if ($request->filled('website')) return back()->with('success','Thank you. Your inquiry has been received.');
        $data = $request->validate([
            'name'=>['required','string','max:180'],
            'email'=>['required','email','max:255'],
            'phone'=>['nullable','string','max:50'],
            'service'=>['nullable','string','max:120'],
            'property_location'=>['nullable','string','max:255'],
            'project_timing'=>['nullable','string','max:180'],
            'investment_range'=>['nullable','string','max:180'],
            'project_stage'=>['nullable','string','max:120'],
            'staging_occupancy'=>['nullable','string','max:120'],
            'listing_date'=>['nullable','string','max:120'],
            'construction_status'=>['nullable','string','max:120'],
            'property_count'=>['nullable','integer','between:1,999'],
            'message'=>['required','string','min:10','max:5000'],
        ]);
        $details=array_intersect_key($data,array_flip(['property_location','project_timing','investment_range','project_stage','staging_occupancy','listing_date','construction_status','property_count']));
        foreach(array_keys($details) as $key) unset($data[$key]);
        $data['project_details']=$details;
        $inquiry = Inquiry::create($data);
        if (config('mail.default') !== 'log' && config('site.contact_email')) {
            try {
                Mail::raw("New website inquiry #{$inquiry->id}\nName: {$inquiry->name}\nEmail: {$inquiry->email}\nPhone: {$inquiry->phone}\nService: {$inquiry->service}\n\n{$inquiry->message}",
                    fn($message)=>$message->to(config('site.contact_email'))->subject('Website inquiry: '.$inquiry->name));
            } catch (\Throwable $e) { Log::error('Inquiry email failed', ['id'=>$inquiry->id, 'error'=>$e->getMessage()]); }
        }
        return redirect('/contact')->with('success','Thank you for reaching out. We will be in touch soon.');
    }

    public function search(Request $request)
    {
        $term = trim((string) $request->query('q',''));
        $projects = collect(); $posts=collect();
        if (mb_strlen($term)>=2 && mb_strlen($term)<=100) {
            $projects=Project::visibleOnSite()->where(fn($q)=>$q->where('title','like','%'.$term.'%')->orWhere('preview_title','like','%'.$term.'%'))->limit(20)->get();
            $posts=Post::visibleOnSite()->where('title','like','%'.$term.'%')->limit(20)->get();
        }
        return $this->view('site.search',compact('term','projects','posts'),['title'=>'Search | The Luxury Look','noindex'=>true]);
    }

    public function special(Request $request)
    {
        $path='/'.ltrim($request->path(),'/');
        $legacy=LegacyUrl::where('path',$path)->firstOrFail();
        return response($this->view('site.pending',compact('legacy'),['title'=>'Page under migration | The Luxury Look','noindex'=>true]),503)
            ->header('Retry-After','86400');
    }

    public function fallback(Request $request)
    {
        $path='/'.ltrim($request->path(),'/');
        $legacy=LegacyUrl::where('path',$path)->first();
        if ($legacy && $legacy->status==='redirect' && $legacy->redirect_to) {
            // Allow relative internal redirect paths only; avoid open redirects.
            $to=$legacy->redirect_to;
            abort_unless(Str::startsWith($to,'/') && !Str::startsWith($to,'//'),422);
            return redirect($to,301);
        }
        if ($legacy) return $this->page($request);
        abort(404);
    }

    public function sitemap()
    {
        // Only true canonical 200 URLs. Do not publish drafts, retired URLs or redirects.
        $paths=LegacyUrl::where('status','ready')->orderBy('path')->pluck('path')->all();
        // Unapproved pages, including the homepage, stay out until explicitly reviewed.
        $xml=view('site.sitemap',compact('paths'))->render();
        return response($xml)->header('Content-Type','application/xml; charset=UTF-8');
    }

    public function robots()
    {
        $content=config('site.launch_ready')
            ? "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /checkout\nDisallow: /paypal-checkout\nDisallow: /order-confirmation\nDisallow: /search\nSitemap: https://".config('site.canonical_host')."/sitemap.xml\n"
            : "User-agent: *\nDisallow: /\n";
        return response($content)->header('Content-Type','text/plain; charset=UTF-8');
    }
}
