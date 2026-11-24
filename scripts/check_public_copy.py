#!/usr/bin/env python3
"""Fail publication if an HTML page exposes development or administrative copy."""
from html.parser import HTMLParser
from pathlib import Path
import re
import sys

ROOT = Path(__file__).resolve().parents[1]
PUBLIC_DIR = ROOT / "preview-dist"
FORBIDDEN = re.compile(
    r"private design review|design preview|development preview|"
    r"website content migration|editorial migration|"
    r"under review|not approved for public launch|"
    r"title pending confirmation|not indexed|"
    r"\bfilament\b|\bbackend\b|\bCMS\b|"
    r"temporary (?:layout|portfolio|cover|studio|illustration|editorial|photograph|photography|images?|visual)|"
    r"the original article has not yet been imported|"
    r"page under migration|migration preview|"
    r"this page is an inquiry preview|"
    r"staging preview|review-only website|"
    r"restored articles",
    re.IGNORECASE,
)

class PublicText(HTMLParser):
    def __init__(self):
        super().__init__(convert_charrefs=True)
        self.hidden = 0
        self.parts = []

    def handle_starttag(self, tag, attrs):
        if tag in {"script", "style"}:
            self.hidden += 1
        if not self.hidden:
            for name, value in attrs:
                if name in {"alt", "aria-label", "title", "placeholder"} and value:
                    self.parts.append(value)

    def handle_endtag(self, tag):
        if tag in {"script", "style"} and self.hidden:
            self.hidden -= 1

    def handle_data(self, data):
        if not self.hidden:
            self.parts.append(data)

def main():
    pages = sorted(PUBLIC_DIR.rglob("*.html"))
    if not pages:
        print("FAIL: no published HTML to audit")
        return 1
    errors = []
    for path in pages:
        html = path.read_text(encoding="utf-8")
        if 'class="preview-ribbon"' in html:
            errors.append((path, "visible internal review ribbon"))
        parser = PublicText()
        parser.feed(html)
        for match in FORBIDDEN.finditer(" ".join(parser.parts)):
            errors.append((path, match.group(0)))
    if errors:
        for path, phrase in errors[:35]:
            print(f"FAIL: {path.relative_to(PUBLIC_DIR)}: {phrase}")
        print(f"FAILED: {len(errors)} client-facing copy issues found")
        return 1
    print(f"PASS: {len(pages)} public pages contain no internal development notices")
    return 0

if __name__ == "__main__":
    sys.exit(main())
