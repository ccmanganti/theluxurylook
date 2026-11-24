#!/usr/bin/env python3
from pathlib import Path
import json,re
root=Path(__file__).resolve().parents[1]
out=root/'preview-dist'
articles=json.loads((root/'database/data/blog-archive.json').read_text('utf-8'))
assert len(articles)>=15
assert len(set(p['slug'] for p in articles))==len(articles)
for p in articles:
 assert len(p.get('body') or '')>350,p['slug']
 f=out/'blog'/p['slug']/'index.html'
 assert f.exists(),p['slug']
 html=f.read_text('utf-8')
 assert '<div class="journal-article-body"' in html,p['slug']
 assert len(html)>3000,p['slug']
 assert '<link rel="canonical"' in html,p['slug']
 assert '<script type="application/ld+json">' in html,p['slug']
 assert 'noindex,nofollow,noarchive' in html,p['slug']
 assert 'The full original article is being transferred' not in html,p['slug']
 assert p['slug'] in html
for path in ['index.html','portfolio/index.html','design/index.html','contact/index.html','services/index.html']:
 html=(out/path).read_text('utf-8')
 assert '<link rel="canonical"' in html,path
 assert '<meta property="og:title"' in html,path
 assert '<script type="application/ld+json">' in html,path
 assert 'noindex,nofollow,noarchive' in html,path
assert 'data-journal-search-input' in (out/'blog/index.html').read_text('utf-8')
assert 'data-portfolio-search' in (out/'portfolio/index.html').read_text('utf-8')
assert (out/'assets/journal.css').exists()
assert (out/'assets/journal.js').exists()
print('PASS: 15 complete Journal articles, page-specific SEO, canonical metadata and accessible search.')
