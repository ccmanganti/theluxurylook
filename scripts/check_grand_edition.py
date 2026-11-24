#!/usr/bin/env python3
"""Visual CSS deployment and route preservation guard for The Luxury Look review."""
from pathlib import Path
import re

root = Path(__file__).resolve().parents[1]
preview = root / 'preview-dist'
cssfile = root / 'public/assets/grand-edition.css'
css = cssfile.read_text(encoding='utf-8')
assert len(css) > 10000, 'Grand Edition design stylesheet unexpectedly empty'
assert css.count('{') == css.count('}'), 'Unbalanced CSS rule braces'
assert '--grand-ink:' in css and '.x-home-hero' in css and '.x-case-header' in css
# No mechanical image transforms: all motion must remain stationary and fade-only.
assert not re.search(r'transform\s*:\s*scale\s*\(\s*(?!1(?:\.0+)?\s*\))', css), 'Scaled image animation present'
assert not re.search(r'@keyframes\s+(?:zoom|ken-burns|parallax)', css, re.I), 'Forbidden image animation'
assert 'body.x-site .x-ambient-fade' in css and 'transform:none!important' in css
paths = ['index.html', 'design/index.html', 'portfolio/index.html',
         'builders-and-agents/index.html', 'home-staging/index.html',
         'collection/index.html', 'buying-trips/index.html', 'about/index.html',
         'contact/index.html']
for page in paths:
    f = preview / page
    assert f.exists(), f'Missing route: {page}'
    txt = f.read_text(encoding='utf-8')
    assert 'grand-edition.css' in txt, f'Missing Grand Edition stylesheet in {page}'
    assert 'noindex,nofollow,noarchive' in txt, f'Preview lost noindex guard on {page}'
assert (preview / 'assets/grand-edition.css').exists(), 'Missing published CSS'
# Verify exact original project legacy slug survives redesign.
assert (preview / 'portfolios/mockingbird-paradise-valley/index.html').exists()
assert 'The Veranda Estate' in (preview / 'portfolio/index.html').read_text(encoding='utf-8')
print(f'PASS: Grand Edition visual system, no-zoom policy, {len(paths)} routes, original SEO slug preserved.')
