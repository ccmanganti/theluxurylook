<?php
namespace App\Http\Controllers;

use App\Models\{Inquiry, LegacyUrl, Page, PortfolioCategory, Post, Project, Service};
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
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
        return view($template, $data + compact('seo'));
    }

    public function home()
    {
        return $this->view('site.home', [
            'projects' => Project::visibleOnSite()->where('featured', true)->orderBy('sort_order')->take(6)->get(),
            'categories' => PortfolioCategory::visibleOnSite()->orderBy('sort_order')->get(),
            'services' => Service::visibleOnSite()->orderBy('sort_order')->get(),
        ], ['path'=>'/', 'description'=>'The Luxury Look is an award-winning interior design and home staging firm in Scottsdale, Arizona. Explore our projects and services.']);
    }

    public function services()
    {
        return $this->view('site.services', [
            'services'=>Service::visibleOnSite()->orderBy('sort_order')->get(),
        ], ['title'=>'Services - The Luxury Look | Interior Design, Scottsdale, AZ','description'=>'Explore interior design, interior styling, home staging, Airbnb staging, commercial staging and corporate branding services in Scottsdale.']);
    }

    public function service(string $slug)
    {
        $service = Service::visibleOnSite()->where('slug',$slug)->firstOrFail();
        return $this->view('site.service',compact('service'),[
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
            'projects'=>$category->projects()->visibleOnSite()->orderBy('sort_order')->paginate(18),
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
        return $this->view('site.post',compact('post'),[
            'title'=>$post->meta_title ?: $post->title.' | The Luxury Look',
            'description'=>$post->meta_description ?: $post->excerpt,
            'image'=>$post->hero_url,
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
        return $this->view($path==='/contact'?'site.contact':'site.page',compact('page'),[
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
            'message'=>['required','string','min:10','max:5000'],
        ]);
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
            $projects=Project::visibleOnSite()->where('title','like','%'.$term.'%')->limit(20)->get();
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
