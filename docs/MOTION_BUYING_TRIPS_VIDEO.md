# Motion, Buying Trips, and YouTube migration

## Issue resolved

The former `public/media/atelier-motion.mp4` is itself a zooming animation of a still photograph. Removing CSS transforms did not stop the visible movement. No new page references or plays that clip. The redesigned ambient visual contains a static poster and two stationary temporary photos. Their *opacity only* crossfades on a 21-second cycle. No scale, translate or parallax keyframes exist.

New `media-stable.css` loads **last**, with a cache-busted URL to override older GitHub Pages or browser-cached CSS. `media-stable.js` loads YouTube only following a user click. Low-bandwidth and reduced-motion users receive a static image.

## YouTube provenance

- Original verified Webflow `https://www.theluxurylook.com/services` has `https://www.youtube.com/embed/UuUu8FOKULQ?rel=1&controls=1&autoplay=0&mute=0&start=0`.
- The original company site links `https://www.youtube.com/channel/UCqPsB5QK-XuPWmksTDGoDrQ`.
- `database/data/studio-videos.json` contains only this verified video. Other videos can be entered into **Filament > Studio Videos**, with YouTube watch/share/embed URLs. No unrelated video is invented.
- A YouTube video is not played as a forced background: it opens in a cinematic overlay *on user click*, using youtube-nocookie; this avoids autoplay performance regressions. The hero background uses an opacity-only photo sequence instead.

## Buying Trips hierarchy

- `/buying-trips` is the Buying Trips overview; Dallas Market is one destination option.
- `/buying-trips/dallas-market` and existing `/dallas-market` both expose Dallas-specific offerings. Existing preview URLs and legacy URLs stay reserved; final canonical/redirect policy requires approval.
- Additional buying trip modes are marked inquiry-only, with no invented destinations, dates, prices or guaranteed trade access.
- Filament > Dallas, Buying Trips & Education can edit offering descriptions, status and images.

## Deployment checklist

- Static GitHub Pages preview remains `noindex`; production Webflow remains untouched.
- Verify desktop and mobile hero frames do not move and the outgoing clip is not requested by the page.
- Check image fades, save-data, reduced motion, gallery hover, and YouTube play/close/escape behavior.
- On PHP hosting run migrations and the seeders. Verify CMS field validations, routing, video embedding policies, and final SEO/canonical URLs before cutover.
