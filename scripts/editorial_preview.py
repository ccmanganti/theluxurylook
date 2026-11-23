"""Conversion-led inner-page design for GitHub Pages visual review only.

This module mirrors the Laravel Blade page hierarchy using the shared
editorial-content.json, original project URLs and editable photography slots.
Preview photos and journal imagery are illustrative and not evidence of work.
"""
from __future__ import annotations
import html


def e(v):
    return html.escape(str(v if v is not None else ''), quote=True)


def U(ctx, path):
    return ctx['url'](path)


def I(ctx, source, alt, loading='lazy'):
    return ctx['image'](source or '', alt, loading)


def frame(ctx, source, alt, css='', loading='lazy'):
    return f'<div class="{css} media-frame">{I(ctx,source,alt,loading)}</div>'


def btn(ctx, path, label, light=False):
    c='lux-btn lux-btn--light' if light else 'lux-btn'
    return f'<a class="{c}" href="{U(ctx,path)}">{e(label)} <span>&nearr;</span></a>'


def action(ctx, path, label):
    return f'<a class="lux-line-action" href="{U(ctx,path)}">{e(label)} &nearr;</a>'


def conversion(ctx, question, description='Tell us about your property and what you hope to create.',label='Begin a conversation'):
    return f'''<section class="lux-conversion lux-wrap"><p class="lux-overline">A space of your own</p><h2>{e(question)}</h2><p>{e(description)}</p>{btn(ctx,'contact',label)}</section>'''


def project_tiles(ctx, projects, css='lux-editorial-grid'):
    return f'<div class="{css}">'+''.join(f'<div class="lux-editorial-grid__item lux-editorial-grid__item--{i%7}">{ctx["work_card"](p)}</div>' for i,p in enumerate(projects))+'</div>'


def portfolio_index(ctx):
    projects=ctx['PROJECTS']; cats=ctx['CATEGORIES']; lead=projects[0] if projects else None
    filters=f'<button class="filter-button is-active" data-portfolio-filter="all" aria-pressed="true">All work <span>{len(projects)}</span></button>'
    filters+=''.join(f'<button class="filter-button" data-portfolio-filter="{e(c["slug"])}" aria-pressed="false">{e(c["title"])} <span>{ctx["COUNT"][c["slug"]]}</span></button>' for c in cats)
    return f'''<section class="lux-portfolio-head"><div class="lux-wrap lux-portfolio-head__inner"><div><p class="lux-overline">An edited collection / The Luxury Look</p><h1>Spaces worth<br><em>exploring.</em></h1><p>Every project offers a different perspective on how a place can look, feel and function. Explore the work by discipline, property and point of view.</p><a class="lux-text-link" href="#all-projects">Enter the collection &darr;</a></div><div class="lux-portfolio-head__photo media-frame">{I(ctx,ctx['cover'](lead) if lead else '', 'Temporary featured project photograph','eager')}<span>{len(projects)} PROJECTS &nbsp; / &nbsp; {len(cats)} DISCIPLINES</span></div></div></section>
<section id="all-projects" class="lux-collection lux-wrap"><div class="lux-heading-row"><div><p class="lux-overline">01 / Find your inspiration</p><h2>The portfolio<span class="lux-dot">.</span></h2></div><p>Choose a discipline or browse the complete collection. Open any project to explore its visual story.</p></div><div class="lux-filter-bar" aria-label="Filter projects">{filters}</div>{project_tiles(ctx,projects)}<p class="empty-filter" hidden>No projects in this discipline yet.</p></section>{conversion(ctx,'Have a space that needs a fresh perspective?')}'''


def category_page(c,ctx):
    projects=[p for p in ctx['PROJECTS'] if p.get('category_slug')==c['slug']]
    copy=ctx['EDITORIAL']['categories'].get(c['slug'],{})
    spotlight=projects[0] if len(projects)>0 else None
    supporting=projects[1] if len(projects)>1 else None
    tabs=f'<a href="{U(ctx,"portfolio")}">All work</a>'
    tabs+=''.join(f'<a href="{U(ctx,"portfolio/"+x["slug"])}" {"aria-current=page" if x["slug"]==c["slug"] else ""}>{e(x["title"])}</a>' for x in ctx['CATEGORIES'])
    main=f'''<a class="lux-collage-intro__main media-frame" href="{U(ctx,'portfolios/'+spotlight['slug'])}">{I(ctx,ctx['cover'](spotlight),spotlight['title'])}<span class="lux-image-caption"><b>{e(spotlight['title'])}</b><span>View project &nearr;</span></span></a>''' if spotlight else ''
    secondary=f'''<a class="media-frame" href="{U(ctx,'portfolios/'+supporting['slug'])}">{I(ctx,ctx['cover'](supporting),supporting['title'])}<span class="lux-image-caption">{e(supporting['title'])}</span></a>''' if supporting else ''
    focus=''.join(f'<div><span>0{i+1}</span><p>{e(x)}</p></div>' for i,x in enumerate(copy.get('focus',[])))
    return f'''<article class="lux-category"><header class="lux-category-hero"><div class="lux-category-hero__image media-frame">{I(ctx,ctx['cat_image'](c), c['title']+' temporary design reference','eager')}</div><div class="lux-category-hero__panel"><p class="lux-overline"><a href="{U(ctx,'portfolio')}">The portfolio</a> <span>/</span> {e(c['title'])}</p><span class="lux-hero-rule"></span><h1>{e(c['title'])}</h1><p class="lux-category-hero__line">{e(copy.get('headline',c['tagline']))}</p><a class="lux-text-link" href="#collection">Discover the collection <span>&darr;</span></a><div class="lux-category-hero__foot"><span>Selected work / Scottsdale & beyond</span><span>{len(projects):02d} projects</span></div></div></header>
<section class="lux-category-intro lux-wrap"><div class="lux-vertical-index"><span>01</span><span>THE POINT OF VIEW</span></div><div><p class="lux-overline">{e(copy.get('eyebrow','A closer look'))}</p><h2>{e(copy.get('promise',c['tagline']))}</h2></div><div class="lux-category-intro__right"><p>{e(copy.get('opening',c['description']))}</p>{action(ctx,'contact','Discuss your space')}</div></section>
<section class="lux-collage-intro lux-wrap" aria-label="Selected photography">{main}<div class="lux-collage-intro__aside">{secondary}<div class="lux-collage-intro__note"><span class="lux-overline">Every detail matters</span><p>{e(copy.get('headline',c['tagline']))}</p><span class="lux-aside-number">{len(projects):02d}</span></div></div></section>
<section id="collection" class="lux-collection lux-wrap"><div class="lux-heading-row"><div><p class="lux-overline">02 / Explore the work</p><h2>The collection<span class="lux-dot">.</span></h2></div><p>Take a closer look at the individual spaces, settings and projects within this discipline.</p></div><nav class="lux-category-nav" aria-label="Portfolio disciplines">{tabs}</nav>{project_tiles(ctx,projects)}</section>
<section class="lux-values lux-wrap"><div><p class="lux-overline">What we consider</p><h2>The details<br><em>change everything.</em></h2></div><div class="lux-values__items">{focus}</div></section>{conversion(ctx,copy.get('cta','Have something in mind?'))}</article>'''


def project_page(p,ctx):
    c=ctx['CATEGORY_MAP'].get(p.get('category_slug',''),{})
    related=[x for x in ctx['PROJECTS'] if x['slug'] != p['slug'] and x.get('category_slug')==p.get('category_slug')][:3]
    photos=p.get('preview_gallery',[])
    breadcrumb=f'<a href="{U(ctx,"portfolio")}">The portfolio</a> <span>/</span> '+(f'<a href="{U(ctx,"portfolio/"+c["slug"])}">{e(c["title"])}</a>' if c else 'Selected work')
    details=f'<div><dt>Project</dt><dd>{e(p["title"])}</dd></div><div><dt>Discipline</dt><dd>{e(c.get("title","Selected work"))}</dd></div>'
    if p.get('location'):details+=f'<div><dt>Location</dt><dd>{e(p["location"])}</dd></div>'
    intro=p.get('summary') or 'Original project details are being reviewed.'
    note='Original project notes, styling decisions and materials are being confirmed for the full case study. These editorial layouts are ready for the final images and descriptions.'
    gallery=''
    for i,photo in enumerate(photos):
        s=photo.get('url','') if isinstance(photo,dict) else str(photo)
        alt=photo.get('alt','Temporary project photograph') if isinstance(photo,dict) else 'Temporary project photograph'
        gallery+=f'''<figure class="lux-photo-story__item lux-photo-story__item--{i%8} media-frame"><button type="button" class="gallery-open" data-gallery-image="{e(s)}" data-gallery-alt="{e(alt)}" aria-label="Enlarge photograph {i+1}">{I(ctx,s,alt)}<span class="lux-photo-story__view">View photograph &nearr;</span></button><figcaption><span>{i+1:02d} / {len(photos):02d}</span><span>IMAGE PLACEHOLDER</span></figcaption></figure>'''
    if not photos: gallery='<p>Approved project photography will appear here after migration.</p>'
    rel=f'''<section class="lux-related lux-wrap"><div class="lux-heading-row"><div><p class="lux-overline">03 / Continue exploring</p><h2>More to discover<span class="lux-dot">.</span></h2></div>{action(ctx,'portfolio','View the full portfolio')}</div><div class="lux-related__grid">{''.join(ctx['work_card'](x) for x in related)}</div></section>''' if related else ''
    return f'''<article class="lux-case"><header class="lux-case-head lux-wrap"><p class="lux-overline">{breadcrumb}</p><div class="lux-case-head__title"><h1>{e(p['title'])}</h1><div class="lux-case-head__index"><span>PROJECT STUDY</span><span>{e(c.get('title','The Luxury Look'))}</span></div></div><div class="lux-case-head__strap"><p>A closer look at a space from the portfolio.</p><a href="#project-story" class="lux-text-link">Explore this project &darr;</a></div></header>
<section class="lux-case-hero"><div class="lux-case-hero__image media-frame">{I(ctx,ctx['cover'](p),'Temporary hero image for '+p['title'],'eager')}<div class="lux-case-hero__tag"><span>THE LUXURY LOOK</span><span>{e(p['title'])}</span></div></div><div class="lux-case-hero__rail"><span>DESIGN ARCHIVE / 01</span><span>SCROLL TO EXPLORE &darr;</span></div></section>
<section class="lux-case-story lux-wrap" id="project-story"><aside class="lux-case-story__meta"><span class="lux-overline">The project / At a glance</span><dl>{details}</dl>{action(ctx,'contact','Discuss a similar project')}</aside><div class="lux-case-story__copy"><p class="lux-overline">01 / Inside the project</p><h2>The story<br><em>behind the space.</em></h2><p class="lux-large-copy">{e(intro)}</p><p>{e(note)}</p><p class="lux-notice" role="note">Design-review imagery only. Gallery photos are temporary layout references and are not verified photographs of this property. Replace them in the Filament project editor before launch.</p></div></section>
<section class="lux-gallery-section" aria-label="Project gallery"><div class="lux-wrap lux-gallery-heading"><div><p class="lux-overline">02 / The visual story</p><h2>Explore every angle<span class="lux-dot">.</span></h2></div><span>{len(photos):02d} PHOTOGRAPHS / REVIEW LAYOUT</span></div><div class="lux-photo-story lux-wrap">{gallery}</div></section>
<section class="lux-case-bridge"><div class="lux-wrap lux-case-bridge__inner"><p class="lux-overline">A thoughtful space begins with a conversation</p><h2>What could we create<br><em>for your space?</em></h2>{btn(ctx,'contact','Share your vision',True)}</div></section></article>{rel}{ctx['lightbox']()}'''


def steps(ctx):
    return ''.join(f'<div><span>{e(x["number"])}</span><h3>{e(x["title"])}</h3><p>{e(x["text"])}</p></div>' for x in ctx['EDITORIAL']['steps'])


def process(ctx):
    return f'''<section class="lux-process-band"><div class="lux-wrap"><p class="lux-overline">03 / Working with our studio</p><div class="lux-heading-row"><h2>From idea<br><em>to intention.</em></h2><p>We begin with a conversation and plan a direction suited to your project.</p></div><div class="lux-process-steps">{steps(ctx)}</div></div></section>'''


def services_index(ctx):
    services=ctx['SERVICES']; hero=ctx['service_image'](services[0]) if services else ''
    chapters=''
    for i,s in enumerate(services):
        copy=ctx['EDITORIAL']['services'].get(s['slug'],{})
        link='services/'+s['slug']
        chapters+=f'''<article class="lux-service-chapter"><a href="{U(ctx,link)}" class="lux-service-chapter__photo media-frame">{I(ctx,ctx['service_image'](s),s['title']+' temporary design reference')}<span>EXPLORE SERVICE &nearr;</span></a><div class="lux-service-chapter__copy"><span class="lux-overline">{i+1:02d} / {e(s['title'])}</span><h2>{e(copy.get('headline',s['title']))}</h2><p>{e(s.get('summary'))}</p>{action(ctx,link,'Explore '+s['title'])}</div></article>'''
    return f'''<article class="lux-services-index"><header class="lux-services-index__hero"><div class="lux-services-index__words"><p class="lux-overline">The Luxury Look / What we do</p><h1>Good spaces<br><em>change the way</em><br>we feel.</h1><p>Whether you're creating a home, preparing a property for sale or shaping a guest experience, we bring imagination and practical understanding to the space.</p>{btn(ctx,'contact','Tell us about your project',True)}</div><div class="lux-services-index__art media-frame">{I(ctx,hero,'Interior design visual reference','eager')}<span>DESIGN / STAGING / STYLING</span></div></header><div class="lux-services-index__intro lux-wrap"><div><span class="lux-overline">01 / Our disciplines</span><h2>Different spaces.<br><em>One considered approach.</em></h2></div><p>Explore the ways we can help you see your property differently. Each service begins with the people who will experience the space.</p></div><section class="lux-service-chapters lux-wrap" aria-label="Services">{chapters}</section>{process(ctx)}{conversion(ctx,'Tell us the story of your space.','Share your plans and we can help identify the right conversation to have next.','Start a project enquiry')}</article>'''


def service_page(s,ctx):
    copy=ctx['EDITORIAL']['services'].get(s['slug'],{})
    related=[p for p in ctx['PROJECTS'] if p.get('category_slug')==ctx['CATEGORY_SERVICE'].get(s['slug'],'')][:3]
    first=related[0] if related else None;second=related[1] if len(related)>1 else None
    aud=''.join(f'<div><span>{i+1:02d}</span><h3>{e(x)}</h3></div>' for i,x in enumerate(copy.get('audience',[])))
    caps=''.join(f'<div><span>{i+1:02d}</span><h3>{e(x)}</h3><span aria-hidden="true">&nearr;</span></div>' for i,x in enumerate(copy.get('scope',[])))
    art1=ctx['cover'](first) if first else ctx['service_image'](s)
    art2=ctx['cover'](second) if second else ctx['service_image'](s)
    rel=f'''<section class="lux-related lux-wrap"><div class="lux-heading-row"><div><p class="lux-overline">04 / See the possibilities</p><h2>Work in focus<span class="lux-dot">.</span></h2></div>{action(ctx,'portfolio','All portfolio projects')}</div><div class="lux-related__grid">{''.join(ctx['work_card'](p) for p in related)}</div></section>''' if related else ''
    return f'''<article class="lux-service"><header class="lux-service-hero"><div class="lux-service-hero__copy"><p class="lux-overline"><a href="{U(ctx,'services')}">Our services</a> / {e(s['title'])}</p><span class="lux-hero-rule"></span><span class="lux-service-hero__id">{e(copy.get('eyebrow','The Luxury Look'))}</span><h1>{e(s['title'])}</h1><p class="lux-service-hero__statement">{e(copy.get('headline',s['summary']))}</p>{btn(ctx,'contact','Let\'s talk about your project')}</div><div class="lux-service-hero__photo media-frame">{I(ctx,ctx['service_image'](s),s['title']+' temporary concept photograph','eager')}<div class="lux-service-hero__photo-caption">THE LUXURY LOOK / {e(s['title'].upper())}</div></div></header>
<section class="lux-service-opening lux-wrap"><span class="lux-overline">A closer look / Our approach</span><h2>{e(copy.get('question','How should your space make people feel?'))}</h2><div><p>{e(copy.get('intro',s['summary']))}</p><a class="lux-line-action" href="#service-work">Explore what's possible &darr;</a></div></section>
<section id="service-work" class="lux-service-scope lux-wrap"><div class="lux-service-scope__heading"><p class="lux-overline">01 / The brief</p><h2>Designed for<br><em>your next chapter.</em></h2><p>The best starting point is understanding the space, the people who use it and what you want it to achieve.</p></div><div class="lux-service-scope__cards">{aud}</div></section>
<section class="lux-service-art lux-wrap"><div class="lux-service-art__main media-frame">{I(ctx,art1,'Temporary related portfolio visual reference')}<span>THE PORTFOLIO / SELECTED DETAILS</span></div><div class="lux-service-art__stack">{frame(ctx,art2,'Temporary complementary visual reference')}<div class="lux-service-art__note"><p class="lux-overline">02 / What we consider</p><h2>Details in dialogue.</h2><p>Every choice should feel connected to the overall experience of the space.</p></div></div></section>
<section class="lux-service-capabilities lux-wrap"><div><p class="lux-overline">Areas of focus</p><h2>The right elements,<br><em>working together.</em></h2><p>Each commission is different. These are some of the areas we can explore together.</p></div><div class="lux-service-capabilities__list">{caps}</div></section>{process(ctx)}{rel}<section class="lux-service-close lux-wrap"><span class="lux-overline">Ready when you are</span><h2>{e(copy.get('closing','Let\'s create something distinctive.'))}</h2><p>Describe your project and your goals. We can discuss the right approach together.</p>{btn(ctx,'contact','Enquire about '+s['title'])}</section></article>'''


def about_page(page,ctx):
    path=page['path'].strip('/')
    pics=ctx['EDITORIAL']['pages'].get(path,ctx['EDITORIAL']['pages']['fallback'])
    first=pics['image'];second=pics['detail'];intro=page.get('intro','')
    if path=='about':
        return f'''<article class="lux-about"><header class="lux-about-hero"><div class="lux-about-hero__title"><p class="lux-overline">The people and thinking behind The Luxury Look</p><h1>Design is<br><em>deeply personal.</em></h1><p>We believe a space should feel like it belongs to the people who live, work and gather there.</p><a class="lux-text-link" href="#meet-kristine">Meet the founder &darr;</a></div>{frame(ctx,first,'Temporary featured interior reference','lux-about-hero__photo','eager')}</header><section class="lux-about-belief lux-wrap"><p class="lux-overline">01 / The philosophy</p><h2>We don't begin with a trend.<br><em>We begin with you.</em></h2><div><p>The Luxury Look draws on a rare combination of interior design and luxury real estate experience. Our approach brings visual clarity to the practical realities of a space, whether it's the home you live in or the property you're preparing for its next chapter.</p><p>Good design should create a feeling - and make sense for the people who use it.</p></div></section>
<section id="meet-kristine" class="lux-founder lux-wrap"><div class="lux-founder__visual media-frame">{I(ctx,second,'Selected interior design work')}<div><span>FOUNDED IN 2013</span><strong>Kristine<br>Vowles</strong></div></div><div class="lux-founder__story"><span class="lux-overline">02 / Meet the founder</span><h2>A designer's eye.<br><em>A real estate perspective.</em></h2><p class="lux-founder__role">Kristine Vowles / Founder & CEO</p><p>Kristine established The Luxury Look in 2013, following an extensive career in real estate. Her understanding of property, presentation and the people behind each decision informs the way she approaches design.</p><p>She listens for what matters most to clients, bringing that vision into focus through a balance of function, beauty and individual character.</p><div class="lux-founder__facts"><div><b>2013</b><span>THE STUDIO WAS ESTABLISHED</span></div><div><b>35+</b><span>YEARS OF INDUSTRY EXPERIENCE*</span></div></div><small>*Experience figure from the existing website; confirm before launch.</small>{action(ctx,'contact','Tell Kristine about your project')}</div></section>
<section class="lux-about-principles"><div class="lux-wrap"><p class="lux-overline">03 / What guides the work</p><h2>Beautiful is only<br><em>the beginning.</em></h2><div class="lux-about-principles__grid"><div><span>01</span><h3>Listen first</h3><p>Understand the way a space needs to work before deciding how it should look.</p></div><div><span>02</span><h3>Consider every detail</h3><p>Materials, scale, flow and finishing touches should speak to one another.</p></div><div><span>03</span><h3>Think beyond a moment</h3><p>Design for an experience that continues long after the first impression.</p></div></div></div></section><section class="lux-about-close lux-wrap"><div><p class="lux-overline">Explore the work</p><h2>See what this approach<br><em>looks like in practice.</em></h2>{btn(ctx,'portfolio','Discover our portfolio')}</div>{frame(ctx,first,'Temporary portfolio reference')}</section></article>{conversion(ctx,"Let's talk about your space.")}'''
    if path=='the-studio':
        return f'''<article class="lux-studio"><header class="lux-studio-hero"><div class="lux-studio-hero__photo media-frame">{I(ctx,first,'Temporary studio design reference','eager')}<span>EDITORIAL LAYOUT / IMAGE TO REPLACE</span></div><div class="lux-studio-hero__copy"><p class="lux-overline">Old Town Scottsdale / By appointment</p><h1>A place to<br><em>begin.</em></h1><p>{e(intro)}</p>{btn(ctx,'contact','Arrange a conversation')}</div></header><section class="lux-wrap lux-studio-visit"><div><p class="lux-overline">01 / Visit the studio</p><h2>Ideas begin<br><em>in conversation.</em></h2></div><div><p>Our Scottsdale design studio is a place to explore ideas, talk through materials, and consider what your property could become. Appointments ensure we can give your project the attention it deserves.</p><address>4343 N Scottsdale Road, Suite 150<br>Scottsdale, AZ 85251</address><span>VISITS BY APPOINTMENT ONLY</span>{action(ctx,'contact','Contact the studio')}</div></section><section class="lux-studio-double lux-wrap">{frame(ctx,second,'Temporary visual reference')}<div><span class="lux-overline">02 / What happens next</span><h2>Bring the questions.<br><em>We'll bring the perspective.</em></h2><p>Share photos, plans, timelines or simply a sense of what isn't working. We'll talk through the kind of design or staging support that makes sense.</p>{action(ctx,'services','Explore the services')}</div></section></article>{conversion(ctx,"Let's talk about your project.")}'''
    if path=='awards':
        return f'''<article class="lux-recognition"><header class="lux-recognition-hero"><div><p class="lux-overline">Recognition / The Luxury Look</p><h1>Work that<br><em>speaks for itself.</em></h1><p>The Luxury Look has received recognition for design and home staging. Explore the work behind the studio, and the perspective that continues to shape it.</p>{btn(ctx,'portfolio','Explore selected projects')}</div>{frame(ctx,first,'Temporary interior portfolio photograph','', 'eager')}</header><div class="lux-recognition-note lux-wrap"><span class="lux-overline">Press & Awards</span><div><h2>Recognition is part of the story.<br><em>The work is the proof.</em></h2><p>Original award details, dates and publisher links are being verified before launch. This design preview intentionally avoids unconfirmed badges or rankings.</p>{action(ctx,'portfolio','See the portfolio')}</div></div></article>{conversion(ctx,'Have a space to transform?')}'''
    body=''.join('<p>'+e(p)+'</p>' for p in page.get('body','').split('\n\n') if p)
    return f'''<article class="lux-generic"><header class="lux-generic-hero"><div><p class="lux-overline">{e(page.get('eyebrow','The Luxury Look'))}</p><h1>{e(page['title'])}</h1><p>{e(intro)}</p><a class="lux-text-link" href="#details">Explore the details &darr;</a></div>{frame(ctx,first,'Temporary editorial image for '+page['title'],'','eager')}</header><section id="details" class="lux-generic-details lux-wrap"><div><span class="lux-overline">01 / The details</span><h2>A closer look.</h2></div><div class="lux-richtext">{body}{action(ctx,'contact','Ask the studio')}</div></section></article>{conversion(ctx,'Have a space to transform?')}'''


def contact(ctx):
    photo=ctx['EDITORIAL']['pages']['about']['detail']
    choices=['Interior Design','Interior Styling','Luxury Residential Staging','Commercial Staging','Airbnb Staging','Corporate Branding']
    opts=''.join('<option>'+e(x)+'</option>' for x in choices)
    form=f'''<div class="lux-contact-form"><div class="lux-contact-form__title"><span class="lux-overline">Project inquiry / Step one</span><h2>Tell us about<br><em>the space.</em></h2><p>Use the form below, or email Kristine directly if that's easier.</p></div><form class="contact-form" aria-label="Visual reference of contact form; inactive"><div class="form-grid"><label>Your name <span>*</span><input type="text" placeholder="Full name" disabled></label><label>Email address <span>*</span><input type="email" placeholder="you@example.com" disabled></label></div><div class="form-grid"><label>Phone number<input type="tel" placeholder="Optional" disabled></label><label>What are you planning?<select disabled><option>I'm exploring options</option>{opts}</select></label></div><label>Tell us about your project <span>*</span><textarea disabled rows="7" placeholder="Where is the property? What would you like to change? Is there a timeline in mind?"></textarea></label><p class="lux-notice" role="note">Form is intentionally disabled on the visual-only GitHub Pages preview. Contact the studio directly using the email and phone details alongside.</p><a href="mailto:kristine@theluxurylook.com?subject=Project%20Enquiry" class="lux-btn">Email the studio <span>&nearr;</span></a></form></div>'''
    aside=f'''<aside class="lux-contact-aside"><div class="lux-contact-aside__art media-frame">{I(ctx,photo,'The Luxury Look design reference')}</div><div class="lux-contact-aside__text"><span class="lux-overline">Visit / Connect</span><h3>Let's meet<br><em>where you are.</em></h3><p>Studio appointments are arranged in advance. Reach out through the form or contact us directly.</p><dl><div><dt>Studio</dt><dd>4343 N Scottsdale Road<br>Suite 150, Scottsdale, AZ 85251</dd></div><div><dt>Phone</dt><dd><a href="tel:+16025786707">+1 (602) 578-6707</a></dd></div><div><dt>Email</dt><dd><a href="mailto:kristine@theluxurylook.com">kristine@theluxurylook.com</a></dd></div></dl></div></aside>'''
    return f'''<article class="lux-contact"><header class="lux-contact-hero"><div class="lux-contact-hero__inner lux-wrap"><p class="lux-overline">The Luxury Look / Project enquiries</p><h1>Every space has<br><em>a next chapter.</em></h1><p>Tell us what you're imagining. The best projects begin with an open conversation about what matters to you.</p><div><a href="#inquiry" class="lux-btn lux-btn--light">Share your project <span>&nearr;</span></a><span>SCOTTSDALE, ARIZONA / BY APPOINTMENT</span></div></div></header><section class="lux-contact-intro lux-wrap"><div><p class="lux-overline">01 / Start here</p><h2>We would love<br><em>to hear your ideas.</em></h2></div><p>From a single room to an entire property, you can start by sharing a few details. Your plans don't need to be final for us to have a useful conversation.</p></section><section id="inquiry" class="lux-contact-main lux-wrap">{form}{aside}</section><section class="lux-contact-next lux-wrap"><div><p class="lux-overline">02 / What comes next</p><h2>A conversation,<br><em>not a sales pitch.</em></h2></div><div class="lux-contact-next__steps"><div><span>01</span><h3>Share the essentials</h3><p>Tell us a little about the space and what you'd like to accomplish.</p></div><div><span>02</span><h3>We'll review your brief</h3><p>The studio can consider your goals and the type of project support you may need.</p></div><div><span>03</span><h3>Discuss the next step</h3><p>From there, we can explore the best direction for a consultation.</p></div></div></section></article>'''


def journal(ctx):
    posts=[x for x in ctx['LEGACY'] if x['path'].startswith('/blog/')]
    photo=ctx['EDITORIAL']['pages']['about']['image'];second=ctx['EDITORIAL']['pages']['about']['detail']
    cards=''
    for i,p in enumerate(posts):
        title=ctx['slug_title'](p['path'].split('/')[-1]);src=photo if i%2==0 else second
        cards+=f'''<a class="lux-journal-story lux-journal-story--{i%5}" href="{U(ctx,p['path'])}"><div class="lux-journal-story__photo media-frame">{I(ctx,src,'Temporary journal visual reference')}<span>READ THE STORY &nearr;</span></div><div class="lux-journal-story__info"><span class="lux-overline">The Luxury Look Journal</span><h3>{e(title)}</h3><p>Original editorial content is being carried over from the live website before publication.</p></div></a>'''
    return f'''<section class="lux-journal-hero"><div class="lux-wrap"><p class="lux-overline">The Luxury Look / Journal</p><h1>Notes on the art<br><em>of living well.</em></h1><p>Ideas on interiors, styling, staging and the spaces that shape our everyday lives.</p></div></section><section class="lux-journal-list lux-wrap"><div class="lux-heading-row"><div><p class="lux-overline">Ideas / Stories / Perspectives</p><h2>From the journal<span class="lux-dot">.</span></h2></div>{action(ctx,'portfolio','Explore the portfolio')}</div><div class="lux-journal-grid">{cards}</div></section>{conversion(ctx,'Make a space your own.','Explore how a considered design approach could shape your property.','View the services')}'''


def journal_post(x,ctx):
    title=ctx['slug_title'](x['path'].split('/')[-1]);photo=ctx['EDITORIAL']['pages']['about']['image']
    return f'''<article class="lux-article"><header class="lux-article-hero lux-wrap"><p class="lux-overline"><a href="{U(ctx,'blog')}">The Journal</a> / Design perspectives</p><h1>{e(title)}</h1><p>Original editorial content is being transferred from the existing website.</p><a href="#story" class="lux-text-link">Read the story &darr;</a></header><figure class="lux-article-feature media-frame">{I(ctx,photo,'Temporary journal hero photograph','eager')}<figcaption>THE LUXURY LOOK JOURNAL / {e(title)}</figcaption></figure><div class="lux-article-body lux-wrap" id="story"><aside><span class="lux-overline">Perspective / The Luxury Look</span><a href="{U(ctx,'blog')}">&larr; All journal stories</a></aside><div class="lux-richtext"><p>This is an editorial-layout preview. The original article and verified photography are being carried over from Webflow before publication.</p></div></div></article>{conversion(ctx,'Every space has a story waiting to be told.','Talk to our studio about yours.')}'''
