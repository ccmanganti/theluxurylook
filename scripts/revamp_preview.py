"""Visual-only revamp pages and navigation, combined with original SEO-safe preview.

No CMS writes, payment buttons, real waitlist submission or press claims in GitHub Pages.
"""
from __future__ import annotations
import html,re

def esc(v):return html.escape(str(v or ''),quote=True)

def nav(url):
    pages=[('Design','design'),('Portfolio','portfolio'),('The Collection','collection'),('Journal','blog'),('The Curated Circle','curated-circle'),('About','about')]
    items=''.join(f'<a href="{url(p)}">{esc(name)}</a>' for name,p in pages)
    return '<nav id="primary-nav" class="primary-nav" aria-label="Main navigation">'+items+f'<a class="nav-outline" href="{url("contact")}">Work With Us <span aria-hidden="true">↗</span></a></nav>'

def shell(original_html,url):
    s=re.sub(r'<nav id="primary-nav" class="primary-nav" aria-label="Main navigation">.*?</nav>',nav(url),original_html,flags=re.S)
    base=url('')
    s=s.replace(f'<link rel="stylesheet" href="{base}assets/site.css">',f'<link rel="stylesheet" href="{base}assets/site.css"><link rel="stylesheet" href="{base}assets/editorial.css"><link rel="stylesheet" href="{base}assets/revamp.css">')
    s=s.replace(f'<script defer src="{base}assets/site.js"></script>',f'<script defer src="{base}assets/site.js"></script><script defer src="{base}assets/revamp.js"></script>')
    return s

def pill(ctx,path,label): return f'<a class="lux-btn" href="{ctx["url"](path)}">{esc(label)} <span>↗</span></a>'

def cta(ctx,title,path='contact',label='Begin your project'):
    return f'<section class="revamp-editorial-cta"><div class="lux-wrap"><p class="lux-overline">The Luxury Look / An invitation</p><h2>{esc(title)}</h2>{pill(ctx,path,label)}</div></section>'

def tile(ctx,path,head,body,label):
    return f'<a href="{ctx["url"](path)}"><h2>{esc(head)}</h2><p>{esc(body)}</p><span class="lux-text-link">{esc(label)} ↗</span></a>'

def hero(eyebrow,heading,summary,button):
    return f'<header class="revamp-pillar-hero"><div class="lux-wrap"><p class="lux-overline">{esc(eyebrow)}</p><h1>{heading}</h1><p>{esc(summary)}</p>{button}</div></header>'

def design(ctx):
    intro=hero('The Luxury Look / Design','Designed for the way<br><em>luxury is lived.</em>',
      'Interior design for primary residences, second homes and select builder projects. Led by Kristine Vowles, with more than 35 years of design and real estate experience.',pill(ctx,'contact','Begin your project'))
    tiles=''.join([tile(ctx,'services/interior-design','Full-service design','New builds, thoughtful renovations and interiors with an individual point of view.','Explore the service'),tile(ctx,'portfolio/interior-design-projects','Selected residences','A closer look at the rooms and spaces in our visual archive.','View selected work'),tile(ctx,'collection','The Collection','Furnishings and housewares thoughtfully considered together.','Explore the lookbook')])
    return intro+f'<section class="revamp-pillars lux-wrap"><p class="lux-overline">Design / Full service and considered detail</p><div class="revamp-pillars__grid">{tiles}</div></section>'+cta(ctx,'Every extraordinary home begins with a conversation.')

def builders(ctx):
    intro=hero('For builders / developers / agents','Designed to sell.<br><em>Staged to be remembered.</em>',
      'Luxury specs and market-ready interiors for thoughtful new construction, vacant homes and occupied listings.',pill(ctx,'contact','Request a staging conversation'))
    related=[p for p in ctx['PROJECTS'] if p.get('category_slug')=='home-staging-projects-the-luxury-look'][:6]
    cards=''.join(ctx['work_card'](p) for p in related)
    cards=f'<div class="work-grid work-grid--related">{cards}</div>'
    tiles=''.join([tile(ctx,'services/luxury-residential-staging','Luxury home staging','Occupied, vacant and select-room staging approached with a buyer in mind.','See staging'),tile(ctx,'portfolio/home-staging-projects-the-luxury-look','Market-ready spaces','Study the styling and presentation of different properties.','View the portfolio'),tile(ctx,'contact','Builder partnerships','Discuss project logistics, timing and level of involvement.','Get in touch')])
    return intro+f'<section class="revamp-pillars lux-wrap"><p class="lux-overline">The approach</p><div class="revamp-pillars__grid">{tiles}</div></section><section class="lux-related lux-wrap"><div class="lux-heading-row"><div><p class="lux-overline">Illustrative project archive</p><h2>Where buyers can picture a life.</h2></div></div>{cards}<p class="revamp-note">Project outcome numbers and testimonial quotes are only published after verification.</p></section>'+cta(ctx,'Show your property at its strongest.','contact','Discuss a listing')

def collection(ctx):
    intro=hero('The Luxury Look / Collection','Every detail<br><em>makes a difference.</em>',
      'Furniture and housewares curated with the same careful eye as our residential projects. The collection is being prepared for its next chapter.',
      '<a class="lux-btn" target="_blank" rel="noopener noreferrer" href="https://heyzine.com/flip-book/a228d0b91d.html#page/78">View the lookbook <span>↗</span></a>')
    tiles=''.join([tile(ctx,'design','Designed with purpose','Bring cohesion to a new home or a finishing refresh.','Explore design'),tile(ctx,'contact','Turnkey residences','Discuss complete furnishing and housewares for a second home.','Start a conversation'),tile(ctx,'curated-circle','The Curated Circle','Learn about the forthcoming design community.','Discover more')])
    return intro+f'<section class="revamp-pillars lux-wrap"><p class="lux-overline">Beyond a room / A finished home</p><div class="revamp-pillars__grid">{tiles}</div><p class="revamp-note">Browse-only review: no checkout or confirmed stock/pricing on the preview.</p></section>'

def circle(ctx):
    intro=hero('Forthcoming / The Luxury Look','The Curated<br><em>Circle.</em>',
      'A forthcoming home, design and lifestyle community for people who believe the details matter. Member benefits and timing will be announced when confirmed.',pill(ctx,'contact','Register your interest'))
    tiles=''.join([tile(ctx,'collection','Curated finds','Explore the vision for considered furniture and housewares.','Explore the Collection'),tile(ctx,'blog','Notes from the studio','Interiors, travel, food and thoughtful living.','Read the Journal'),tile(ctx,'contact','Stay in the loop','Connect with the studio while membership details are being finalized.','Contact us')])
    return intro+f'<section class="revamp-pillars lux-wrap"><p class="lux-overline">The proposed experience / Subject to confirmation</p><div class="revamp-pillars__grid">{tiles}</div></section>'

def contact(original):
    # Static demo can be explored, but it must not submit or simulate a successful inquiry.
    insert="""<div class="revamp-inquiry-fields" data-conditional-inquiry><label>How may we help?<select id="inquiry-service"><option value="">Choose a service</option><option>Custom Home Design</option><option>Luxury Home Staging</option><option>Builder or Developer Design Partnership</option><option>Turnkey Furniture & Housewares</option><option>The Curated Circle</option></select></label><div data-inquiry-branch="staging" hidden><label>Is the home occupied?<select><option>Vacant</option><option>Occupied</option></select></label><label>Anticipated listing date<input placeholder="Date or approximate month" type="text"></label></div><div data-inquiry-branch="builder" hidden><label>Construction status<input placeholder="Planning or underway"></label><label>Number of properties<input type="number" min="1"></label></div><p class="revamp-note">Preview fields are illustrative and do not submit. More detailed, secure inquiries are available in the Laravel application.</p></div>"""
    return original.replace('<div class="form-grid">',insert+'<div class="form-grid">',1)

def case(original,p):
    # All 54 preview projects demonstrate the future narrative structure, not fictional results.
    section="""<section class="revamp-case-study lux-wrap"><div class="revamp-case-study__intro"><p class="lux-overline">02 / The commission</p><h2>A story behind<br><em>every decision.</em></h2></div><div class="revamp-case-study__columns"><div><span>01 / The brief</span><h3>What did the space need?</h3><p>A verified client brief and project goals will be added through the Filament CMS.</p></div><div><span>02 / The design</span><h3>How it came together</h3><p>Materials, furnishings, floor-plan decisions and collaboration can be recorded individually.</p></div><div><span>03 / The impact</span><h3>The result</h3><p>Verified before-and-after photos, scope and measured outcomes will appear here only when supplied and approved.</p></div></div></section>"""
    return original.replace('<section class="lux-gallery-section"',section+'<section class="lux-gallery-section"',1)

def extra_pages(ctx):
    for slug,title,fn in [('design','Design',design),('builders-and-agents','Builders and Agents',builders),('collection','The Collection',collection),('curated-circle','The Curated Circle',circle)]:
        ctx['write'](slug+'/index.html',ctx['shell'](title,fn(ctx),'Development review only - not a public offering or verified project record.'))
