# The Luxury Look / Client-focused internal-page redesign

## Principles

1. Make the work the most compelling evidence: large editorial spreads, galleries, and intuitive browsing across preserved legacy URLs.
2. Make the service tangible to a prospective client: audience, scope, approach, related work and an unambiguous inquiry step.
3. Make Kristine's perspective visible: the founder story, real-estate background, Scottsdale studio and verifiable company founding in 2013.
4. Do not invent client testimonials, award rankings, project transformations, measurable results, materials or budgets.
5. Treat temporary photographs as layout references, **not** evidence of specific completed projects.
6. Keep permanent site content tied to Filament image uploads. The static preview never handles real leads or CMS login.

## Changed routes (original paths preserved)

- `/portfolio`: editorial archive with filters and asymmetrical project grid.
- `/portfolio/{original-category-slug}`: distinct image-led collection introductions, visual feature spread, full collection, design considerations and inquiry CTA.
- `/portfolios/{original-project-slug}`: large photography, project context, full 8-image layout gallery with lightbox, related projects and inquiry CTA.
- `/services`: large format editorial service catalog.
- `/services/{service-slug}`: client-focused brief, fit, scope, process, related cases and contact preselection.
- `/about`: founder-led editorial story, verified founding year, and company philosophy.
- `/the-studio`: Scottsdale studio address, appointments and experience.
- `/awards`: recognition layout without inventing unverified award claims.
- `/contact`: real Laravel inquiry form, client briefing, contact channels and next steps. Static preview uses disabled example fields plus direct mailto.
- `/blog` and `/blog/{slug}`: image-led editorial journal with original posts withheld until migration.
- Other legacy pages: photography-led editorial layout without claiming unverified product availability.

## Replacing assets and copy

- Project and category images use existing Filament upload fields; project gallery image slots are already editable.
- Service hero images use existing Filament fields; case selections derive from related work.
- Page hero images use `Page.hero_image` with a config-managed editorial fallback.
- For positioning text, edit `database/data/editorial-content.json` (source of truth shared with static preview) and review before launch.
- The visual-only build consumes `scripts/editorial_preview.py` and copies `public/assets/editorial.css` to GitHub Pages.

## Before production migration

- Verify 96 original URLs and SEO tags against Webflow and transfer complete old bodies and images.
- Confirm verified proof (press, awards, founder biography, experience claim) before use.
- Publish approved real portfolio images, remove preview disclaimers, run image alt-text review and WCAG checks.
- Test mobile & tablet photo loading, accessible forms, galleries, page speed and Search Console.
- Do not change live DNS or switch `SITE_LAUNCH_READY=true` based solely on design review.
