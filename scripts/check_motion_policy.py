#!/usr/bin/env python3
"""Regression test: editorial site motion is fade-only; no image zoom or moving marquees."""
from pathlib import Path
import re

ROOT = Path(__file__).resolve().parents[1]
assets = [ROOT / "public/assets" / f for f in ("experience.css", "site.css", "editorial.css", "revamp.css")]
for path in assets:
    css = path.read_text(encoding="utf-8")
    assert not re.search(r"transform\s*:\s*(?:scale|scaleX|scaleY)\s*\(", css, re.I), f"Zoom transform in {path}"
    assert not re.search(r"animation\s*:\s*x-marquee", css, re.I), f"Moving marquee in {path}"
    assert "translateY(26px)" not in css, f"Old slide-in remains in {path}"
    assert not re.search(r"transition\s*:[^;}]*\btransform\b", css, re.I), f"Transform transition remains in {path}"
experience = assets[0].read_text(encoding="utf-8")
assert ".x-reveal.is-observed{opacity:0;transition:opacity .7s" in experience
assert ".x-reveal.is-observed.is-visible{opacity:1}" in experience
js = (ROOT / "public/assets/experience.js").read_text(encoding="utf-8")
assert "el.getBoundingClientRect()" in js
assert "observer.unobserve(entry.target)" not in js or "obs.unobserve(entry.target)" in js
print("PASS: fade-only editorial transitions; no image zoom, layout slide or moving marquee.")
