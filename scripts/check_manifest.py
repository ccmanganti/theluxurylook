#!/usr/bin/env python3
"""Offline integrity check for the Webflow URL inventory and seeded CMS data.

This is intentionally dependency-free so it runs before Composer/Laravel setup.
It does not replace Laravel's runtime feature tests or manual SEO parity checks.
"""
from collections import Counter
from pathlib import Path
from urllib.parse import urlparse
import json

ROOT = Path(__file__).resolve().parent.parent
DATA = ROOT / "database" / "data"
def load(name):
    return json.loads((DATA / name).read_text(encoding="utf-8"))

legacy = load("legacy-sitemap.json")
extra = load("discovered-not-in-sitemap.json")
portfolio = load("portfolio-seed.json")
services = load("services-seed.json")
pages = load("pages-seed.json")
slugs = {p["slug"] for p in portfolio["projects"]}
categories = {c["slug"] for c in portfolio["categories"]}
all_paths = [u["path"] for u in legacy]
extra_paths = [u["path"] for u in extra]

assert len(legacy) == 96, f"Expected 96 live sitemap URLs, found {len(legacy)}"
assert len(all_paths) == len(set(all_paths)), "Duplicate URL in original sitemap"
assert len(extra_paths) == len(set(extra_paths)), "Duplicate extra linked URL"
assert not set(all_paths).intersection(extra_paths), "Extra URLs duplicate XML sitemap entries"
assert all(u["path"].startswith("/") and urlparse(u["source"]).netloc == "www.theluxurylook.com" for u in legacy + extra)
assert len(categories) == 6, f"Expected six portfolio categories, found {len(categories)}"
assert len(portfolio["projects"]) == len(slugs) == 54
assert len(services) == 6
assert len({s["slug"] for s in services}) == len(services)
assert len({p["path"] for p in pages}) == len(pages)

missing_projects = [u["path"] for u in legacy + extra if u["path"].startswith("/portfolios/") and u["path"].split("/", 2)[2] not in slugs]
missing_categories = [u["path"] for u in legacy if u["path"].startswith("/portfolio/") and u["path"].split("/", 2)[2] not in categories]
assert not missing_projects, f"Project slugs missing in CMS seed: {missing_projects}"
assert not missing_categories, f"Portfolio category slugs missing in CMS seed: {missing_categories}"
assert all(p["category_slug"] in categories or p["category_slug"] is None for p in portfolio["projects"])
assert len({p["cover_image"] for p in portfolio["projects"]}) == 54, "Cover image ordering reused an unrelated project photo"
assert all(not p["content_verified"] for p in portfolio["projects"]), "Unverified project claimed ready"

by_type = Counter(
    "project" if x.startswith("/portfolios/") else
    "category" if x.startswith("/portfolio/") else
    "article" if x.startswith("/blog/") else
    "product" if x.startswith("/product/") else
    "webflow_template" if x.startswith("/template-info/") else
    "other" for x in all_paths
)
print("Manifest PASS: 96 original URLs, 2 extra linked URLs")
print(f"Portfolio PASS: {len(slugs)} projects, {len(categories)} categories, 54 distinct source cover images")
print("Original URL breakdown:", dict(sorted(by_type.items())))
print("Not audited here: Laravel runtime, Webflow copy parity, images, Google tags, payment and DNS")
