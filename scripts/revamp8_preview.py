"""Eight 2026 website revamp workstreams: static review renderer."""
def page(kind,d):
 e,u,a,p=d['E'],d['U'],d['A'],d['pill']
 pro=d['PROJECTS']; cover=d['photourl'];title=d['title']
 def project_tiles(items):
  return ''.join(f'<a class="r8-builder-case" href="{u("portfolios/"+x["slug"])}"><div><img loading="lazy" width="940" height="720" src="{e(cover(x))}" alt="Temporary photography for {e(title(x))}"></div><h3>{e(title(x))}</h3><b>View Case Study ↗</b></a>' for x in items)
 def finish(t,cta='Begin Your Project'):
  return f'<section class="r8-quote-strip"><div class="x-wrap"><span class="x-eyebrow">THE NEXT STEP</span><h2>{t}</h2>{p("contact",cta,True)}</div></section>'
 if kind=='design':
  items=[x for x in pro if x.get('category_slug')=='interior-design-projects'][:3]
  hero=f'<header class="r8-design-hero x-wrap"><div class="r8-design-copy"><span class="x-eyebrow">LUXURY RESIDENTIAL INTERIOR DESIGN</span><h1>More than a beautiful home.<br><em>A way of living.</em></h1><p>Full-service design for distinctive residences, second homes and thoughtful renovations, shaped around the people who live there.</p>{p("contact","Begin Your Project")}</div><div class="r8-design-image"><img src="{e(cover(items[0]))}" width="920" height="1160" alt="Temporary design portfolio photography"></div></header>'
  steps=[('Discovery','We begin with how you live and the priorities that matter.'),('Direction','Design ideas, materials and furnishings begin to connect.'),('Design & coordination','We discuss collaborators, specifications and timelines.'),('Finishing touches','Art, lighting, textiles and thoughtful details complete the experience.')]
  process='<section class="r8-process"><div class="x-wrap"><div class="r8-section-heading"><span class="x-eyebrow">02 / OUR PROCESS</span><h2>From the first thought<br><em>to the final detail.</em></h2></div><div class="r8-process-grid">'+''.join(f'<article><span>0{i}</span><h3>{n}</h3><p>{v}</p></article>' for i,(n,v) in enumerate(steps,1))+'</div></div></section>'
  work=f'<section class="r8-work-preview x-wrap x-section"><div class="r8-section-heading"><span class="x-eyebrow">03 / SELECTED RESIDENCES</span><h2>A closer look<br><em>at the work.</em></h2></div><div class="r8-work-grid">{project_tiles(items)}</div></section>'
  return '<article class="r8-page r8-design">'+hero+process+work+finish('Design for<br><em>the way you live.</em>')+'</article>'
 if kind=='builders-and-agents':
  slugs=['nichols-project---9-gold-awards','petersen-residence','mockingbird-paradise-valley','occupied-home-staging','13-5-million-dollar-property','prof-sports-client-featured-on-nbc']
  lookup={x['slug']:x for x in pro};items=[lookup[s] for s in slugs if s in lookup]
  hero=f'<header class="r8-builder-hero"><div class="r8-builder-cover"><img width="1600" height="900" src="{e(cover(items[0]))}" alt="Temporary luxury property visual"><div class="r8-builder-overlay"></div></div><div class="x-wrap r8-builder-lead"><span class="x-eyebrow">BUILDERS / DEVELOPERS / AGENTS</span><h1>Designed to sell.<br><em>Styled to be remembered.</em></h1><p>Design direction and property presentation for distinctive homes at important market moments.</p>{p("contact","Discuss a Property",True)}</div></header>'
  examples=f'<section class="r8-builder-cases x-wrap x-section"><div class="r8-section-heading"><span class="x-eyebrow">03 / SELECTED EXAMPLES</span><h2>Properties with<br><em>a point of view.</em></h2><p>Candidate projects for design review. No unverified sales results are claimed.</p></div><div class="r8-builder-case-grid">{project_tiles(items)}</div></section>'
  return '<article class="r8-page">'+hero+_offers([('Luxury specs & model homes','A considered presentation for new construction.'),('Luxury staging','Market-ready design for vacant and occupied properties.'),('Project partnerships','Timing, coordination and logistics aligned with your team.')])+examples+finish('A property worth<br><em>remembering.</em>','Discuss a Property')+'</article>'
 if kind=='home-staging':
  items=[x for x in pro if x.get('category_slug')=='home-staging-projects-the-luxury-look'][:4]
  hero=f'<header class="r8-staging-hero x-wrap"><div class="r8-staging-copy"><span class="x-eyebrow">LUXURY HOME STAGING</span><h1>A home that is<br><em>ready to be felt.</em></h1><p>Thoughtful staging for luxury properties and their next chapter.</p>{p("contact","Request a Staging Consultation")}</div><div class="r8-staging-visual"><img src="{e(cover(items[0]))}" width="1200" height="1100" alt="Temporary home staging photography"></div></header>'
  cases=f'<section class="x-wrap x-section"><div class="r8-section-heading"><span class="x-eyebrow">03 / SELECTED STAGING WORK</span><h2>Spaces with<br><em>something to say.</em></h2></div><div class="r8-work-grid">{project_tiles(items)}</div></section>'
  return '<article class="r8-page">'+hero+_offers([('Vacant luxury homes','Transform empty rooms into an inviting vision.'),('Occupied seller refresh','Elevate existing spaces before photography and listing.'),('Spec and model homes','Property presentation for builders and agents.')])+cases+finish('Your property’s<br><em>next chapter.</em>','Request a Staging Consultation')+'</article>'
 if kind=='collection':
  image=e(d['STOCK'][3]);link='https://heyzine.com/flip-book/a228d0b91d.html#page/78'
  hero=f'<header class="r8-collection-hero x-wrap"><div class="r8-collection-visual"><img src="{image}" width="950" height="1100" alt="Temporary furnishing inspiration"></div><div class="r8-collection-copy"><span class="x-eyebrow">THE COLLECTION / FURNITURE & HOUSEWARES</span><h1>The room is made<br><em>in the details.</em></h1><p>Curated furnishings and housewares for homes worth enjoying from the first day.</p><div class="r8-actions"><a class="x-pill" href="{link}" target="_blank" rel="noopener noreferrer">Explore the Lookbook ↗</a>{a("contact","Discuss Furnishings")}</div></div></header>'
  extra=f'<section class="r8-lookbook x-wrap x-section"><div><span class="x-eyebrow">THE FURNITURE & HOUSEWARES LOOKBOOK</span><h2>Discover the<br><em>possibilities.</em></h2><p>Current reference selections are available in the lookbook. Product availability, trade terms, fees and logistics are confirmed per project.</p><a class="x-pill" href="{link}" target="_blank" rel="noopener noreferrer">View the Lookbook ↗</a></div><div class="r8-lookbook-art"><img src="{image}" width="850" height="650" alt="Illustrative furnishings" loading="lazy"></div></section>'
  return '<article class="r8-page">'+hero+_offers([('Turnkey residences','Coordinated furnishings for new and second homes.'),('The finishing edit','Art, lighting and housewares thoughtfully connected.'),('Buying Trips','A wider world of sourcing and discovery.')])+extra+finish('Beautifully<br><em>complete.</em>')+'</article>'
 return ''

def _offers(items):
 return '<section class="r8-builder-services x-wrap x-section"><div class="r8-section-heading"><span class="x-eyebrow">02 / HOW WE HELP</span><h2>A thoughtful way<br><em>to begin.</em></h2></div><div class="r8-builder-services-grid">'+''.join(f'<article><span>0{i}</span><h3>{n}</h3><p>{v}</p></article>' for i,(n,v) in enumerate(items,1))+'</div></section>'

def home(base,d):
 proof='<section class="r8-journey-proof"><div class="x-wrap"><div class="r8-section-heading"><span class="x-eyebrow">DESIGN / REAL ESTATE / INTENTION</span><h2>Design that starts with<br><em>understanding you.</em></h2></div><div class="r8-journey-proof-grid"><div><strong>Design House</strong><p>Full-service residential interior design.</p></div><div><strong>Built for living</strong><p>Thoughtful layout, furnishing and finishing details.</p></div><div><strong>Led by Kristine</strong><p>Experience in both design and real estate.</p></div></div></div></section>'
 base=base.replace('<section class="x-introduction',proof+'<section class="x-introduction',1)
 base=base.replace('Explore the work','Explore The Curated Circle',1)
 base=base.replace('href="'+d['U']('portfolio')+'">Explore The Curated Circle','href="'+d['U']('curated-circle')+'">Explore The Curated Circle',1)
 links=f'<section class="r8-home-nav x-wrap">{d["A"]("home-staging","Luxury Home Staging")} {d["A"]("builders-and-agents","For Builders & Agents")} {d["A"]("collection","Explore The Collection")}</section>'
 base=base.replace('<section class="x-dallas-teaser',links+'<section class="x-dallas-teaser',1)
 journal='<section class="r8-home-journal"><div class="x-wrap"><div class="r8-section-heading"><span class="x-eyebrow">THE JOURNAL / DESIGN & LIVING</span><h2>Ideas worth<br><em>living with.</em></h2></div><div class="r8-journal-grid"><article><span class="x-eyebrow">DESIGN</span><h3>Thoughts on home and the way we live.</h3><a href="'+d["U"]("blog")+'">Explore the Journal ↗</a></article><article><span class="x-eyebrow">TRAVEL</span><h3>Where considered discoveries begin.</h3><a href="'+d["U"]("buying-trips")+'">Buying Trips ↗</a></article><article><span class="x-eyebrow">THE STUDIO</span><h3>Meet the person behind the work.</h3><a href="'+d["U"]("about")+'">Meet Kristine ↗</a></article></div></div></section>'
 return base.replace('<section class="x-next-cta',journal+'<section class="x-next-cta',1)

def buying(base,d):
 add='<section class="r8-manifesto x-wrap x-section"><span class="x-eyebrow">BUYING TRIPS / PERSONALLY CURATED</span><div><h2>Know what you are looking for.<br><em>Discover what is possible.</em></h2><p>Dallas Market is one destination within a wider experience. Every journey starts with the project and the selections that matter.</p></div></section>'
 return base.replace('<section class="x-section x-wrap" id="experiences">',add+'<section class="x-section x-wrap" id="experiences">',1)
