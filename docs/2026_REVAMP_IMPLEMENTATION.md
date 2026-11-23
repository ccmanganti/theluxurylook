# The Luxury Look — 2026 revamp implementation

Source: `2026 Website Revamp (2).pdf`, particularly slides 4–5, 8–25, 35–48, 67–73 and 118–119.

## Decisions brought into the rebuild

- Lead with Interior Design, Luxury Specs & Market-Ready (builders & agents), The Collection, Journal and forthcoming Curated Circle.
- Keep old staging and specialty service URLs; reduce their prominence on the top navigation without deleting them.
- Add clear, distinct working portfolio names for **54 projects**. Working names are not client identities or factual project titles, and remain hidden in production. The 54 pre-existing slugs and source SEO titles remain unchanged.
- A dedicated case-study CMS: client brief, scope, design approach, reorderable approved gallery, optional before images, source-backed metrics, results verification toggle, signed-off client testimonial.
- Preview images and working names are development-only. Do not use proof claims or present sample photography as work performed.
- Add Design, Builders & Agents, Collection and Curated Circle draft landing pages (noindex until approved); link the source-provided Heyzine furniture lookbook.
- Conditional inquiry fields: staging occupancy/listing date vs builder construction stage/property count, with shared property location, timeline and investment fields.
- Journal heading retained, and legacy blog routes preserved.

## Explicitly deferred

- No unsourced sales outcomes, $6M examples, 14-room renovation figures, award counts, 150+ flips, 1,550+ client relationships, endorsements or press logos until verified against original evidence and approved.
- Curated Circle member benefits, prices and specific opening dates remain draft concepts, not confirmed offers.
- Contact form and Filament are Laravel-only, not functional within GitHub Pages static review.
- Live Google Analytics, DNS, payment checkout and www.theluxurylook.com are untouched.

## Managing projects in Filament

1. Run the two additive migrations and `php artisan db:seed` (idempotent seed preserves edited records).
2. Open `/admin` -> Projects. Edit the *working project name* to review the public preview; do not change the original `slug`.
3. Set category, original title, approved cover, final gallery, project brief, design decisions, scope, before-gallery and verified outcomes.
4. Only enable `results_verified` when claims and quotes are documented; only enable `content_verified` after original URL, title, copy and media review.
5. In staging, approved gallery photos override placeholder photos. Production mode ignores staging-only imagery and working names.

## Launch SEO notes

The `legacy-sitemap.json` inventory retains all 96 original paths. The new `/design`, `/builders-and-agents`, `/collection` and `/curated-circle` routes remain noindexed until separately approved. No real-client content is silently renamed.
