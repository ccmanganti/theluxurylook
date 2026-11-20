# URL and Content Mapping

| Existing Webflow path | Laravel handler | Filament content model |
|---|---|---|
| `/` | `SiteController@home` | Projects, Services, Portfolio Categories |
| `/about`, `/the-studio`, `/awards`, `/digital-products`, workshops, yacht page | `@page` | Pages |
| `/services` | `@services` | Services |
| `/portfolio/{slug}` | `@category` | Portfolio Categories and Projects |
| `/portfolios/{slug}` | `@project` | Projects + galleries |
| `/blog` | `@blog` | Posts |
| `/blog/{slug}` | `@post` | Posts |
| `/contact` | `@contact`, `@submitContact` | Inquiries |
| `/search` | `@search` | Projects + Posts (noindex) |
| `/checkout`, `/paypal-checkout`, `/order-confirmation` | `@special` (503; prelaunch only) | Legacy URL audit |
| `/product/{slug}`, `/template-info/{slug}` | `@fallback` exact allowlist | Legacy URL audit / reserved |
| `/sitemap.xml`, `/robots.txt` | `@sitemap`, `@robots` | Dynamic source of truth |

*The migration should preserve the original URLs before any structural redesign. An indexable launch must not happen while mandatory original content and commerce routes are incomplete.*
