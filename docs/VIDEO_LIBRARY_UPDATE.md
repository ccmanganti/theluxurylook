# The Luxury Look: Video library update (November 24, 2026)

## Video priority
- **Featured (Vimeo; latest supplied):** `https://player.vimeo.com/video/1079407954?h=420926c7fc` — title verified as The Luxury Look. **Never remove the hash**: needed for an unlisted Vimeo video.
- **YouTube / Studio 3125 Tour:** `https://youtu.be/gNeiecujx8Q`
- **YouTube / From the Studio — Featured Video:** `https://youtu.be/Jb8Ye_MFJjA` — metadata/title could not be verified; editable temporary title.
- **YouTube / Sedona interview:** `https://youtu.be/NnwE6poxV1c`

## Use and performance
Both the homepage and Services highlight the Vimeo film as the first item with a static poster. The other three are on the editorial film shelf. **No provider iframe is inserted until the visitor clicks Play**; fullscreen playback is available in a dismissible overlay. Links are host-validated to YouTube or Vimeo; arbitrary embedded HTML and JavaScript URLs are rejected. The player preserves the unlisted Vimeo `h` token and provides an external watch link if the embedded player is blocked by provider privacy settings.

The hero itself retains only stationary crossfading photographs; no MP4 zoom clip returns. `prefers-reduced-motion` and Save-Data remain supported. All video URLs, placements, captions, posters and publication approval are editable at **Filament → Studio Videos**. Seed data is for staging only; new links are not marked as production-verified until approved.

## Additional review needed
- Confirm the exact YouTube `Jb8Ye_MFJjA` video title: the provider did not expose its metadata during source verification.
- The Vimeo iframe endpoint returned HTTP 401 to automated retrieval; the Vimeo watch page and metadata resolved successfully. Test real browser embed playback and domain/privacy settings if it doesn't play.
- Verify usage permission for all clips and the thumbnail/CDN restrictions before public production launch.
