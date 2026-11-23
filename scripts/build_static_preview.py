#!/usr/bin/env python3
"""Build a noindex visual-only preview from the Laravel site's seeded portfolio data.

NOT a PHP deployment. No forms, accounts, checkout, migrations or CMS are executed.
"""
from __future__ import annotations
import html
import json
import shutil
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
DEST = ROOT / 'preview-dist'
SEED = json.loads((ROOT / 'database/data/portfolio-seed.json').read_text())
CATEGORIES = SEED['categories']
PROJECTS = SEED['projects']
CATEGORIES_BY_SLUG = {c['slug']: c for c in CATEGORIES}


def e(v):
    return html.escape(str(v or ''), quote=True)


def uri(p):
    return './' if p == '/' else '/' + p.strip('/') + '/'


def link(path):
    # Relative links from a GitHub Pages project root, so https://USER.github.io/REPO works.
    return './' if path == '/' else './' + path.strip('/') + '/'


def shell(title, body, relative_base='./'):
    nav = [('Home', ''), ('Portfolio', 'portfolio/interior-design-projects/'), ('Services', 'services/'), ('Our Studio', 'about/'), ('Journal', 'blog/')]
    nav_links = ''.join(f'<a href="{relative_base}{p}">{e(t)}</a>' for t,p in nav)
    return f'''<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{e(title)} | The Luxury Look — Preview</title><meta name="robots" content="noindex,nofollow,noarchive">
<meta name="description" content="Private-stage visual preview of the redesigned The Luxury Look website. Production remains on Webflow.">
<meta name="theme-color" content="#f6f4f0">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{relative_base}assets/site.css"><script src="{relative_base}assets/site.js" defer></script>
<style>.preview-banner{{display:block!important;position:relative;z-index:90;padding:9px 12px!important}}.review-note{{padding:26px 4.25%;background:#f1eadc;color:#514836;font-size:14px;line-height:1.8}}.review-note strong{{font-weight:700}}.preview-no-form{{display:flex;align-items:center;gap:16px;padding:20px;border:1px solid #cabca8;background:#fff9eb;margin:30px 0}}.preview-page-header{{padding:100px 0 65px}}.preview-page-header h1{{font:600 clamp(52px,8vw,116px)/1.04 'Cormorant Garamond',Georgia,serif;margin:0}}.preview-page-header p{{max-width:750px;color:#4c514b;line-height:1.9}}.preview-tag{{font-size:12px;letter-spacing:.14em;text-transform:uppercase;color:#84745a}}.preview-grid{{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:24px}}.preview-grid img{{width:100%;aspect-ratio:4/4.7;object-fit:cover}}.preview-grid h3{{font:600 32px/1.2 'Cormorant Garamond',Georgia,serif;padding-top:14px}}.preview-grid a:hover img{{filter:brightness(.86)}}.preview-categories{{display:flex;flex-wrap:wrap;gap:12px;margin-bottom:45px}}.preview-categories a{{padding:10px 16px;border:1px solid #b8b1a7;font-size:12px}}.preview-category--active{{background:#26322e;color:#fff}}.preview-single-photo{{max-height:72vh;width:100%;object-fit:cover}}.preview-feature-list{{display:grid;grid-template-columns:repeat(2,1fr);gap:18px}}.preview-feature-list>a{{display:block;padding:35px;border:1px solid #ccc5b8;font:600 37px 'Cormorant Garamond',Georgia,serif}}@media(max-width:750px){{.preview-grid{{grid-template-columns:repeat(2,1fr)}}.preview-feature-list{{grid-template-columns:1fr}}}}@media(max-width:500px){{.preview-grid{{grid-template-columns:1fr}}}}</style>
</head><body><a href="#main" class="skip-link">Skip to content</a>
<div class="preview-banner">VISUAL DESIGN PREVIEW ONLY · LARAVEL / FILAMENT BACKEND NOT LIVE · NOINDEX</div>
<header class="site-header" id="site-header"><a class="wordmark" href="{relative_base}" aria-label="The Luxury Look home"><span class="wordmark__the">THE</span><span class="wordmark__main">LUXURY LOOK</span><span class="wordmark__tag">INTERIOR DESIGN &amp; STAGING</span></a>
<button class="nav-toggle" type="button" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation"><span></span><span></span></button>
<nav class="main-nav" id="main-nav" aria-label="Primary navigation">{nav_links}<a class="nav-contact" href="{relative_base}contact/">Start a Project <span aria-hidden="true">↗</span></a></nav></header>
<main id="main">{body}</main><footer class="site-footer"><div class="footer-intro"><p class="eyebrow eyebrow--light">We'd love to hear your ideas</p><h2>Let's create something<br><em>extraordinary.</em></h2><a href="{relative_base}contact/" class="pill-link pill-link--light">Start a conversation <span>↗</span></a></div>
<div class="footer-grid"><div><span class="footer-label">The Studio</span><p>4343 N Scottsdale Rd.<br>Suite 150<br>Scottsdale, AZ 85251</p><small>Visits by appointment only</small></div><div><span class="footer-label">Connect</span><a href="tel:+16025786707">+1 (602) 578-6707</a><a href="mailto:kristine@theluxurylook.com">kristine@theluxurylook.com</a></div><div><span class="footer-label">Explore</span><a href="{relative_base}services/">Our Services</a><a href="{relative_base}portfolio/interior-design-projects/">The Portfolio</a></div><div><span class="footer-label">Follow</span><a href="https://www.instagram.com/theluxurylook_/">Instagram ↗</a><a href="https://www.facebook.com/TheLuxuryLookLLC">Facebook ↗</a></div></div><div class="footer-bottom"><span>© The Luxury Look. All rights reserved.</span><span>Migration preview — design feedback welcomed</span></div></footer></body></html>'''


def write(path, content):
    target = DEST / path
    target.parent.mkdir(parents=True, exist_ok=True)
    target.write_text(content, encoding='utf-8')


def card(project, base='./'):
    c = CATEGORIES_BY_SLUG.get(project.get('category_slug',''), {})
    return f'<a class="project-card" href="{base}portfolios/{e(project["slug"])}/"><figure class="project-card__image"><img src="{e(project.get("cover_image"))}" alt="{e(project["title"])} — The Luxury Look" loading="lazy"><span class="project-card__overlay">Explore project ↗</span></figure><div class="project-card__meta"><div><span class="micro-label">{e(c.get("title","Selected Work"))}</span><h3>{e(project["title"])}</h3></div><span class="project-card__arrow">↗</span></div></a>'


def home():
    selected = [p for p in PROJECTS if p['slug'] in ['mockingbird-paradise-valley','desert-vista-luxury-design','old-town-scottsdale-airbnb','petersen-residence','manzanita-sedona-retreat','studio-3125']]
    if len(selected)<4:selected=PROJECTS[:6]
    hero='https://cdn.prod.website-files.com/61900fb94bdf48d160f4ffef/6930cdb203e0e2dbea07f32a_MOCKINGBIRD%20PARADISE%20VALLEY_6.png'
    second='https://cdn.prod.website-files.com/61900fb94bdf48d160f4ffef/6930cd2e6e621902d0d5006f_OLD%20TOWN%20SCOTTSDALE%20AIRBNB_24.jpg'
    cats=''.join(f'<a class="category-tile" href="./portfolio/{e(c["slug"])}/"><img src="{e(c.get("hero_image"))}" loading="lazy" alt="{e(c["title"])}"><span class="category-tile__scrim"></span><span class="category-tile__content"><span class="micro-label">The Portfolio</span><strong>{e(c["title"])}</strong><small>Explore ↗</small></span></a>' for c in CATEGORIES)
    services=['Interior Design','Home Staging','Interior Styling','Airbnb Staging','Commercial Staging','Corporate Branding']
    rows=''.join(f'<a href="./services/"><span class="service-num">{i+1:02d}</span><span class="service-title">{e(s)}</span><span class="service-desc">Discover our full-service approach</span><span class="service-arrow">↗</span></a>' for i,s in enumerate(services))
    return f'''<section class="hero hero--home"><div class="hero__visual"><img src="{hero}" fetchpriority="high" alt="Luxury interior designed by The Luxury Look"></div><div class="hero__veil"></div><div class="hero__content container"><p class="eyebrow eyebrow--light">THE ART OF TRANSFORMING SPACE &nbsp; / &nbsp; SCOTTSDALE, AZ</p><h1>Curated living.<br><em>Beautifully</em> imagined.</h1><p class="hero__lead">Distinguished interiors and thoughtfully styled spaces that reflect the people who live, work, and gather within them.</p><div class="hero__actions"><a class="pill-link pill-link--light" href="./portfolio/interior-design-projects/">Discover Our Work <span>↗</span></a><a class="subtle-link" href="./services/">Explore Services <span>→</span></a></div></div><div class="hero__bottom container"><span>DESIGN WITH INTENTION.</span><span>SCROLL TO EXPLORE ↓</span></div></section>
<section class="intro-section section-pad container"><div class="intro-section__side"><span class="eyebrow">01 / Our Philosophy</span><div class="line-accent"></div></div><div class="intro-section__main"><h2>We believe a space should be <em>experienced,</em> not just seen.</h2><div class="intro-section__bottom"><p>From luxurious residences to evocative commercial spaces, The Luxury Look brings design expertise, thoughtful collaboration, and an unmistakable attention to detail.</p><a class="text-link" href="./about/">Meet The Studio <span>↗</span></a></div></div></section>
<section class="featured-section section-pad"><div class="container"><div class="section-heading"><div><span class="eyebrow">02 / The Portfolio</span><h2>Spaces with <em>a story.</em></h2></div><a class="text-link" href="./portfolio/interior-design-projects/">Explore the Portfolio <span>↗</span></a></div><div class="project-grid">{''.join(card(p) for p in selected)}</div></div></section>
<section class="statements-section"><div class="statement-image"><img loading="lazy" src="{second}" alt="A curated Airbnb interior"></div><div class="statement-copy"><span class="eyebrow eyebrow--light">03 / Our Expertise</span><h2>Considered design.<br><em>Extraordinary</em><br>outcomes.</h2><p>We create complete environments by looking beyond appearances. Every material, proportion, and detail contributes to a seamless experience.</p><a class="pill-link pill-link--light" href="./services/">What We Do <span>↗</span></a></div></section>
<section class="services-section section-pad container"><div class="section-heading"><div><span class="eyebrow">04 / Services</span><h2>What we <em>do best.</em></h2></div><p class="section-subtitle">An integrated approach to beauty, function, and the feeling of being at home.</p></div><div class="services-list">{rows}</div></section>
<section class="category-section section-pad"><div class="container"><div class="section-heading"><div><span class="eyebrow">05 / Explore by Specialty</span><h2>One studio.<br><em>Many perspectives.</em></h2></div></div><div class="category-grid">{cats}</div></div></section><section class="about-callout section-pad container"><p class="eyebrow">The Founder</p><div><h2>Rooted in expertise.<br><em>Driven by passion.</em></h2><p>Led by founder and CEO Kristine Vowles, The Luxury Look blends decades of experience with a distinctive eye for possibilities.</p><a class="text-link" href="./about/">Get to Know Us <span>↗</span></a></div></section>'''


def main():
    if DEST.exists():shutil.rmtree(DEST)
    shutil.copytree(ROOT/'public/assets',DEST/'assets')
    (DEST/'.nojekyll').write_text('')
    write('robots.txt','User-agent: *\nDisallow: /\n')
    write('index.html',shell('Home',home()))
    categories_nav=''.join(f'<a href="../../portfolio/{e(c["slug"])}/">{e(c["title"])}</a>' for c in CATEGORIES)
    for c in CATEGORIES:
        base='../../'
        menu=''.join(f'<a class="{"preview-category--active" if other["slug"]==c["slug"] else ""}" href="{base}portfolio/{e(other["slug"])}/">{e(other["title"])}</a>' for other in CATEGORIES)
        items=[p for p in PROJECTS if p.get('category_slug')==c['slug']]
        body=f'<section class="container section-pad"><div class="preview-page-header"><p class="preview-tag">The Portfolio / Selected work</p><h1>{e(c["title"])}.</h1><p>{e(c.get("description") or c.get("tagline"))}</p></div><div class="preview-categories">{menu}</div><div class="project-grid">{"".join(card(p,base) for p in items)}</div></section>'
        write(f'portfolio/{c["slug"]}/index.html',shell(c['title']+' Portfolio',body,base))
    for p in PROJECTS:
        base='../../'
        category=CATEGORIES_BY_SLUG.get(p.get('category_slug',''),{})
        body=f'<section class="container section-pad"><div class="preview-page-header"><p class="preview-tag">{e(category.get("title","Portfolio"))} / Project</p><h1>{e(p["title"])}.</h1><p>{e(p.get("summary") or "A design project by The Luxury Look.")}</p></div><img class="preview-single-photo" src="{e(p.get("cover_image"))}" alt="{e(p["title"])}"><div class="review-note"><strong>Photo gallery still being migrated.</strong> This design preview shows the indexed project cover; original project photography and case-study details require content verification before launch.</div><p style="padding-top:35px"><a class="text-link" href="{base}portfolio/{e(category.get("slug","interior-design-projects"))}/">← Back to Portfolio</a></p></section>'
        write(f'portfolios/{p["slug"]}/index.html',shell(p['title'],body,base))
    services=''.join(f'<a href="../portfolio/{e(c["slug"])}/">{e(c["title"])} <span>↗</span></a>' for c in CATEGORIES)
    write('services/index.html',shell('Our Services',f'<section class="container section-pad"><div class="preview-page-header"><span class="preview-tag">What we do</span><h1>Design with intention.<br><em>Every detail considered.</em></h1><p>Interior design, staging, styling and environments that bring client visions to life.</p></div><div class="preview-feature-list">{services}</div></section>','../'))
    write('about/index.html',shell('Our Studio','<section class="container section-pad"><div class="preview-page-header"><span class="preview-tag">Our Philosophy</span><h1>The Luxury Look.</h1><p>Led by founder and CEO Kristine Vowles, our Scottsdale-based studio creates extraordinary interiors and thoughtfully staged spaces.</p></div><div class="review-note">Full biographies, awards, team imagery, and history are pending content migration.</div></section>','../'))
    write('blog/index.html',shell('Journal','<section class="container section-pad"><div class="preview-page-header"><span class="preview-tag">Stories / Ideas / Inspiration</span><h1>The Journal.</h1><p>Editorial stories and insights from The Luxury Look. Legacy article pages and content are being migrated.</p></div><div class="review-note">Visual preview only — the original editorial archive is retained in the Laravel route inventory, but article bodies are not yet available here.</div></section>','../'))
    write('contact/index.html',shell('Contact','<section class="container section-pad"><div class="preview-page-header"><span class="preview-tag">Let’s start a project</span><h1>Let’s make something<br><em>extraordinary.</em></h1><p>We would love to hear about your space. Visits to our Scottsdale studio are by appointment only.</p></div><div class="preview-no-form">This is a static design preview. Contact forms are disabled here. Please contact the studio directly.</div><p>4343 N Scottsdale Rd. Suite 150, Scottsdale, AZ 85251<br><a href="tel:+16025786707">+1 (602) 578-6707</a><br><a href="mailto:kristine@theluxurylook.com">kristine@theluxurylook.com</a></p></section>','../'))
    print('Static visual-only preview generated:',len(list(DEST.rglob('index.html'))),'pages')

if __name__=='__main__':main()
