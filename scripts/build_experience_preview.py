#!/usr/bin/env python3
"""Generate a complete, noindex visual review of the new Laravel design.

Uses the same project and offering seeds as Laravel. Never claims that temporary
photographs depict a particular named project. Never activates checkout/forms.
GitHub Pages remains a static review; Filament requires PHP hosting.
"""
from __future__ import annotations
from pathlib import Path
import html, json, shutil, re

ROOT=Path(__file__).resolve().parents[1]
DEST=ROOT/'preview-dist'
BASE='/theluxurylook/'
load=lambda filename:json.loads((ROOT/'database/data'/filename).read_text('utf-8'))
PORT=load('portfolio-seed.json')
PROJECTS=PORT['projects'];CATEGORIES=PORT['categories'];SERVICES=load('services-seed.json')
OFFERINGS=load('experience-offerings.json');PAGES=load('pages-seed.json');LEGACY=load('legacy-sitemap.json');VIDEOS=load('studio-videos.json')
CATS={c['slug']:c for c in CATEGORIES}
EDU=[o for o in OFFERINGS if o['kind']=='education'];DALLAS=[o for o in OFFERINGS if o['kind']=='dallas'];BUYING=[o for o in OFFERINGS if o['kind']=='buying-trip']
STOCK=[
'https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=1350&q=78',
'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1350&q=78',
'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1350&q=78',
'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1350&q=78',
'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1350&q=78',
'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1350&q=78',
'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1350&q=78',
'https://images.unsplash.com/photo-1600566753051-f0b89df2dd90?auto=format&fit=crop&w=1350&q=78'
]

def E(v):return html.escape(str(v or ''),quote=True)
def U(p=''):
    return BASE+(str(p).strip('/')+'/' if str(p).strip('/') else '')
def A(p,label,cls='x-underlink'):
    return f'<a class="{cls}" href="{U(p)}">{E(label)} ↗</a>'
def media(src,alt='',kind='landscape',priority=False):
    return (f'<div class="x-media x-media--{kind}"><img src="{E(src)}" alt="{E(alt)}" '
            f'width="1200" height="900" decoding="async" loading="{ "eager" if priority else "lazy" }"></div>')
def photourl(p):return p.get('preview_cover_image') or p.get('cover_image') or STOCK[0]
def title(p):return p.get('preview_title') or p.get('title') or 'Portfolio Project'
def pill(p,label,light=False):return f'<a class="x-pill{" x-pill--light" if light else ""}" href="{U(p)}">{E(label)} <span>↗</span></a>'
def h2(s):return f'<h2>{s}</h2>'
def eyebrow(s):return f'<span class="x-eyebrow">{E(s)}</span>'
def actions(p,label):return pill(p,label)

def video_frame():
    """Three stationary stills, opacity fading only. NO MP4/YouTube autoplay or zoom."""
    poster=U('media/atelier-motion-poster.jpg').rstrip('/')
    one=STOCK[1];two=STOCK[3]
    return (f'<div class="x-ambient x-ambient--still-fade" data-ambient-container>'
            f'<img class="x-ambient-poster" src="{poster}" alt="" width="960" height="661">'
            f'<img class="x-ambient-fade x-ambient-fade--1" src="{E(one)}" alt="" width="1500" height="1000" loading="lazy">'
            f'<img class="x-ambient-fade x-ambient-fade--2" src="{E(two)}" alt="" width="1500" height="1000" loading="lazy">'
            f'<div class="x-ambient-tone" aria-hidden="true"></div></div>')

def film_button(video, extra=''):
    """Source is The Luxury Look's existing public video embed; click activates iframe."""
    if not video:return ''
    title=E(video['title']);url=E(video['youtube_url'])
    return (f'<button type="button" class="x-film-cta {extra}" data-youtube-url="{url}" '
            f'data-video-title="{title}" aria-label="Play video {title}">'
            '<span class="x-play-icon">▶</span> Watch the Studio Film</button>')

def film_feature():
    if not VIDEOS:return ''
    v=VIDEOS[0]
    if not v.get('is_published',False):return ''
    img=E(v['poster_url']);title=E(v['title'])
    return (f'<section class="x-film-feature x-wrap" aria-label="Studio film">'
            f'<div class="x-film-feature__copy">{eyebrow("AN INSIDE LOOK / THE STUDIO")}<h2>See the work<br><em>in motion.</em></h2>'
            '<p>Watch the studio film featured on The Luxury Look’s original Services page. The player loads only after you select Play.</p>'
            '<a class="x-underlink" target="_blank" rel="noopener noreferrer" href="https://www.youtube.com/channel/UCqPsB5QK-XuPWmksTDGoDrQ">Explore the YouTube channel ↗</a></div>'
            f'<div class="x-film-feature__visual"><img src="{img}" loading="lazy" width="1280" height="720" alt="{title} video thumbnail">'
            f'{film_button(v)}</div></section>')

def buying_trips():
    cards=''.join('<article>'+eyebrow(E(o['audience']))+f'<h3>{E(o["title"])}</h3><p>{E(o["summary"])}</p>'+
                  A('buying-trips/'+o['slug'],'Discuss this approach')+'</article>' for o in BUYING)
    return (f'<section class="x-buying-hero x-wrap"><div>{eyebrow("THE LUXURY LOOK / BUYING TRIPS")}'
            '<h1>Travel with<br><em>a purpose.</em><br>Return with a point of view.</h1>'
            '<p>Design-led buying journeys, from Dallas Market to other sourcing experiences shaped around your project. Dallas is one destination within a wider Buying Trips offering.</p>'
            f'<div class="x-actions">{pill("contact","Discuss a Buying Trip")}<a class="x-underlink" href="#experiences">Explore the experiences ↓</a></div>'
            '<p class="x-preview-disclaimer">Destinations, dates, availability, market access and fees are confirmed individually.</p></div>'
            f'<div class="x-buying-hero__art">{video_frame()}</div></section>'
            f'<section class="x-section x-wrap" id="experiences"><div class="x-heading"><div>{eyebrow("01 / DESTINATION & DISCOVERY")}'
            '<h2>Buying with<br><em>intention.</em></h2></div><p>Choose where to begin.</p></div><div class="x-buying-paths">'
            f'<article class="x-buying-path"><img src="{E(STOCK[1])}" alt="Illustrative furniture sourcing" loading="lazy" width="1200" height="900">'
            f'{eyebrow("FEATURED MARKET / DALLAS")}<h2>Dallas Market<br><em>Experiences</em></h2>'
            f'<p>Dallas is one market destination in the broader buying trips collection.</p><a href="{U("buying-trips/dallas-market")}">Explore Dallas Market ↗</a></article>'
            f'<article class="x-buying-path"><img src="{E(STOCK[5])}" alt="Illustrative interior sourcing" loading="lazy" width="1200" height="900">'
            f'{eyebrow("BESPOKE / OTHER DESTINATIONS")}<h2>Beyond<br><em>the Market</em></h2>'
            f'<p>Ask the studio about other markets and possible design journeys shaped around your goals. No fixed itinerary is promised.</p><a href="{U("contact")}">Discuss other destinations ↗</a></article>'
            f'</div></section><section class="x-section x-wrap"><div class="x-heading"><div>{eyebrow("02 / WAYS TO WORK TOGETHER")}'
            f'<h2>Choose your<br><em>approach.</em></h2></div></div><div class="x-buying-grid">{cards}</div></section>'
            +row_cta('Bring the brief.<br><em>Find the direction.</em>','The journey begins with what you want to source and the places you are considering.','Discuss Buying Trips'))

def shell(title_text,body,description='The Luxury Look — portfolio-led design review with temporary photography and preserved legacy routes.'):
    nav=[('design','Design'),('portfolio','Portfolio'),('builders-and-agents','For Builders'),('buying-trips','Buying Trips'),('education','Education'),('collection','Collection'),('blog','Journal')]
    links=''.join(f'<a href="{U(path)}">{label}</a>' for path,label in nav)
    return f'''<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="theme-color" content="#eceae5"><title>{E(title_text)} | The Luxury Look — Design Review</title><meta name="description" content="{E(description)}"><meta name="robots" content="noindex,nofollow,noarchive"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet"><link rel="stylesheet" href="{U('assets/site.css').rstrip('/')}"><link rel="stylesheet" href="{U('assets/editorial.css').rstrip('/')}"><link rel="stylesheet" href="{U('assets/revamp.css').rstrip('/')}"><link rel="stylesheet" href="{U('assets/experience.css').rstrip('/')}"><link rel="stylesheet" href="{U('assets/media-stable.css').rstrip('/')}?v=20261124-stationary-v3"><script defer src="{U('assets/site.js').rstrip('/')}"></script><script defer src="{U('assets/revamp.js').rstrip('/')}"></script><script defer src="{U('assets/experience.js').rstrip('/')}"></script><script defer src="{U('assets/media-stable.js').rstrip('/')}?v=20261124-stationary-v3"></script></head>
<body class="x-site"><a class="skip-link" href="#main">Skip to content</a><div class="preview-ribbon">PRIVATE DESIGN REVIEW <span>/</span> TEMPORARY IMAGES &amp; VIDEO <span>/</span> NOT INDEXED</div>
<header class="x-header masthead" id="top"><div class="x-topline x-wrap"><span>THE LUXURY LOOK &nbsp; / &nbsp; INTERIOR DESIGN HOUSE</span><a href="{U('contact')}">PROJECT INQUIRIES ↗</a></div><div class="x-nav-shell x-wrap"><a class="x-wordmark" href="{U()}"><span>THE</span><b>LUXURY LOOK</b><small>DESIGN HOUSE</small></a><button class="nav-toggle x-menu-toggle" type="button" aria-label="Open navigation" aria-controls="primary-nav" aria-expanded="false"><span></span><span></span></button><nav id="primary-nav" class="primary-nav x-nav" aria-label="Primary navigation">{links}<details class="x-more"><summary>More</summary><div class="x-more-panel"><a href="{U('about')}">The Studio</a><a href="{U('curated-circle')}">The Curated Circle</a><a href="{U('awards')}">Recognition</a></div></details><a class="x-nav-cta" href="{U('contact')}">Begin Your Project <span>↗</span></a></nav></div></header>
<main id="main">{body}</main><footer class="x-footer"><div class="x-footer-top x-wrap">{eyebrow('A NEW CHAPTER STARTS HERE')}<div class="x-footer-hero"><h2>Ready for a home that<br><em>feels like yours?</em></h2>{pill('contact','Begin Your Project',True)}</div></div><div class="x-footer-bottom x-wrap"><div><a class="x-footer-logo" href="{U()}">THE LUXURY LOOK</a><p>Design with intention. Furnished for living.</p></div><nav aria-label="Footer"><a href="{U('portfolio')}">Portfolio</a><a href="{U('buying-trips')}">Buying Trips</a><a href="{U('education')}">Education</a><a href="{U('contact')}">Work with us</a></nav><div><p>4343 N Scottsdale Rd, Suite 150<br>Scottsdale, AZ 85251</p><a href="mailto:kristine@theluxurylook.com">kristine@theluxurylook.com</a><a href="tel:+16025786707">+1 (602) 578-6707</a></div></div><div class="x-footer-legal x-wrap"><small>© 2026 The Luxury Look</small><small>Image and moving-media placeholders in this private review are illustrative</small><a href="#top">Back to top ↑</a></div></footer></body></html>'''

def card(p,i=0):
    cls=('wide' if i%6==0 else ('portrait' if i%3==1 else 'landscape'))
    category=CATS.get(p.get('category_slug'),{}).get('title','Selected Work')
    return f'<a class="x-project-card x-project-card--{cls}" data-category-slug="{E(p.get("category_slug"))}" href="{U("portfolios/"+p["slug"])}" aria-label="Explore {E(title(p))}"><figure class="x-project-media x-media x-media--{cls}"><img src="{E(photourl(p))}" alt="Temporary layout photography for {E(title(p))}" width="960" height="720" loading="lazy" decoding="async"><span class="x-project-open">VIEW CASE STUDY ↗</span></figure><div class="x-project-meta"><div><span>{E(category)}</span><h3>{E(title(p))}</h3></div><span aria-hidden="true">↗</span></div></a>'

def mosaic(projects,fmt='archive',skip=0):
    return f'<div class="x-mosaic x-mosaic--{fmt}">'+''.join(f'<div class="x-mosaic-cell x-mosaic-cell--{i%6}" data-project-wrapper>{card(p,i)}</div>' for i,p in enumerate(projects[skip:]))+'</div>'
def row_cta(title='Inspired by what you see?',desc='Tell us about the property, the feeling you want to create and where you are in the process.',label='Begin Your Project'):
    return f'<section class="x-next-cta x-wrap x-section"><div>{eyebrow("YOUR NEXT CHAPTER")}{h2(title)}</div><div><p>{E(desc)}</p>{pill("contact",label)}</div></section>'
def home():
    featured=[p for p in PROJECTS if p.get('featured')][:6] or PROJECTS[:6]
    cover=photourl(featured[0]);cat=photourl(featured[1])
    return f'''<section class="x-home-hero x-wrap"><div class="x-home-copy">{eyebrow('THE LUXURY LOOK / DESIGN HOUSE')}<h1>Designed for<br><em>the way luxury</em><br>is lived.</h1><p class="x-lead">Distinctive interiors for primary residences, second homes and homes with a story to tell. Guided by Kristine Vowles and a considered point of view.</p><div class="x-actions">{pill('contact','Begin Your Project')}{A('portfolio','Explore the work')}</div><span class="x-home-index">INTERIORS, FURNISHINGS &amp; MARKET-READY SPACES</span></div><div class="x-home-frame">{video_frame()}{film_button(VIDEOS[0] if VIDEOS else None)}<div class="x-film-caption">A STUDY IN CALM, COLLECTED LIVING <span>01 / 04</span></div></div></section>
<section class="x-introduction x-wrap x-section"><div>{eyebrow('01 / AN APPROACH')}<span class="x-rule"></span></div><div class="x-reveal">{h2('Beautiful is the beginning.<br><em>Feeling at home is the point.</em>')}<div class="x-intro-bottom"><p>The most compelling spaces balance proportion, material and personality. Every vision begins with how the space needs to live.</p>{A('design','Meet our design approach')}</div></div></section>
<section class="x-selected x-section"><div class="x-wrap"><div class="x-heading"><div>{eyebrow('02 / THE PORTFOLIO')}{h2('Work worth<br><em>exploring.</em>')}</div><p>Each room is different. Each decision is intentional. Step inside the projects.</p></div>{mosaic(featured,'home')}<div class="x-selection-end"><span>HOMES / TRANSFORMATIONS / MARKET-READY SPACES</span>{A('portfolio','Discover the complete portfolio')}</div></div></section>
<section class="x-dark-story"><div class="x-story-img x-media x-media--cinema"><img src="{E(cat)}" alt="Interior photography temporary visual" loading="lazy" width="1200" height="900"></div><div class="x-story-text x-reveal">{eyebrow('03 / THE DESIGN HOUSE')}{h2('Experience matters.<br><em>So does every detail.</em>')}<p>Interior design meets practical experience in real estate, renovations and furnishings. Decisions made for how a home is enjoyed, not simply a passing trend.</p><a class="x-underlink x-underlink--light" href="{U('about')}">Meet Kristine &amp; the studio ↗</a></div></section>
<section class="x-pathways x-wrap x-section"><div class="x-heading"><div>{eyebrow('04 / FIND YOUR PATH')}{h2('What brings<br><em>you here?</em>')}</div></div><div class="x-pathway-grid"><a class="x-pathway" href="{U('design')}"><span>01 / FOR HOMEOWNERS</span><h3>Design a home<br>to call your own.</h3><p>From concept and renovation to furnishings.</p><b>EXPLORE DESIGN ↗</b></a><a class="x-pathway x-pathway--accent" href="{U('builders-and-agents')}"><span>02 / BUILDERS &amp; AGENTS</span><h3>Make the right<br>first impression.</h3><p>Luxury specifications and market-ready staging.</p><b>VIEW MARKET-READY ↗</b></a><a class="x-pathway" href="{U('collection')}"><span>03 / FURNISHINGS &amp; MORE</span><h3>Furnish it<br>beautifully.</h3><p>Considered furniture and housewares.</p><b>THE COLLECTION ↗</b></a></div></section>
<section class="x-dallas-teaser x-section"><div class="x-wrap x-dallas-teaser-inner">{media(U('media/atelier-motion-poster.jpg').rstrip('/'),'Temporary Dallas Market illustration','portrait')}<div class="x-dallas-teaser-copy x-reveal">{eyebrow('05 / BUYING TRIPS & MARKET EXPERIENCES')}{h2('A more considered<br><em>way to source.</em>')}<p>Dallas Market is one destination in a broader collection of buying trips and custom sourcing journeys.</p>{pill('buying-trips','Explore Buying Trips')}<small>DATES, SCOPE &amp; PRICING CONFIRMED BY INQUIRY.</small></div></div></section>
<section class="x-education-teaser x-wrap x-section"><div>{eyebrow('06 / EDUCATION &amp; WORKSHOPS')}{h2('Knowledge should<br><em>be shared.</em>')}</div><div class="x-education-teaser-right"><p>Learning in interiors, home staging, business strategy and property presentation, informed by Kristine's real-world experience.</p>{A('education','Explore education')}</div></section>{film_feature()}{row_cta('Your home deserves<br><em>a point of view.</em>','Share what you are imagining. A compelling space starts with the right conversation.')}'''

def portfolio():
    counts={c['slug']:sum(p['category_slug']==c['slug'] for p in PROJECTS) for c in CATEGORIES}
    buttons=f'<button class="filter-button is-active" data-portfolio-filter="all" aria-pressed="true">All work <span>{len(PROJECTS)}</span></button>'
    buttons+=''.join(f'<button class="filter-button" data-portfolio-filter="{E(c["slug"])}" aria-pressed="false">{E(c["title"])} <span>{counts[c["slug"]]}</span></button>' for c in CATEGORIES)
    cover=photourl(PROJECTS[0])
    return f'''<section class="x-portfolio-lead x-wrap"><div>{eyebrow('THE LUXURY LOOK / THE PORTFOLIO')}<h1>Not just rooms.<br><em>Entire stories.</em></h1><p>An edited archive of interiors, staging and spaces shaped for living. Look closer at the details.</p><a class="x-underlink" href="#browse">Browse the complete portfolio ↓</a></div><div class="x-portfolio-cover x-media x-media--portrait"><img src="{E(cover)}" alt="Temporary portfolio cover" loading="eager" width="900" height="1200"><span>SELECTED WORK / {len(PROJECTS)} PROJECTS</span></div></section><section class="x-portfolio-search x-section" id="browse"><div class="x-wrap"><div class="x-heading"><div>{eyebrow('01 / THE COLLECTION')}{h2('Find your<br><em>inspiration.</em>')}</div><p>Explore by discipline. Open any project to see its visual story.</p></div><div class="x-filter-rail" role="group" aria-label="Filter portfolio projects">{buttons}</div>{mosaic(PROJECTS)}<p class="empty-filter" hidden>No projects in this discipline yet.</p></div></section>{row_cta('Inspired by something<br><em>you’ve seen?</em>')}'''

def category(c):
    group=[p for p in PROJECTS if p.get('category_slug')==c['slug']]
    feature=group[0] if group else None
    featured=''
    if feature:
        featured=f'<section class="x-category-feature x-wrap"><div class="x-category-feature-img x-media x-media--landscape"><a href="{U("portfolios/"+feature["slug"])}"><img src="{E(photourl(feature))}" alt="{E(title(feature))}" width="1100" height="780"></a></div><div>{eyebrow("FEATURED FROM THIS COLLECTION")}{h2(E(title(feature)))}<p>{E(feature.get("summary"))}</p>{A("portfolios/"+feature["slug"],"Explore the case study")}</div></section>'
    image=c.get('hero_image') or photourl(feature) if feature else STOCK[0]
    return f'<section class="x-category-hero"><div class="x-category-hero-photo x-media x-media--cinema"><img src="{E(image)}" alt="Temporary category photography" width="1440" height="800"></div><div class="x-category-hero-title x-wrap">{eyebrow("PORTFOLIO / "+c["title"])}<h1>{E(c["title"])}</h1><p>{E(c.get("tagline"))}</p><a class="x-pill x-pill--light" href="#gallery">See the work <span>↓</span></a></div></section><section class="x-category-manifesto x-wrap x-section">{eyebrow("A COLLECTION WITH CHARACTER")}<div>{h2("Spaces with<br><em>their own voice.</em>")}<p>{E(c.get("description"))}</p>{A("contact","Discuss this specialty")}</div></section>'+featured+f'<section id="gallery" class="x-category-archive x-wrap x-section"><div class="x-heading"><div>{eyebrow("THE SELECTED ARCHIVE")}{h2("See every<br><em>perspective.</em>")}</div><span>{len(group)} WORKS</span></div>{mosaic(group,"category",skip=1)}</section>'+row_cta('A fresh point<br><em>of view.</em>')

def project(p):
    c=CATS.get(p.get('category_slug'),{})
    gal=[]
    for i in range(8):
        item=STOCK[(i+len(p['slug']))%len(STOCK)] if i%2==0 else photourl(PROJECTS[(i*3+len(p['slug']))%len(PROJECTS)])
        gal.append(item)
    figures=''.join(f'<figure class="x-gallery-photo x-gallery-photo--{i%8} x-media"><button type="button" class="gallery-open" data-gallery-image="{E(src)}" data-gallery-alt="Temporary photograph {i+1} for {E(title(p))}" aria-label="Enlarge photograph {i+1}"><img src="{E(src)}" width="960" height="720" loading="lazy" alt="Temporary photograph {i+1} for {E(title(p))}"><span class="x-gallery-view">EXPAND IMAGE ↗</span></button></figure>' for i,src in enumerate(gal))
    matches=[z for z in PROJECTS if z!=p and z.get('category_slug')==p.get('category_slug')][:3]
    story=(p.get('story') or 'The original project-specific design decisions will be added after confirmation.').replace('\n','<br>')
    return f'''<article class="x-case"><header class="x-case-header x-wrap"><div>{eyebrow('PORTFOLIO / '+c.get('title','SELECTED WORK'))}<h1>{E(title(p))}</h1><div class="x-case-header-bottom"><span>{E(c.get('title','Selected work'))}</span><a href="#inside" class="x-underlink">Step inside ↓</a></div></div><div class="x-case-header-aside"><p>Designed to be explored.</p><span>PROJECT STUDY / THE LUXURY LOOK</span></div></header><section class="x-case-cover"><div class="x-media x-media--cinema"><img src="{E(photourl(p))}" alt="Temporary cover image for {E(title(p))}" width="1600" height="900" fetchpriority="high"><span class="x-case-figcaption">{E(title(p))}<b>01 / PROJECT VIEW</b></span></div></section><section class="x-case-intro x-wrap x-section" id="inside"><aside>{eyebrow('PROJECT NOTES')}<dl><div><dt>Project</dt><dd>{E(title(p))}</dd></div><div><dt>Discipline</dt><dd>{E(c.get('title'))}</dd></div></dl>{A('contact','Discuss a similar project')}</aside><div>{eyebrow('01 / BEHIND THE SPACE')}{h2('A room can change<br><em>everything.</em>')}<p class="x-lead">{E(p.get('summary'))}</p><div class="x-prose"><p>{E(story)}</p></div><p class="x-preview-disclaimer">The image set is illustrative and not a verified photographic record of this named project. Replace it through Filament before launch.</p></div></section>
<section class="x-case-gallery x-wrap x-section"><div class="x-heading"><div>{eyebrow('02 / VISUAL STORY')}{h2('Consider every<br><em>angle.</em>')}</div><span>08 IMAGE POSITIONS</span></div><div class="x-gallery-rail">{figures}</div><p class="x-gallery-count">08 VIEWS / TEMPORARY DESIGN PREVIEW</p></section>
<section class="x-case-story x-section"><div class="x-wrap">{eyebrow('03 / THE DESIGN APPROACH')}{h2('Beauty with<br><em>a purpose.</em>')}<div class="x-case-story-grid"><div><span>THE BRIEF</span><p>Client priorities and scope will be documented following final case-study approval.</p></div><div><span>THE APPROACH</span><p>Materials, planning and furnishings are chosen to support how the room will be experienced.</p></div><div><span>THE SCOPE</span><p>Room-by-room interventions and finishing decisions will be entered in the CMS.</p></div></div></div></section><section class="x-case-cta x-section"><div class="x-wrap">{eyebrow('A NEW PROJECT / A NEW POSSIBILITY')}{h2('What could your<br><em>space become?</em>')}{pill('contact','Let’s discuss your project',True)}</div></section></article><section class="x-related x-wrap x-section"><div class="x-heading"><div>{eyebrow('STAY A LITTLE LONGER')}{h2('Keep exploring.')}</div>{A('portfolio','View all projects')}</div><div class="x-related-grid">{''.join(card(x,i) for i,x in enumerate(matches))}</div></section><div class="lightbox" role="dialog" aria-modal="true" aria-label="Project gallery" aria-hidden="true" hidden><button class="lightbox-close" aria-label="Close">×</button><button class="lightbox-prev" aria-label="Previous">←</button><img class="lightbox-image" alt=""><button class="lightbox-next" aria-label="Next">→</button><span class="lightbox-count"></span></div>'''

def services():
    rows=''
    for i,s in enumerate(SERVICES):
        img=CATS.get({"interior-design":"interior-design-projects","interior-styling":"interior-styling-projects-the-luxury-look","luxury-residential-staging":"home-staging-projects-the-luxury-look","commercial-staging":"commercial-staging-projects-the-luxury-look","airbnb-staging":"airbnb-staging-the-luxury-look","corporate-branding":"corporate-brandding-projects-the-luxury-look"}.get(s['slug']),{}).get('hero_image',STOCK[0])
        rows+=f'<article class="x-service-chapter"><a class="x-service-chapter-photo x-media x-media--landscape" href="{U("services/"+s["slug"])}"><img src="{E(img)}" width="960" height="720" loading="lazy" alt="Temporary illustration for {E(s["title"])}"></a><div class="x-service-chapter-copy">{eyebrow(str(i+1).zfill(2)+" / OUR EXPERTISE")}{h2(E(s["title"]))}<p>{E(s.get("summary"))}</p>{A("services/"+s["slug"],"Explore the service")}</div></article>'
    return f'''<header class="x-services-hero x-wrap"><div>{eyebrow('THE LUXURY LOOK / SERVICES')}<h1>Great design<br><em>changes what</em><br>home feels like.</h1><p>From the first thought to the final piece, we help turn places into environments worth remembering.</p>{pill('contact','Discuss your plans')}</div>{media(CATS['interior-design-projects']['hero_image'],'Interior design layout visual','portrait',True)}</header><section class="x-services-statement x-section x-wrap">{eyebrow('FIND YOUR FIT')}{h2('Not another list of services.<br><em>A way to make your vision real.</em>')}</section><section class="x-services-chapters x-wrap">{rows}</section><section class="x-services-paths x-wrap x-section"><div>{eyebrow('BEYOND THE DESIGN PROJECT')}{h2('Two more ways<br><em>to experience the studio.</em>')}</div><div class="x-services-path-links"><a href="{U('dallas-market')}"><span>CURATED SOURCING</span><strong>Dallas Market Experiences</strong><b>↗</b></a><a href="{U('education')}"><span>KNOWLEDGE &amp; MENTORING</span><strong>Education &amp; Workshops</strong><b>↗</b></a></div></section>'''+row_cta('Every beautiful space<br><em>starts somewhere.</em>')

def service(s):
    mapcat={'interior-design':'interior-design-projects','interior-styling':'interior-styling-projects-the-luxury-look','luxury-residential-staging':'home-staging-projects-the-luxury-look','airbnb-staging':'airbnb-staging-the-luxury-look','commercial-staging':'commercial-staging-projects-the-luxury-look','corporate-branding':'corporate-brandding-projects-the-luxury-look'}
    img=CATS.get(mapcat.get(s['slug']),{}).get('hero_image',STOCK[0])
    related=[p for p in PROJECTS if p.get('category_slug')==mapcat.get(s['slug'])][:3]
    return f'''<section class="x-service-feature x-service-feature--{len(s['slug'])%3} x-wrap"><div class="x-service-feature-copy">{eyebrow('THE LUXURY LOOK / '+s['title'])}<h1>{E(s['title'])}</h1><p class="x-lead">{E(s.get('summary'))}</p><div class="x-actions">{pill('contact','Start a conversation')}<a href="#process" class="x-underlink">Our approach ↓</a></div></div><div class="x-service-feature-img x-media x-media--landscape"><img src="{E(img)}" width="1100" height="850" alt="Temporary editorial service reference"></div></section><section class="x-service-promise x-wrap x-section">{eyebrow('01 / THE OPPORTUNITY')}{h2('More than a finished room.<br><em>A better way to live in it.</em>')}<div class="x-service-promise-copy"><p>{E(s['summary'])}</p><div class="x-prose">{E(s.get('body')).replace(chr(10),'<br>')}</div></div></section><section class="x-process-feature" id="process"><div class="x-wrap">{eyebrow('02 / HOW WE WORK')}<div class="x-heading">{h2('From the first conversation<br><em>to the finishing touch.</em>')}<p>Bring us the property, the goals and the questions.</p></div><div class="x-process-steps"><div><b>01</b><h3>Understand the space</h3><p>Talk about the property, the people and the vision.</p></div><div><b>02</b><h3>Set the direction</h3><p>Align priorities, scope and the project approach.</p></div><div><b>03</b><h3>Bring it together</h3><p>Discuss planning, sourcing and the next steps.</p></div></div></div></section><section class="x-related x-wrap x-section"><div class="x-heading"><div>{eyebrow('03 / A LOOK AT THE WORK')}{h2('Imagine the possibilities.')}</div>{A('portfolio','The complete portfolio')}</div><div class="x-related-grid">{''.join(card(p,i) for i,p in enumerate(related))}</div></section>'''+row_cta('It all starts<br><em>with an idea.</em>')

def offering_index(kind):
    entries=DALLAS if kind=='dallas' else EDU
    if kind=='dallas':
        rows=''.join(f'<a class="x-market-row" href="{U("dallas-market/"+o["slug"])}"><span>{i+1:02d}</span><div><small>{E(o["audience"])}</small><h3>{E(o["title"])}</h3><p>{E(o["summary"])}</p></div>{media(o["cover_image"],o["title"],"square").replace('class="x-media', 'class="x-market-row-thumb x-media')}<b>↗</b></a>' for i,o in enumerate(entries))
        top=f'<header class="x-market-hero"><div class="x-market-copy">{eyebrow("THE LUXURY LOOK / BUYING EXPERIENCES")}<h1>Know what<br><em>to look for.</em><br>Love what<br>you find.</h1><p>Dallas Market buying and sourcing experiences for people who value a trained eye, a clear direction and the pleasure of discovering something special.</p>{pill("contact","Discuss a buying experience",True)}<small>PRIVATE INQUIRY / DATES &amp; ACCESS CONFIRMED INDIVIDUALLY</small></div><div class="x-market-film">{video_frame()}<div class="x-market-film-caption">DALLAS / SOURCING WITH INTENTION</div></div></header><section class="x-market-intro x-section x-wrap">{eyebrow("01 / THE REASON TO GO")}<div>{h2("A great purchase<br><em>changes the room.</em>")}<p>Buying trips are not about seeing everything. They are about knowing where to focus and how selections will fit the project.</p></div></section><section class="x-market-offerings x-section x-wrap"><div class="x-heading"><div>{eyebrow("02 / EXPLORE THE OFFERINGS")}{h2("The Market Edit.")}</div><p>Options to discuss with the studio. Each itinerary is discussed before engagement.</p></div>{rows}</section><section class="x-market-journey x-section"><div class="x-wrap">{eyebrow("03 / HOW IT BEGINS")}{h2("Arrive with a vision.<br><em>Leave with direction.</em>")}<div class="x-market-steps"><article><span>01</span><h3>Tell us the goal</h3><p>Discuss your priorities and inspiration.</p></article><article><span>02</span><h3>Shape the experience</h3><p>Agree on scope, timing and access.</p></article><article><span>03</span><h3>Bring it home</h3><p>Talk through selections and next steps.</p></article></div></div></section>'
    else:
        acc=''.join(f'<details {"open" if i==0 else ""}><summary><span>{i+1:02d}</span><strong>{E(o["title"])}</strong><b>+</b></summary><div class="x-learning-detail">{media(o["cover_image"],o["title"])}<div><p>{E(o["summary"])}</p><small>{E(o["audience"])}</small>{A("education/"+o["slug"],"Explore this program")}</div></div></details>' for i,o in enumerate(entries))
        top=f'<header class="x-learning-hero x-wrap"><div>{eyebrow("THE LUXURY LOOK / STUDIO EDUCATION")}<h1>The business<br>of beautiful<br><em>spaces.</em></h1><p>A learning collection for people who design, stage, style and build businesses around compelling places.</p><a href="#programs" class="x-pill">Explore the programs <span>↓</span></a></div><div class="x-learning-art x-media x-media--portrait"><img src="{E(entries[0]["cover_image"])}" width="880" height="1200" alt="Design education illustrative image"><div class="x-learning-art-caption"><span>DESIGN</span><span>BUSINESS</span><span>EXPERIENCE</span></div></div></header><section class="x-learning-marquee" aria-hidden="true"><div>LEARN • DESIGN • GROW • CREATE • LEARN • DESIGN • GROW • CREATE •</div></section><section class="x-learning-intro x-wrap x-section">{eyebrow("01 / LEARNING WITH INTENTION")}<div>{h2("Experience is meant<br><em>to be shared.</em>")}<p>Explore visual design and practical thinking for a sustainable service-driven business.</p></div></section><section class="x-learning-programs x-wrap x-section" id="programs"><div class="x-heading"><div>{eyebrow("02 / AREAS OF STUDY")}{h2("Choose your<br><em>next chapter.</em>")}</div></div><div class="x-learning-accordion" data-accordion>{acc}</div></section><section class="x-learning-note x-section"><div class="x-wrap">{eyebrow("CURIOUS ABOUT THE NEXT PROGRAM?")}{h2("Learn what you<br><em>want to build.</em>")}<p>Workshop details and schedules are confirmed by inquiry.</p>{pill("contact","Request program details",True)}</div></section>'
    faq='<section class="x-offering-faq x-wrap x-section"><div class="x-heading"><div>'+eyebrow('BEFORE WE BEGIN')+h2('Good questions.<br><em>Thoughtful answers.</em>')+'</div></div><div class="x-faq-list" data-accordion><details><summary>Are dates and pricing available?<span>+</span></summary><p>Availability, confirmed times and any applicable fees are shared after a direct inquiry.</p></details><details><summary>Can we tailor the format?<span>+</span></summary><p>Share your project or learning goals with the studio to discuss an appropriate approach.</p></details><details><summary>How do I get started?<span>+</span></summary><p>Send a project inquiry with your priorities, location and preferred timing.</p></details></div></section>'
    return top+faq+row_cta('Let’s make<br><em>something happen.</em>','Tell us what matters most. We will discuss the next suitable step.','Make an inquiry')

def offering_detail(o):
    kind=o['kind']; parent='dallas-market' if kind=='dallas' else ('buying-trips' if kind=='buying-trip' else 'education')
    desc=E(o.get('body')).replace('\n\n','</p><p>').replace('\n','<br>')
    return f'<article class="x-offer-detail x-offer-detail--{kind}"><div class="x-wrap x-offer-top"><div class="x-offer-intro">{eyebrow(o["eyebrow"])}<h1>{E(o["title"])}</h1><p class="x-lead">{E(o["summary"])}</p><div class="x-actions">{pill("contact",o["cta"])}<a href="#details" class="x-underlink">Explore the details ↓</a></div></div><div class="x-offer-image x-media x-media--portrait"><img src="{E(o["cover_image"])}" alt="Temporary sourcing or education reference" width="900" height="1200"><span>{E(o["eyebrow"])}</span></div></div><section id="details" class="x-offer-story x-section"><div class="x-wrap">{eyebrow("01 / THE EXPERIENCE")}{h2("Tailored to<br><em>your goals.</em>")}<div class="x-offer-description x-prose"><p>{desc}</p></div></div></section><section class="x-offer-takeaway x-wrap x-section">{media(U("media/atelier-motion-poster.jpg").rstrip("/"),"Temporary illustrative design photography") }<div>{eyebrow("02 / THE RIGHT FIT")}{h2("Make every choice<br><em>count.</em>")}<p>{E(o["audience"])}</p>{A("contact","Start a conversation")}</div></section>'+row_cta('A good beginning<br><em>changes everything.</em>','Availability, scope, schedule and any fees are confirmed directly before engagement.',o['cta'])+'</article>'

def editorial_page(p):
    slug=p['path'].strip('/');headline={'about':'Design is deeply personal.','the-studio':'A place to begin.','awards':'Work that speaks for itself.'}.get(slug,p['title']);intro=p.get('intro') or ''
    img=(CATS['interior-design-projects'].get('hero_image') or STOCK[0])
    if slug=='the-studio':
        return f'<article class="lux-studio"><header class="lux-studio-hero"><div class="lux-studio-hero__photo media-frame"><img src="{E(img)}" alt="Temporary studio editorial visual"><span>EDITORIAL LAYOUT / IMAGE TO REPLACE</span></div><div class="lux-studio-hero__copy">{eyebrow("THE DESIGN STUDIO / BY APPOINTMENT")}<h1>A place to<br><em>begin.</em></h1><p>{E(intro)}</p>{pill("contact","Arrange a conversation")}</div></header></article>'+row_cta('Bring the questions.<br><em>We’ll bring the perspective.</em>')
    if slug=='about':
        return f'<article class="lux-about"><header class="lux-about-hero"><div class="lux-about-hero__title">{eyebrow("THE PEOPLE AND THINKING BEHIND THE LUXURY LOOK")}<h1>Design is<br><em>deeply personal.</em></h1><p>We begin with the people who will live, work and gather in the space.</p><a href="#founder" class="x-underlink">Meet the founder ↓</a></div><div class="lux-about-hero__photo media-frame"><img src="{E(img)}" alt="Interior design editorial photograph"></div></header><section id="founder" class="x-introduction x-wrap x-section">{eyebrow("FOUNDED BY KRISTINE VOWLES")}<div>{h2("A designer’s eye.<br><em>A real-estate perspective.</em>")}<p>Kristine founded The Luxury Look in 2013. Her design experience and understanding of how people experience property inform each new project.</p>{A("contact","Discuss your project")}</div></section></article>'+row_cta('Let’s talk about<br><em>your space.</em>')
    if slug=='awards':
        return f'<section class="x-portfolio-lead x-wrap"><div>{eyebrow("PRESS / THE DESIGN HOUSE")}<h1>Work that<br><em>speaks for itself.</em></h1><p>Recognition, editorial features and awards are part of the studio story. Original links and award details are being verified before production.</p>{A("portfolio","Explore the portfolio")}</div>{media(img,"Temporary design photography","portrait")}</section>'
    return f'<section class="x-portfolio-lead x-wrap"><div>{eyebrow(p.get("eyebrow","THE LUXURY LOOK"))}<h1>{E(headline)}</h1><p>{E(intro)}</p>{A("contact","Ask the studio")}</div>{media(img,"Temporary page photography","portrait")}</section><section class="x-category-manifesto x-wrap x-section">{eyebrow("THE DETAILS")}<div>{h2("A closer look.")}<p>{E(p.get("body","Full program details are being confirmed."))}</p></div></section>'

def journal():
    blogs=[u for u in LEGACY if u['path'].startswith('/blog/')];cards=''
    for i,b in enumerate(blogs):
        slug=b['path'].rsplit('/',1)[-1];label=slug.replace('---',' — ').replace('-',' ').title();img=STOCK[i%len(STOCK)]
        cards+=f'<a class="x-project-card" href="{U(b["path"])}"><div class="x-project-media x-media x-media--landscape"><img src="{E(img)}" alt="Temporary journal illustration" loading="lazy" width="900" height="600"></div><div class="x-project-meta"><div><span>JOURNAL / INTERIORS</span><h3>{E(label)}</h3></div>↗</div></a>'
    return f'<section class="x-portfolio-lead x-wrap"><div>{eyebrow("THE JOURNAL / DESIGN & LIVING")}<h1>Notes on the<br><em>art of living well.</em></h1><p>Thoughts on interiors, staging, style and the places we inhabit.</p>{A("education","Explore education")}</div>{media(STOCK[5],"Illustrative design editorial image","portrait")}</section><section class="x-related x-wrap x-section"><div class="x-heading"><div>{eyebrow("THE ORIGINAL ARTICLE ARCHIVE")}{h2("The journal.")}</div></div><div class="x-related-grid">{cards}</div></section>'

def journal_post(x):
    label=x['path'].split('/')[-1].replace('-',' ').title()
    return f'<section class="x-portfolio-lead x-wrap"><div>{eyebrow("THE LUXURY LOOK / JOURNAL")}<h1>{E(label)}</h1><p>The full original article is being transferred and verified. This page retains the original URL during the staging review.</p>{A("blog","Return to the journal")}</div>{media(STOCK[4],"Temporary journal illustration","portrait")}</section>'

def page_extra(path):
    if path=='design':return f'<section class="x-services-hero x-wrap"><div>{eyebrow("THE LUXURY LOOK / FULL-SERVICE DESIGN")}<h1>Design for<br><em>a life well lived.</em></h1><p>Full-service interiors for primary homes, second homes, luxury renovations and considered finishing details.</p>{pill("contact","Begin a design project")}</div>{media(STOCK[2],"Temporary interior design illustration","portrait")}</section>'+row_cta('Make the space<br><em>unmistakably yours.</em>')
    if path=='builders-and-agents':return f'<section class="x-category-hero"><div class="x-category-hero-photo x-media x-media--cinema"><img src="{E(STOCK[4])}" alt="Temporary luxury property illustration" width="1600" height="900"></div><div class="x-category-hero-title x-wrap">{eyebrow("LUXURY SPECS & MARKET-READY") }<h1>Designed to sell.<br><em>Staged to stay with you.</em></h1><p>For builders, developers and real-estate professionals who need a home to make a lasting impression.</p>{pill("contact","Discuss your property",True)}</div></section>'+row_cta('Give buyers<br><em>a reason to remember.</em>')
    if path=='collection':return f'<section class="x-services-hero x-wrap"><div>{eyebrow("THE COLLECTION / FURNISHINGS & HOUSEWARES") }<h1>The finishing<br><em>touches matter.</em></h1><p>Furniture and housewares make a space complete. Explore the collection and discuss furnishing support with the studio.</p><a class="x-underlink" href="https://heyzine.com/flip-book/a228d0b91d.html#page/78" target="_blank" rel="noopener noreferrer">View the collection lookbook ↗</a></div>{media(STOCK[1],"Temporary furnishing illustration","portrait")}</section>'+row_cta('Bring the whole<br><em>home together.</em>')
    if path=='curated-circle':return f'<section class="x-market-hero"><div class="x-market-copy">{eyebrow("THE CURATED CIRCLE / COMING SOON")}<h1>More than a home.<br><em>A world of ideas.</em></h1><p>A proposed private community for design, sourcing and thoughtful living. The launch date and benefits are subject to confirmation.</p>{pill("contact","Register interest",True)}</div><div class="x-market-film">{video_frame()}</div></section>'+row_cta('Stay close to<br><em>beautiful ideas.</em>')
    return ''

def contact():
    return f'<section class="x-market-hero"><div class="x-market-copy">{eyebrow("WORK WITH US / THE LUXURY LOOK")}<h1>Every beautiful<br><em>home begins</em><br>with a conversation.</h1><p>Design, staging, buying experiences and learning. Tell us what you are imagining and we will help shape the next step.</p><a class="x-pill x-pill--light" href="mailto:kristine@theluxurylook.com?subject=New%20Project%20Inquiry">Email the studio ↗</a></div><div class="x-market-film">{video_frame()}</div></section><section class="x-market-intro x-wrap x-section">{eyebrow("CONTACT THE STUDIO")}<div>{h2("Tell us about<br><em>your project.</em>")}<p>The static design preview does not accept inquiries. Please use email or phone to reach the studio directly.</p><p><a href="mailto:kristine@theluxurylook.com">kristine@theluxurylook.com</a><br><a href="tel:+16025786707">+1 (602) 578-6707</a></p></div></section>'

def write(path,text):
    out=DEST/path;out.parent.mkdir(parents=True,exist_ok=True);out.write_text(text,encoding='utf-8')
def main():
    if DEST.exists():shutil.rmtree(DEST)
    shutil.copytree(ROOT/'public/assets',DEST/'assets')
    shutil.copytree(ROOT/'public/media',DEST/'media',ignore=shutil.ignore_patterns('atelier-motion.mp4'))
    write('.nojekyll','');write('robots.txt','User-agent: *\nDisallow: /\n')
    rendered=set()
    def output(path,heading,content):
        rel=path.strip('/');name=(rel+'/index.html') if rel else 'index.html'
        write(name,shell(heading,content));rendered.add('/'+rel if rel else '/')
    output('/','Home',home());output('portfolio','The Portfolio',portfolio())
    output('buying-trips','Buying Trips',buying_trips())
    for cat in CATEGORIES:output('portfolio/'+cat['slug'],cat['title'],category(cat))
    for item in PROJECTS:output('portfolios/'+item['slug'],title(item),project(item))
    output('services','Services',services()+film_feature())
    for item in SERVICES:output('services/'+item['slug'],item['title'],service(item))
    for k in ['dallas','education']:
        slug='dallas-market' if k=='dallas' else 'education'
        output(slug,'Dallas Market Buying Experiences' if k=='dallas' else 'Education & Workshops',offering_index(k))
        if k=='dallas':output('buying-trips/dallas-market','Dallas Market / Buying Trips',offering_index(k))
        for o in OFFERINGS:
            if o['kind']==k:output(slug+'/'+o['slug'],o['title'],offering_detail(o))
    for o in BUYING:output('buying-trips/'+o['slug'],o['title'],offering_detail(o))
    for k in ['design','builders-and-agents','collection','curated-circle']:output(k,k.replace('-',' ').title(),page_extra(k))
    for p in PAGES:
        if p['path']=='/contact':continue
        output(p['path'],p['title'],editorial_page(p))
    output('contact','Contact The Studio',contact());output('blog','The Journal',journal())
    for item in LEGACY:
        if item['path'].startswith('/blog/'):output(item['path'],item['path'].split('/')[-1].replace('-',' ').title(),journal_post(item))
    for item in LEGACY:
        if item['path'] not in rendered:
            # Retain exact original paths for audit but never pretend commerce or old
            # templates are functional while the migration is under review.
            msg=f'<section class="x-services-hero x-wrap"><div>{eyebrow("THE LUXURY LOOK / ARCHIVE") }<h1>Content in<br><em>transition.</em></h1><p>This legacy URL is reserved for content migration. It is not approved for public launch.</p>{A("contact","Contact the studio")}</div>{media(STOCK[1],"Temporary editorial photograph","portrait")}</section>'
            output(item['path'],'Content Under Review',msg)
    write('404.html',shell('Page Not Found',f'<section class="x-portfolio-lead x-wrap"><div>{eyebrow("PAGE NOT FOUND")}<h1>Find a new<br><em>direction.</em></h1>{A("portfolio","Browse the portfolio")}</div></section>'))
    archive=(DEST/'portfolio/index.html').read_text('utf-8')
    for p in PROJECTS:
        name=E(title(p));old=E(p['title']);
        assert f'<h3>{name}</h3>' in archive, f'Working name missing in preview card: {p["slug"]}'
        if name!=old:assert f'<h3>{old}</h3>' not in archive, f'Stale project card: {p["slug"]}'
        assert (DEST/'portfolios'/p['slug']/'index.html').exists()
    assert not any('atelier-motion.mp4' in f.read_text('utf-8') for f in DEST.rglob('*.html')), 'Zooming video source must not be rendered'
    assert (DEST/'buying-trips/index.html').exists() and (DEST/'buying-trips/dallas-market/index.html').exists()
    assert 'media-stable.css' in (DEST/'index.html').read_text('utf-8')
    print(f'Experience preview PASS: {len(rendered)} pages, {len(PROJECTS)} uniquely named projects, {len(DALLAS)} Dallas offerings, {len(EDU)} education tracks, all original paths reserved.')

if __name__=='__main__':main()
