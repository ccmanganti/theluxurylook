#!/usr/bin/env python3
"""Render the portfolio-led visual design review for GitHub Pages.

No PHP is executed here. The static review does not handle inquiries, accounts,
payments, or publishing-approved content. Image sources are temporary and labeled.
All review HTML is noindex and robots.txt disallows crawling. Path URLs are the
original Webflow paths wherever present; the /portfolio overview is new/noindex.
"""
from __future__ import annotations
import html
import json
import shutil
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
DEST = ROOT / 'preview-dist'
BASE = '/theluxurylook/'
D = json.loads((ROOT / 'database/data/portfolio-seed.json').read_text(encoding='utf-8'))
CATEGORIES = sorted(D['categories'], key=lambda x:x['sort_order'])
PROJECTS = D['projects']
SERVICES = sorted(json.loads((ROOT / 'database/data/services-seed.json').read_text(encoding='utf-8')),key=lambda x:x['sort_order'])
PAGES = json.loads((ROOT / 'database/data/pages-seed.json').read_text(encoding='utf-8'))
LEGACY = json.loads((ROOT / 'database/data/legacy-sitemap.json').read_text(encoding='utf-8'))
IMAGES = json.loads((ROOT / 'database/data/preview-image-catalog.json').read_text(encoding='utf-8'))
CATEGORY_MAP = {c['slug']:c for c in CATEGORIES}
COUNT = {c['slug']:sum(p['category_slug']==c['slug'] for p in PROJECTS) for c in CATEGORIES}
CATEGORY_SERVICE = {'interior-design':'interior-design-projects',
                    'interior-styling':'interior-styling-projects-the-luxury-look',
                    'luxury-residential-staging':'home-staging-projects-the-luxury-look',
                    'commercial-staging':'commercial-staging-projects-the-luxury-look',
                    'airbnb-staging':'airbnb-staging-the-luxury-look',
                    'corporate-branding':'corporate-brandding-projects-the-luxury-look'}

# Populate visual-only galleries in memory rather than bloating the original
# SEO/URL seed with repeated sample photo URLs. The Filament database receives
# the same eight editable slots from PreviewPhotographySeeder.php.
BY_CATEGORY = {}
for project in PROJECTS:
    key=project.get('category_slug') or 'interior-design-projects'
    BY_CATEGORY.setdefault(key, []).append(project)
for category in CATEGORIES:
    category['preview_hero_image'] = category['hero_image']
for group_slug,group in BY_CATEGORY.items():
    stock=IMAGES['stock'].get(group_slug,IMAGES['stock']['interior-design-projects'])
    for idx,project in enumerate(group):
        others=[p['cover_image'] for p in group if p.get('cover_image') and p['cover_image']!=project.get('cover_image')]
        temp=lambda n:stock[(idx*2+n)%len(stock)]
        neighbor=lambda n:others[(idx+n)%len(others)] if others else temp(n)
        photos=[temp(0),neighbor(1),temp(1),temp(2),neighbor(3),temp(3),neighbor(5),temp(4)]
        project['preview_cover_image']=project.get('cover_image','')
        project['preview_gallery']=[{'url':src,'alt':f'Temporary photograph {i+1} — replace with approved gallery for {project["title"]}'} for i,src in enumerate(photos)]
CATEGORY_IMAGE={c['slug']:c['hero_image'] for c in CATEGORIES}
for service in SERVICES:
    c=CATEGORY_SERVICE[service['slug']]
    service['preview_cover_image']=CATEGORY_IMAGE[c]

def e(v): return html.escape(str(v or ''),quote=True)
def url(path='/'):
    return BASE if path in ('','/') else BASE+path.strip('/')+'/'
def image(src,alt,loading='lazy'):
    return f'<img src="{e(src)}" alt="{e(alt)}" loading="{loading}" decoding="async">'
def a(path,label,cls=''):
    return f'<a href="{url(path)}" class="{cls}">{e(label)}</a>'
def media(src,alt,cls,loading='lazy'):
    return f'<div class="{cls} media-frame">{image(src,alt,loading)}</div>'
def slug_title(s): return s.replace('---',' — ').replace('-',' ').title().replace('Airbnb','Airbnb')
def cover(p): return p.get('preview_cover_image') or p.get('cover_image') or ''
def cat_image(c): return c.get('preview_hero_image') or c.get('hero_image') or ''
def service_image(s): return s.get('preview_cover_image') or s.get('cover_image') or ''
def photo_count(p):return len(p.get('preview_gallery') or [])

def shell(title,body,description='The Luxury Look design review: temporary imagery, real URL structure and editable project gallery layouts.'):
    return f'''<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="theme-color" content="#f3f0ea">
<title>{e(title)} | The Luxury Look — Design Preview</title>
<meta name="robots" content="noindex,nofollow,noarchive"><meta name="description" content="{e(description)}">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link rel="preconnect" href="https://images.unsplash.com"><link rel="preconnect" href="https://cdn.prod.website-files.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Cormorant+Garamond:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{BASE}assets/site.css"><link rel="stylesheet" href="{BASE}assets/editorial.css"><script defer src="{BASE}assets/site.js"></script></head>
<body><a class="skip-link" href="#main">Skip to content</a>
<div class="preview-ribbon" role="note">DESIGN REVIEW <span aria-hidden="true">/</span> IMAGE SETS ARE TEMPORARY &amp; WILL BE REPLACED <span aria-hidden="true">/</span> NOT INDEXED</div>
<header class="masthead" id="top"><div class="masthead-top container-wide"><span>SCOTTSDALE, ARIZONA <i aria-hidden="true"></i> INTERIOR DESIGN &amp; STAGING</span><a href="{url('contact')}">INQUIRE ABOUT A PROJECT <span aria-hidden="true">↗</span></a></div>
<div class="masthead-bottom container-wide"><a href="{BASE}" class="brand" aria-label="The Luxury Look Home"><span class="brand-overline">THE</span><span class="brand-name">LUXURY LOOK</span><span class="brand-subline">DESIGN STUDIO</span></a>
<button class="nav-toggle" type="button" aria-label="Open navigation" aria-expanded="false" aria-controls="primary-nav"><span></span><span></span></button>
<nav id="primary-nav" class="primary-nav" aria-label="Main navigation"><a href="{url('portfolio')}">Portfolio</a><a href="{url('services')}">Services</a><a href="{url('about')}">Studio</a><a href="{url('blog')}">Journal</a><a class="nav-outline" href="{url('contact')}">Let's Talk <span aria-hidden="true">↗</span></a></nav></div></header>
<main id="main">{body}</main>
<footer class="footer-main"><div class="container-wide footer-top"><div><p class="section-kicker section-kicker--light">THE NEXT PROJECT</p><h2>Let's talk about<br><span>your space.</span></h2></div><a href="{url('contact')}" class="footer-action">SEND AN INQUIRY <span aria-hidden="true">↗</span></a></div>
<div class="container-wide footer-columns"><div class="footer-identity"><span class="brand-overline">THE</span><strong>LUXURY LOOK</strong><small>DESIGN STUDIO</small><p>Interior design, home staging and property styling based in Scottsdale, Arizona.</p></div>
<div><h3>Explore</h3><a href="{url('portfolio')}">All Projects</a><a href="{url('services')}">Services</a><a href="{url('about')}">Our Studio</a><a href="{url('blog')}">Journal</a></div>
<div><h3>Visit</h3><p>4343 N Scottsdale Road<br>Suite 150<br>Scottsdale, AZ 85251</p><small>By appointment only</small></div>
<div><h3>Get in touch</h3><a href="mailto:kristine@theluxurylook.com">kristine@theluxurylook.com</a><a href="tel:+16025786707">+1 (602) 578-6707</a><div class="footer-social"><a href="https://www.instagram.com/theluxurylook_/" target="_blank" rel="noopener noreferrer">Instagram ↗</a><a href="https://www.facebook.com/TheLuxuryLookLLC" target="_blank" rel="noopener noreferrer">Facebook ↗</a></div></div></div>
<div class="container-wide footer-bottom"><small>© 2026 THE LUXURY LOOK</small><small>DESIGN &amp; STAGING — SCOTTSDALE, AZ</small><a href="#top">BACK TO TOP ↑</a></div></footer></body></html>'''

def work_card(p):
    c=CATEGORY_MAP.get(p.get('category_slug',''),{})
    return f'''<a class="work-card" data-category-slug="{e(p.get('category_slug',''))}" href="{url('portfolios/'+p['slug'])}" aria-label="View {e(p['title'])} portfolio project">
<figure class="work-card-photo media-frame">{image(cover(p),p['title']+' — temporary portfolio layout photograph')}<span class="work-card-view">VIEW PROJECT <span aria-hidden="true">↗</span></span></figure>
<div class="work-card-caption"><div><span class="caption-discipline">{e(c.get('title','Selected Work'))}</span><h3>{e(p['title'])}</h3></div><span class="caption-arrow" aria-hidden="true">↗</span></div></a>'''

def home():
    selected=['mockingbird-paradise-valley','petersen-residence','old-town-scottsdale-airbnb','desert-vista-luxury-design','gated-annecy','studio-3125','ancala-country-club','mor-furniture-line-launch']
    by_slug={p['slug']:p for p in PROJECTS};features=[by_slug[x] for x in selected if x in by_slug]
    hero=cover(features[0]) if features else IMAGES['hero_home']
    secondary=cat_image(CATEGORY_MAP['interior-styling-projects-the-luxury-look'])
    discipline=''.join(f'''<a href="{url('portfolio/'+c['slug'])}" class="discipline-row"><span class="discipline-number">{i+1:02d}</span><div class="discipline-thumb media-frame">{image(cat_image(c),c['title']+' example photography')}</div><div class="discipline-name"><h3>{e(c['title'])}</h3><span>{e(c['tagline'])}</span></div><span class="discipline-count">{COUNT[c['slug']]:02d} PROJECTS</span><span class="discipline-arrow">↗</span></a>''' for i,c in enumerate(CATEGORIES))
    return f'''<section class="home-hero container-wide"><div class="home-hero-copy"><p class="section-kicker">THE LUXURY LOOK <span>/</span> SCOTTSDALE, AZ</p><h1>Designed<br>to <span>be lived in.</span></h1><p class="home-hero-description">Interior design, home staging, and thoughtful styling for the spaces where life happens.</p><div class="home-hero-actions"><a href="{url('portfolio')}" class="button-ink">Explore Our Portfolio <span>↗</span></a><a href="{url('services')}" class="under-link">Our Services <span>↗</span></a></div><span class="hero-edge-note">EST. 2013 &nbsp; • &nbsp; FOUNDED BY KRISTINE VOWLES</span></div><div class="home-hero-visual media-frame">{image(hero,'Temporary featured photograph for the portfolio review','eager')}<div class="hero-image-index"><span>FEATURED WORK</span><span>01 / 0{len(features)}</span></div></div></section>
<section class="intro-editorial section-space container-wide"><div class="intro-editorial-index"><p class="section-kicker">01 / THE STUDIO</p><div class="intro-rule"></div><span>DESIGN / STAGING / STYLING</span></div><div class="intro-editorial-content"><h2>Spaces with a<br><em>point of view.</em></h2><div class="editorial-subrow"><p>Founded by Kristine Vowles, The Luxury Look works across residential design, home staging and commercial spaces. We bring experience in real estate and design together, with equal attention to how a space looks and how it works.</p><a class="under-link" href="{url('about')}">About the Studio ↗</a></div></div></section>
<section class="homepage-work section-space section-tint"><div class="container-wide"><div class="section-header"><div><p class="section-kicker">02 / SELECTED WORK</p><h2>Inside the portfolio.</h2></div><a class="under-link" href="{url('portfolio')}">View all projects <span>↗</span></a></div><div class="work-grid work-grid--home">{''.join(work_card(p) for p in features)}</div><div class="section-end"><span>{len(PROJECTS)} PROJECTS ACROSS SIX DISCIPLINES</span><a class="link-with-arrow" href="{url('portfolio')}">COMPLETE PORTFOLIO <span>↗</span></a></div></div></section>
<section class="image-interlude"><div class="image-interlude-photo media-frame">{image(secondary,'Temporary interior styling photograph')}</div><div class="image-interlude-copy"><p class="section-kicker section-kicker--light">A CLOSER LOOK</p><h2>Every space<br>has its own<br><em>character.</em></h2><p>Explore our work across interiors, residential and commercial staging, short-term rentals, and brand environments.</p><a class="link-light" href="{url('portfolio')}">EXPLORE PROJECTS <span>↗</span></a></div></section>
<section class="discipline-section section-space container-wide"><div class="section-header"><div><p class="section-kicker">03 / DISCIPLINES</p><h2>What we work on.</h2></div><p class="header-description">Explore the portfolio by project type, from whole-home transformations to individual spaces and experiences.</p></div><div class="discipline-list">{discipline}</div></section>
<section class="process-section section-space"><div class="container-wide"><div class="section-header"><div><p class="section-kicker">04 / WORKING TOGETHER</p><h2>From the first conversation.</h2></div></div><div class="process-grid"><div><span>01</span><h3>Tell us about the space</h3><p>Share the property, your goals, and how you plan to use it.</p></div><div><span>02</span><h3>Find the right service</h3><p>We'll discuss whether your project calls for design, styling, staging, or a combination.</p></div><div><span>03</span><h3>Plan what comes next</h3><p>Connect with our studio to discuss scope, timing, and the next steps.</p></div></div><a href="{url('contact')}" class="button-ink">Start a Project <span>↗</span></a></div></section>'''

def cta():
    return f'<section class="full-bleed-note"><p>YOUR PROJECT COULD BE NEXT</p><h2>Tell us what you\'re planning.</h2><a href="{url("contact")}" class="under-link">CONTACT THE STUDIO ↗</a></section>'

def portfolio_index():
    filters=f'<button class="filter-button is-active" data-portfolio-filter="all" aria-pressed="true">All work <span>{len(PROJECTS)}</span></button>'
    filters+=''.join(f'<button class="filter-button" data-portfolio-filter="{e(c["slug"])}" aria-pressed="false">{e(c["title"])} <span>{COUNT[c["slug"]]}</span></button>' for c in CATEGORIES)
    return f'''<section class="archive-heading container-wide"><div class="archive-heading-left"><p class="section-kicker">THE PORTFOLIO / SELECTED WORK</p><h1>Our projects<span class="period">.</span></h1></div><div class="archive-heading-right"><p>A collection of work across interiors, home staging, styling, hospitality and branded spaces.</p><span class="archive-counter">{len(PROJECTS):02d} PROJECTS &nbsp; / &nbsp; {len(CATEGORIES)} CATEGORIES</span></div></section>
<section class="archive-section container-wide"><div class="filter-bar" aria-label="Filter projects by discipline">{filters}</div><div class="work-grid work-grid--archive">{''.join(work_card(p) for p in PROJECTS)}</div><p class="empty-filter" hidden>No projects in this discipline yet.</p></section>{cta()}'''

def category_page(c):
    p=[p for p in PROJECTS if p['category_slug']==c['slug']]
    tabs=f'<a href="{url("portfolio")}">All Projects</a>'+''.join(f'<a href="{url("portfolio/"+x["slug"])}" {"aria-current=page" if x["slug"]==c["slug"] else ""}>{e(x["title"])}</a>' for x in CATEGORIES)
    return f'''<section class="category-lead container-wide"><div class="category-lead-text"><p class="section-kicker"><a href="{url('portfolio')}">PORTFOLIO</a> / {e(c['title'].upper())}</p><h1>{e(c['title'])}<span class="period">.</span></h1><p>{e(c['tagline'])}</p><span class="category-number">{len(p):02d} PROJECTS IN THIS COLLECTION</span></div><div class="category-lead-image media-frame">{image(cat_image(c),c['title']+' temporary portfolio image','eager')}</div></section>
<section class="archive-section container-wide"><div class="category-tabs"><span>EXPLORE BY DISCIPLINE</span><div>{tabs}</div></div><div class="category-list-intro"><h2>The collection</h2><p>{e(c['description'])}</p></div><div class="work-grid work-grid--archive">{''.join(work_card(v) for v in p)}</div></section>{cta()}'''

def gallery_figure(photo,i,total):
    source=photo['url'] if isinstance(photo,dict) else photo
    alt=photo.get('alt','Temporary project image') if isinstance(photo,dict) else 'Temporary project image'
    return f'''<figure class="case-gallery-item case-gallery-item--{i%6+1} media-frame"><button type="button" class="gallery-open" data-gallery-image="{e(source)}" data-gallery-alt="{e(alt)}" aria-label="Enlarge gallery image {i+1}">{image(source,alt)}<span class="gallery-expand">VIEW IMAGE ↗</span></button><figcaption><span>{i+1:02d} / {total:02d}</span><span>LAYOUT IMAGE</span></figcaption></figure>'''

def lightbox():
    return '<div class="lightbox" role="dialog" aria-modal="true" aria-label="Photo gallery" aria-hidden="true" hidden><button class="lightbox-close" type="button" aria-label="Close photograph">✕</button><button class="lightbox-prev" type="button" aria-label="Previous photograph">←</button><img class="lightbox-image" alt=""><button class="lightbox-next" type="button" aria-label="Next photograph">→</button><span class="lightbox-count"></span></div>'

def project_page(p):
    c=CATEGORY_MAP.get(p.get('category_slug'),{})
    rel=[v for v in PROJECTS if v['slug']!=p['slug'] and v['category_slug']==p['category_slug']][:3]
    pics=p.get('preview_gallery',[])
    bread=f'<a href="{url("portfolio")}">PORTFOLIO</a> / '+(f'<a href="{url("portfolio/"+c["slug"])}">{e(c["title"].upper())}</a>' if c else 'SELECTED WORK')
    details=f'<div><dt>Discipline</dt><dd>{e(c.get("title","Portfolio"))}</dd></div>'
    if p.get('location'):details+=f'<div><dt>Location</dt><dd>{e(p["location"])}</dd></div>'
    details+='<div><dt>Photography</dt><dd>Layout references / temporary</dd></div>'
    intro=p.get('summary','')
    if intro.lower().startswith('an interior design project') or intro.lower().startswith('a home staging project'):
        intro=f'This {e(c.get("title","portfolio").lower())} project is part of The Luxury Look archive. Original project details are being verified for the new website.'
    return f'''<article class="project-case"><div class="case-heading container-wide"><p class="section-kicker">{bread}</p><div class="case-heading-row"><h1>{e(p['title'])}<span class="period">.</span></h1><span class="case-type">THE LUXURY LOOK<br>PROJECT ARCHIVE</span></div></div>
<div class="case-cover media-frame">{image(cover(p),'Temporary cover photograph for '+p['title'],'eager')}<div class="case-cover-label"><span>{e(p['title'])}</span><span>01 / PROJECT VIEW</span></div></div>
<section class="case-context section-space container-wide"><div class="case-context-aside"><span>PROJECT DETAILS</span><dl>{details}</dl><a href="{url('contact')}" class="under-link">Discuss a similar project ↗</a></div><div class="case-context-body"><p class="section-kicker">A LOOK AT THE SPACE</p><h2>{e(p['title'])}</h2><p>{e(intro)}</p><div class="photo-disclaimer">This is a layout preview. The imagery below is a temporary set of visual references, not a verified photographic record of this project. Final project photography can be replaced in the Filament admin.</div></div></section>
<section class="case-gallery container-wide" aria-label="Project photography"><div class="case-gallery-heading"><span class="section-kicker">PROJECT GALLERY</span><span>{len(pics):02d} VIEWS</span></div><div class="case-gallery-grid">{''.join(gallery_figure(pic,i,len(pics)) for i,pic in enumerate(pics))}</div></section></article>
<section class="related-projects section-space"><div class="container-wide"><div class="section-header"><div><p class="section-kicker">CONTINUE EXPLORING</p><h2>More from the archive.</h2></div><a href="{url('portfolio')}" class="under-link">All projects ↗</a></div><div class="work-grid work-grid--related">{''.join(work_card(item) for item in rel)}</div></div></section>{lightbox()}'''

def services_index():
    items=''.join(f'''<article class="service-block"><a href="{url('services/'+s['slug'])}" class="service-block-image media-frame">{image(service_image(s),s['title']+' temporary photography')}</a><div class="service-block-copy"><span class="section-kicker">SERVICE {i+1:02d}</span><h2>{e(s['title'])}</h2><p>{e(s.get('summary'))}</p><a href="{url('services/'+s['slug'])}" class="under-link">Explore this service <span>↗</span></a></div></article>''' for i,s in enumerate(SERVICES))
    return f'''<section class="archive-heading container-wide"><div class="archive-heading-left"><p class="section-kicker">WHAT WE DO</p><h1>Our services<span class="period">.</span></h1></div><div class="archive-heading-right"><p>From a complete interior to the details that make a room work, explore how The Luxury Look can support your project.</p><span class="archive-counter">DESIGN / STYLING / STAGING</span></div></section><section class="service-archive container-wide">{items}</section>{cta()}'''

def service_page(s):
    rel=[p for p in PROJECTS if p['category_slug']==CATEGORY_SERVICE.get(s['slug'],'')][:3]
    related=f'''<section class="related-projects section-space"><div class="container-wide"><div class="section-header"><div><p class="section-kicker">RELATED PROJECTS</p><h2>See the work.</h2></div><a href="{url('portfolio')}" class="under-link">Explore the archive ↗</a></div><div class="work-grid work-grid--related">{''.join(work_card(p) for p in rel)}</div></div></section>''' if rel else ''
    body='<p>'+e(s.get('body','')).replace('\n\n','</p><p>')+'</p>'
    return f'''<section class="service-detail-head container-wide"><div><p class="section-kicker"><a href="{url('services')}">SERVICES</a> / {e(s['title'].upper())}</p><h1>{e(s['title'])}<span class="period">.</span></h1><p>{e(s['summary'])}</p><a class="button-ink" href="{url('contact')}">Discuss a project <span>↗</span></a></div><div class="service-detail-photo media-frame">{image(service_image(s),s['title']+' temporary image','eager')}</div></section>
<section class="container-wide service-detail-copy section-space"><span class="section-kicker">THE SERVICE</span><div><h2>Thoughtful work,<br>from the outset.</h2><div class="prose">{body}</div><a class="under-link" href="{url('contact')}">Ask us about {e(s['title'])} ↗</a></div></section>{related}'''

def about_page(page):
    text=page.get('body','').split('\n\n')
    body=''.join(f'<p>{e(part)}</p>' for part in text if part)
    return f'''<header class="page-editorial-header container-wide"><p class="section-kicker">{e(page.get('eyebrow','OUR STUDIO').upper())}</p><h1>{e(page['title'])}<span class="period">.</span></h1><p>{e(page.get('intro'))}</p></header><section class="page-editorial-body section-space container-wide"><span class="section-kicker">DESIGN STUDIO / SCOTTSDALE</span><div><div class="prose">{body}</div><a class="under-link" href="{url('contact')}">Get in touch ↗</a></div></section>'''

def journal():
    posts=[x for x in LEGACY if x['path'].startswith('/blog/')]
    items=''.join(f'''<a href="{url(x['path'])}" class="journal-card"><span>THE LUXURY LOOK JOURNAL</span><h2>{e(slug_title(x['path'].split('/')[-1]))}</h2><p>This story is retained in the original site archive; its full text will be transferred before launch.</p><span class="under-link">Read article ↗</span></a>''' for x in posts)
    return f'''<section class="archive-heading container-wide"><div class="archive-heading-left"><p class="section-kicker">IDEAS / STORIES / DESIGN</p><h1>The journal<span class="period">.</span></h1></div><div class="archive-heading-right"><p>Notes and ideas on interiors, styling, home staging and the spaces we inhabit.</p></div></section><section class="container-wide section-space"><div class="journal-grid">{items}</div></section>'''

def journal_post(x):
    title=slug_title(x['path'].split('/')[-1]);return f'''<header class="page-editorial-header container-wide"><p class="section-kicker">THE LUXURY LOOK JOURNAL</p><h1>{e(title)}<span class="period">.</span></h1><p>Original editorial copy is being migrated from the existing website.</p></header><section class="page-editorial-body section-space container-wide"><span class="section-kicker">NOTES FROM THE STUDIO</span><div class="prose"><p>The full story is not yet available in this static review. We are retaining the URL while the original article text and accompanying images are checked.</p><a href="{url('blog')}" class="under-link">Back to journal ↗</a></div></section>'''

def contact():
    return f'''<header class="page-editorial-header container-wide"><p class="section-kicker">CONTACT THE STUDIO</p><h1>Start a conversation<span class="period">.</span></h1><p>Planning a renovation, staging a property or refreshing a space? Tell us more about your project.</p></header>
<section class="contact-grid container-wide"><div class="contact-side"><p class="section-kicker">THE LUXURY LOOK</p><h2>We'd love to hear from you.</h2><p>Consultations are available by appointment. Get in touch to discuss your goals, timeline and the right service for your property.</p><h3>VISIT</h3><span>4343 N Scottsdale Road, Suite 150<br>Scottsdale, AZ 85251</span><h3>CALL</h3><a href="tel:+16025786707">+1 (602) 578-6707</a><h3>EMAIL</h3><a href="mailto:kristine@theluxurylook.com">kristine@theluxurylook.com</a></div><div class="contact-form-wrapper"><p class="section-kicker">PROJECT INQUIRY</p><h2 style="font:400 58px var(--display);margin-bottom:20px">Tell us about your project.</h2><div class="preview-contact-cta">The temporary visual preview does not submit contact forms. You can still reach the studio directly at <a href="mailto:kristine@theluxurylook.com">kristine@theluxurylook.com</a> or <a href="tel:+16025786707">+1 (602) 578-6707</a>. The actual Laravel application includes an inquiry form that will be tested with its mail configuration before launch.</div><a class="button-ink" href="mailto:kristine@theluxurylook.com?subject=Interior%20Design%20Inquiry">Email the studio <span>↗</span></a></div></section>'''

def write(path,content):
    dest=DEST/path;dest.parent.mkdir(parents=True,exist_ok=True);dest.write_text(content,encoding='utf-8')

def main():
    if DEST.exists():shutil.rmtree(DEST)
    shutil.copytree(ROOT/'public/assets',DEST/'assets')
    write('.nojekyll','')
    write('robots.txt','User-agent: *\nDisallow: /\n')
    write('index.html',shell('Home',home()))
    write('portfolio/index.html',shell('Selected Work',portfolio_index()))
    for c in CATEGORIES:write('portfolio/'+c['slug']+'/index.html',shell(c['title']+' Projects',category_page(c),c['description']))
    for p in PROJECTS:write('portfolios/'+p['slug']+'/index.html',shell(p['title'],project_page(p)))
    write('services/index.html',shell('Our Services',services_index()))
    for s in SERVICES:write('services/'+s['slug']+'/index.html',shell(s['title']+' Service',service_page(s),s['summary']))
    for page in PAGES:
        if page['path'] in ('/contact',):continue
        write(page['path'].strip('/')+'/index.html',shell(page['title'],about_page(page),page.get('intro','')))
    write('contact/index.html',shell('Contact the Studio',contact()))
    write('blog/index.html',shell('Journal',journal()))
    for x in LEGACY:
        if x['path'].startswith('/blog/'):write(x['path'].strip('/')+'/index.html',shell(slug_title(x['path'].split('/')[-1]),journal_post(x)))
    write('404.html',shell('Page not found',f'<section class="page-editorial-header container-wide"><p class="section-kicker">NOT FOUND</p><h1>This page has moved<span class="period">.</span></h1><p>Some original content is still being migrated in this temporary preview.</p><a class="under-link" href="{url('portfolio')}">Browse the portfolio ↗</a></section>'))
    print(f'Generated {len(list(DEST.rglob("index.html")))} responsive portfolio-led preview pages.')
    print(f'Includes {len(PROJECTS)} project detail pages, {sum(photo_count(p) for p in PROJECTS)} replaceable gallery photo slots, {len(CATEGORIES)} portfolio category pages and {len(SERVICES)} service detail pages.')


# Internal pages share an editorial design system with the Laravel templates.
# Keep all existing legacy paths while giving individual pages conversion-focused layouts.
import editorial_preview as _editorial
EDITORIAL = json.loads((ROOT / 'database/data/editorial-content.json').read_text(encoding='utf-8'))
def portfolio_index(): return _editorial.portfolio_index(globals())
def category_page(c): return _editorial.category_page(c,globals())
def project_page(p): return _editorial.project_page(p,globals())
def services_index(): return _editorial.services_index(globals())
def service_page(s): return _editorial.service_page(s,globals())
def about_page(p): return _editorial.about_page(p,globals())
def journal(): return _editorial.journal(globals())
def journal_post(x): return _editorial.journal_post(x,globals())
def contact(): return _editorial.contact(globals())

if __name__=='__main__':main()
