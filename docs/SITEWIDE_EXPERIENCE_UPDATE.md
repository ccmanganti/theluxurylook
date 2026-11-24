# The Luxury Look — Site-wide Experience Overhaul

**Development edition, November 2026**

## Client-facing direction

The updated experience is portfolio-first, design-first, and conversion-aware. Each type of page has a distinct visual structure: the homepage is a strong editorial introduction; portfolio collections use structured media crops and selectable disciplines; case studies foreground visual work and project outcomes; services use alternating layouts and a three-part process; Dallas Market has an immersive market/sourcing narrative; education uses interactive expandable programs; and journal and informational pages retain their editorial treatments.

The primary request is **Begin Your Project**. The page copy invites visitors to reach out, but does not fabricate urgency, scarcity, fees, outcomes, awards, client testimonials, or schedule promises.

## New editable content

- **Filament → Dallas & Education** (`ExperienceOfferingResource`): type, title, slug, audience, description, hero photo, optional media, gallery, program highlights, CTA, order, visibility, approval and SEO.
- **Filament → Website Pages**: editable sectional content, re-orderable sections, alternate layout mode, optional hero video + poster and CTA.
- **Filament → Projects**: working titles, client brief, process, scope, before/after photos, quotes and result metrics remain editable from the previous changes.
- `experience_offerings` seed initially contains four Dallas Market inquiry categories and four education/service learning tracks. All are draft/unverified. Do not publish programs or pricing as confirmed until reviewed by Kristine.

## Original and new URL handling

- Existing 96 Webflow-sitemap URLs retain their original paths in the application and preview inventory.
- Additional new routes: `/dallas-market`, `/dallas-market/{slug}`, `/education`, and `/education/{slug}`.
- The public GitHub Pages review is static and sets `noindex,nofollow,noarchive` and `robots.txt: Disallow /`; its forms do not submit data. The production Webflow website is untouched.
- New routes remain unindexed in the Laravel application pending launch approval. Verify canonical metadata and final page contents before cutover. GitHub Pages is not a substitute for PHP hosting/Filament.

## Image consistency rules

- Use explicit aspect-ratio media wrappers and `object-fit: cover` for listing and archive cards. Reserve width/height to avoid layout shift.
- Collection/archive cards use a balanced mosaic at desktop size and a consistent 4:3 crop on small screens.
- Project hero uses a cinematic crop; case-study gallery alternates full-width and side-by-side images intentionally.
- Final images should be re-exported to appropriate WebP/AVIF resolutions and compressed for their displayed width. Maintain descriptive alt text for **actual**, verified imagery. Placeholder captions must be removed before public launch.
- Final photos are managed through Filament and must be approved before marking project `content_verified=true`.

## Motion and background video

- Lightweight demo footage (`/public/media/atelier-motion.mp4`) is a **temporary illustrative example**, not a finished project or licensed approved production asset.
- Poster first, `preload=none`, video source set only when in the viewport using IntersectionObserver; loop is muted and inline with no audible sound. Pauses when not visible or page hidden.
- `prefers-reduced-motion: reduce` and browser `Save-Data` result in a static poster instead of autoplay. No video is rendered automatically to production until media is explicitly configured and approved.
- Reveal animation is progressive enhancement; pages remain fully usable without JavaScript.

## New content verification

- Dallas Market: support for personal sourcing/buying trips is described in Kristine's professional biography, but no public approved schedules, prices or itinerary guarantees were found. Display all as inquiry-based possibilities until source-approved.
- Education: the public website lists Interior Design & Home Staging Education, Business Growth & Strategy Training, Event & Workshop Development, and Real Estate & Airbnb Optimization. This design uses those categories as the starting point; workshop availability and instructors are pending verification.
- The user's **2026 Website Revamp** deck (in this conversation) prioritizes luxury interior design, market/builder work, buying trips, education and Curated Circle. Preserve its hierarchy; do not publish speculative success numbers or claims copied from mockups.

## Testing checklist

1. `python3 scripts/build_experience_preview.py` returns 118 preview pages, 54 uniquely named portfolio items, 4 Dallas offerings, 4 education tracks, and all original routes.
2. Confirm PHP lint, Laravel migration/feature tests in CI, and Filament admin can create/edit/delete offering and page sections on a real PHP host.
3. Verify 390px and 1440px mobile/desktop widths for homepage, portfolio, project, services, Dallas, education, about and contact without horizontal overflow.
4. Confirm all images/videos load, poster works with JS disabled, keyboard controls function, and motion reduces for accessibility.
5. Test inquiries, SMTP, consent/privacy, access controls, final photos, Search Console and analytics before launch. Do not adjust DNS until approved.
