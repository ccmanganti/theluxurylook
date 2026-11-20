# The Luxury Look - Webflow to Laravel Migration Runbook

Source: `https://www.theluxurylook.com/sitemap.xml` (inspected November 20, 2026).

## 1. Scope and objectives

**96 URL entries** in the Webflow sitemap have been inventoried and seeded at their exact URI paths. Distinctions include `/portfolio/...` category indexes and `/portfolios/...` case studies. Do not replace these with generic `/work/...` slugs without a documented mapping and 301s. Preserve the misspelled `/portfolio/corporate-brandding-projects-the-luxury-look` as-is for SEO compatibility.

Additional links surfaced on the current live site but were absent from the XML sitemap: the Nichols Project case study and the November 6, 2026 Halloween-to-fall blog post. They are tracked separately for migration review.

SEO improvement with **zero recrawl** is not possible. Google will crawl and reevaluate the website after the backend changes. What we can preserve is the public host, URLs, status codes, canonical identity, page headings, main copy, images, structured data and signals that minimize ranking volatility.

## 2. Baseline: before DNS changes

1. Export the entire Webflow CMS: all portfolio entries, gallery images, original blog bodies, excerpts, publication dates, authors, legal copy, product pages, static pages and all media assets. Fetch original assets, not just CDN derivative URLs.
2. Archive current sitemap XML, robots.txt, current response headers, redirects, the URL list, and every page's canonical/title/meta description/OG tags. Capture sitemap lastmod if supplied, every 200/301/404/410, and internal-link graph.
3. From Google Search Console export indexed pages, performance (queries, pages, last 16 months), coverage, sitemap report and current verified property methods. Capture Google Analytics 4 Measurement ID and Google Tag Manager container ID, pixels, consent configuration, Google Business Profile link, GSC verification token.
4. Identify current contact forms, eCommerce/digital product fulfilment, PayPal checkout, appointment links, social integrations, reCAPTCHA, email routing and automation/webhook endpoints. None may be lost in cutover.
5. Preserve DNS records for mail: MX, TXT (SPF/DKIM/DMARC), and domain verification. Only change web-serving A/AAAA/CNAME records as required by the new hosting provider.

## 3. Build / preview

1. Install Laravel 12 + Filament 5 and seed the URL inventory, content, categories, project metadata and pages.
2. Keep `SITE_LAUNCH_READY=false`, app on a private staging URL and preferably basic authentication too. Validate `X-Robots-Tag: noindex, nofollow` and `robots.txt` `Disallow: /` on staging. Do not submit a staging sitemap to Search Console.
3. Move all Webflow image files to storage/S3 and rewrite URLs to your owned asset host. Optimize responsive formats, dimensions, alt text, filenames and caching. External Webflow CDN image URLs in the demo data are temporary placeholders.
4. Recreate ALL original article content and rich case-study details: captions, headings, internal links and image galleries; maintain article publication dates. Verify asset ownership, accessibility and all mobile breakpoints.
5. Set `content_verified=true` only after accurate original content, meta tags, image alt and full gallery review. Set original `legacy_urls.status=ready` only when its exact URI is live and complete, and 301 only for deliberate renames. Do not treat empty template pages, checkout shells or dummy product entries as ready.
6. Confirm non-legacy `/services/{slug}` pages remain noindexed until separately approved. `SITE_INDEX_NEW_PAGES=false` is the default; newly created pages must undergo a separate SEO launch review before opting in. Prioritize the existing portfolio/service routes and their SEO signals first.

## 4. Cutover gate

- `php artisan migration:audit` exits 0 with zero unreviewed original URLs. Document deliberate `410` removals or `301` redirects, particularly Webflow template pages, demo products and checkout endpoints. Confirm important pages return 200.
- Crawler diff: old vs staging URL by URL, match HTML title/meta description/canonical/heading hierarchy, schema, image paths, internal links, statuses and key content. Check all 96 original URLs plus discovered extras, and include links not in sitemap.
- Verify desktop/mobile performance, image alt and captions, accessible nav, form submissions + email, analytics/tag manager, Search Console verification, conversion events, social pixels, consent settings and security headers.
- Confirm all payment pathways and digital-product delivery are restored or formally retired with stakeholder approval. The initial starter does NOT process sales.
- Restore business integrations on the new host; test 24h before switch; back up Webflow and database. Set modest DNS TTL in advance and prepare rollback DNS target.

## 5. Go live

1. Provision supported production PHP + database, HTTPS, queue/cache/storage, backups and monitoring. Point document root to `public/` only. Configure `APP_ENV=production`, `APP_DEBUG=false`, set HTTPS `APP_URL` and the same `SITE_CANONICAL_HOST=www.theluxurylook.com`.
2. Once all launch checks pass, set `SITE_LAUNCH_READY=true`, clear config/route/view cache, and deploy the final content. Confirm `robots.txt` allows crawling and sitemap.xml includes only approved canonical 200 routes.
3. Keep `https://www.theluxurylook.com` as canonical; 301 redirect other hostname scheme variations to the existing preferred www HTTPS host, without adding redirect chains. Preserve Search Console verified ownership and implement the original GA4/Tag Manager IDs, not new properties unless necessary.
4. Update web DNS records without touching mail/DNS verification records. Watch traffic and errors; don't take down Webflow source assets before the company-owned copies are verified.
5. Resubmit the SAME-domain `https://www.theluxurylook.com/sitemap.xml` in Search Console. You do not need the Change of Address tool for a hosting-only change that preserves the domain.

## 6. After launch

- Monitor crawl coverage, ranking/queries, canonical selection, 404/5xx, mobile performance and form leads daily in the first week, then weekly for 1-2 months. Compare with prelaunch baselines.
- Keep 301 mappings stable and permanent; fix link issues before large-scale content redesign. Avoid changing slug, headline, title and substantive body content at the same moment as the hosting migration if possible.
- Use the new Filament admin to polish portfolios and services in follow-up iterations after the migration is stable.

## 7. Known limitations of this starter

- No deployed host, DNS change, Google Search Console login or Google Analytics connection has been performed.
- The code has route shells, some verified company copy, article URL/title stubs, and partial original portfolio cover references, **not** a pixel-perfect or content-complete Webflow export.
- The repo contains no copied Webflow binary imagery or full galleries. Final imagery must be obtained through the company's own asset export, not hotlinked indefinitely.
- Webflow template demo/shop/product links and checkout routes require business decisions before cutover.
- This is a migration baseline, not a promise of zero SEO fluctuations.
