# The Luxury Look — 2026 Website Revamp: Items 01–08
Status: development implementation and review preview. Not an approved production launch.
Source: 2026 Website Revamp (2).pdf, slides 1–25, 28–38, 118–119.
Branch: main. Production Webflow site remains untouched.

## Status definitions
- **Built** — component exists in Laravel/Filament source and, where relevant, the static design preview.
- **Partial** — structure exists but requires content, approval or operational setup.
- **Pending** — not implemented or not verifiable from supplied documentation.
No unapproved performance metric, testimonial, award, itinerary or price is claimed as verified.

### 01 / Brand & Navigation — Built, approval pending
- [x] Position the site around a luxury interior design house and Kristine Vowles.
- [x] Simplify the navigation: Design, Portfolio, Buying Trips, Collection, Journal, Explore and Work With Us.
- [x] Give Builders & Agents, Staging, Education, the Circle and Kristine routes in the Explore menu.
- [x] Keep Work With Us clearly visible and add related footer links.
- [ ] Owner approval of the primary navigation. Slide 4 lists a different candidate arrangement.
- [ ] Approved brand statement, visual identity and positioning. Local geography remains in structured business data and footer, not the hero tagline.
Prerequisite: Kristine's approval of final navigation and positioning. Verify trademark and approved press badges before displaying them.

### 02 / Homepage — Built layout, content partially complete
- [x] Design-first hero and Begin Your Project CTA; secondary Curated Circle pathway in review.
- [x] Existing named project selection and gallery.
- [x] Founder photo, introduction and published interview/video sections.
- [x] Three client pathways and sourcing/buying-trips feature.
- [x] New credibility/positioning strip and Journal preview.
- [x] Closing conversion CTA; no unstable video zoom and preview remains noindex.
- [ ] Approve homepage-specific photography, copy, press logos and verified press URLs.
- [ ] Resolve inconsistent client-relationship numbers and any 150+ build/flip claim; do not publish unsupported numbers.
- [ ] Confirm Curated Circle membership timing/benefits; newsletter platform integration is a separate phase.
Prerequisites: Kristine-approved hero photos, two verified video assets, press permissions, verified figures and final copy.

### 03 / Portfolio & Case Studies — CMS built; project data incomplete
- [x] Existing 54 project records and exact legacy URL slugs preserved.
- [x] Working presentation titles in preview, approved/original titles retained for SEO.
- [x] Project CMS supports editable cover/gallery uploads, brief, narrative, scope, before photos, metrics, verification flag, attribution, and order.
- [x] Immersive project pages with related projects and inquiry CTAs.
- [x] Dedicated Builder & Agents project-selection flag and case-study category.
- [ ] Source authentic approved before/after galleries, project names, locations, timelines and briefs.
- [ ] Verify Rosie Right Build and Design credit, Remodeler of the Year, 2025 NARI awards, property values and sales results.
- [ ] Obtain builder/client quotes and permission to publish.
- [ ] Evaluate four to six best case studies and remove illustrative imagery before launch.
Prerequisites: client/gallery folder access, license or ownership approval, project results and awards evidence, and case-study sign-off.

### 04 / Luxury Interior Design — Page built; service approval pending
- [x] Dedicated /design editorial page with distinctive hero and selected residences.
- [x] Four explanatory process phases for client clarity.
- [x] Consultation CTA and editable Page CMS sections.
- [ ] Confirm actual contracting scope, process names, procurement/drawings responsibilities and service commitments.
- [ ] Replace design imagery with approved project photos.
Prerequisites: approved service packages, professional workflow descriptions, supporting photography.

### 05 / Builders, Developers & Agents — Page built; case-study proof incomplete
- [x] Dedicated /builders-and-agents page, outcomes-first positioning and three service pathways.
- [x] Six **editorial candidate** projects selected for preview; not verified outcomes.
- [x] Curated project toggles and categories editable in Filament; no hardcoded performance claims.
- [x] Three-step process and dedicated builder inquiry CTA.
- [ ] Verify selected six projects truly represent new builds, staging and partnership outcomes.
- [ ] Add verified numbers (offers/sale price/days on market) only when documented.
- [ ] Add approved testimonials, construction stage, logistics/warehouse availability and coordination commitments.
- [ ] Expand the service-specific inquiry questionnaire in later Form Phase (slide 41).
Prerequisites: Kristine/builder agent approval, property permissions, measurable source records, process and turnaround commitments.

### 06 / Luxury Home Staging — New pathway built; detail approval pending
- [x] Dedicated /home-staging page, with services for vacant, occupied and spec/model homes.
- [x] Stage-specific gallery examples and direct consultation CTA.
- [x] Accessible from primary navigation's Explore menu.
- [ ] Verify staging examples, availability and gallery/quoted outcomes.
- [ ] Implement complete multi-step staging questionnaire (slides 45–48); current form has only a few conditional fields.
- [ ] Confirm staging inventory, warehouse logistics, pricing, minimum terms and consultation process.
Prerequisites: approved staging service scope, listing photographs, warehouse capacity and operations confirmation.

### 07 / The Collection — Page built; procurement and commerce pending
- [x] Dedicated /collection editorial furnishings and housewares page.
- [x] Turnkey, finishing-edit and sourcing paths; links to Buying Trips.
- [x] Live Heyzine reference flipbook linked, opens separately.
- [x] Contact path for interested clients, editable page sections.
- [ ] Confirm source/lookbook currency, product rights, availability, categories and pricing.
- [ ] Decide whether checkout, deposits and inventory syncing are required.
- [ ] Approve trade/designer pricing claims and fulfillment obligations.
Prerequisites: approved catalog, supplier terms, quote/procurement workflow and order-fulfillment owner.

### 08 / Buying Trips — Parent/destination hierarchy built; operational proof pending
- [x] /buying-trips is the parent service, with /buying-trips/dallas-market as one destination.
- [x] Other private sourcing and market discovery options are inquiry-based.
- [x] Distinct offerings editable in Filament with media, descriptions, gallery, CTA and publication state.
- [x] Additional destination information and inquiry journey are explicit.
- [ ] Verify destinations and eligibility, trade-show market access, departure dates, travel rules, safety/liability and group sizes.
- [ ] Agree trip scheduling, pricing, cancellation policies and booking/payment process.
- [ ] Approve actual trip photos and buying-trip film placement.
Prerequisites: Kristine's approved itineraries, market partner permissions, logistics, confirmed pricing and trip policies.

## Cross-cutting launch prerequisites
- [x] Legacy URLs kept; staging noindex; static Pages preview separated from production.
- [x] Static preview generated for all existing portfolio routes and new eight-phase landing pages.
- [x] GitHub CI checks cover the Laravel app and the public design preview.
- [ ] Deploy Laravel with PHP, storage, database and migrations; Filament does **not** operate on GitHub Pages.
- [ ] Configure SMTP/email, contact-form notifications, approved brand media and privacy policy.
- [ ] Test form delivery, gallery uploads, media performance, mobile accessibility, and CMS authorization in live PHP staging.
- [ ] Reconcile original Google Search Console, analytics IDs, sitemap, canonicals, redirected/deprecated routes and existing Webflow content.
- [ ] Secure final review/signoff before domain cutover.
