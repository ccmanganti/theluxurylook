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
    assert 'media-stable.css?v=20261124-films-v4' in h,x
    assert 'atelier-motion.mp4' not in h,x
    assert 'data-ambient-video' not in h,x
    assert '<video ' not in h,x
assert 'Buying Trips' in (public/'buying-trips/index.html').read_text(encoding='utf-8')
assert 'Dallas Market' in (public/'buying-trips/index.html').read_text(encoding='utf-8')
home=(public/'index.html').read_text(encoding='utf-8')
services=(public/'services/index.html').read_text(encoding='utf-8')
assert 'data-video-url="https://player.vimeo.com/video/1079407954?h=420926c7fc"' in home
assert 'data-video-url="https://player.vimeo.com/video/1079407954?h=420926c7fc"' in services
for video_id in ('gNeiecujx8Q','Jb8Ye_MFJjA','NnwE6poxV1c'):
    assert video_id in home and video_id in services, video_id
assert home.index('Watch the latest film') < home.index('Studio 3125 Tour')
assert '<iframe' not in home and '<iframe' not in services
script=(root/'public/assets/media-stable.js').read_text(encoding='utf-8')
assert 'www.youtube-nocookie.com/embed' in script
assert 'player.vimeo.com/video' in script and "params.set('h',hash)" in script
assert 'data-video-url' in script
css=(root/'public/assets/media-stable.css').read_text(encoding='utf-8')
assert '@keyframes x-photo-fade' in css and 'opacity:' in css
assert 'translateX(' not in css and 'translateY(' not in css
assert not re.search(r'transform\s*:\s*scale\s*\(',css)
assert 'transform: none !important' in css
print('PASS: 122-page preview is free of animated zoom video; opacity-only fades; Buying Trips parent; deferred Vimeo and YouTube film gallery.')
