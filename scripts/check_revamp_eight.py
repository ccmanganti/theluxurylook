#!/usr/bin/env python3
from pathlib import Path
p=Path(__file__).resolve().parents[1]/"preview-dist"
checks={
"index.html":["Kristine Vowles","DESIGN / REAL ESTATE","THE JOURNAL"],
"design/index.html":["More than a beautiful home","SELECTED RESIDENCES"],
"builders-and-agents/index.html":["SELECTED EXAMPLES","Designed to sell"],
"home-staging/index.html":["Vacant luxury homes","Occupied seller refresh"],
"collection/index.html":["heyzine.com","THE COLLECTION"],
"buying-trips/index.html":["Dallas Market","Private Sourcing"],
"portfolio/index.html":["The Veranda Estate"],
}
for filename,markers in checks.items():
 path=p/filename
 assert path.exists(),f"Missing {filename}"
 html=path.read_text()
 for marker in markers:assert marker.lower() in html.lower(),f"{filename}: {marker}"
 assert "noindex,nofollow,noarchive" in html,f"Unapproved indexing: {filename}"
assert (p/"assets/revamp8.css").exists()
print("PASS: eight relaunch workstreams in design preview.")
