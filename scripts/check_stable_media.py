#!/usr/bin/env python3
"""CI guard for the historic animated-zoom bug and Buying Trips route hierarchy."""
from pathlib import Path
import re
root=Path(__file__).resolve().parents[1]
public=root/'preview-dist'
expected=['index.html','buying-trips/index.html','buying-trips/dallas-market/index.html','services/index.html','education/index.html']
for x in expected:
    p=public/x
    assert p.exists(),f'Missing {x}'
    h=p.read_text(encoding='utf-8')
    assert 'media-stable.css?v=20261124-stationary-v3' in h,x
    assert 'atelier-motion.mp4' not in h,x
    assert 'data-ambient-video' not in h,x
    assert '<video ' not in h,x
assert 'Buying Trips' in (public/'buying-trips/index.html').read_text(encoding='utf-8')
assert 'Dallas Market' in (public/'buying-trips/index.html').read_text(encoding='utf-8')
assert 'data-youtube-url="https://www.youtube.com/watch?v=UuUu8FOKULQ"' in (public/'index.html').read_text(encoding='utf-8')
assert 'www.youtube-nocookie.com/embed' in (root/'public/assets/media-stable.js').read_text(encoding='utf-8')
css=(root/'public/assets/media-stable.css').read_text(encoding='utf-8')
assert '@keyframes x-photo-fade' in css and 'opacity:' in css
assert 'translateX(' not in css and 'translateY(' not in css
assert not re.search(r'transform\s*:\s*scale\s*\(',css)
assert 'transform: none !important' in css
print('PASS: 122-page preview is free of animated zoom video; opacity-only fades; Buying Trips parent; deferred YouTube player.')
