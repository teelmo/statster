# Statster – project instructions for Claude/AI sessions

See `README.md` first for stack/deploy basics. This file covers
project-specific conventions and gotchas that aren't obvious from the
code alone.

## Environments

- **Local dev**: `statster.local` (Apache + PHP + MySQL on this Mac).
  Dev server is assumed already running; don't start it.
- **Production**: `statster.info`, deployed via `npm run push` (pushes
  to all git remotes) then `npm run sync-prod` (SSH `git pull` on the
  prod host `saksa2`).
- **Never do write/click-through testing (adding a listening, marking an
  album owned, posting a shout, etc.) against production.** Only
  exercise mutating flows on `statster.local`. Read-only verification
  (curl, viewing a page) against production is fine after a deploy.

## CSS

- Production serves **one bundled file**,
  `media/css/dist/bundle.min.css`, built from the ~22 individual
  `media/css/site_template/*.css` files by `npm run build-css`
  (lightningcss). Dev mode (`ENVIRONMENT !== 'development'` is false)
  loads the individual files directly for easier editing/hard-reload.
  **Always run `npm run build-css` and commit the rebuilt bundle
  alongside any `site_template/*.css` edit** – production never picks
  up source file changes directly.
- To add a new CSS file to the bundle, register it in both
  `scripts/build-css.js`'s `FILES` array and `header.php`'s dev-mode
  `<link>` list, in the same relative position in both.
- Native CSS nesting (`&`) is used throughout and is preserved as-is by
  lightningcss in the minified bundle (not flattened) – this is
  expected, not a bug.
- Icon-over-image overlays (e.g. owned-format icons on an album tile)
  use a `.icons` flex wrapper (`display:flex; gap:2px; position:absolute`)
  containing one `<img class="icon">` per format – see `lists.css`'s
  `.album_list, .artist_list li` block. Reuse this pattern rather than
  inventing a new one; it has wide "blast radius" (shared by Discography
  tiles, mosaic tiles, and favorite-album grids).
- **Never put `display:flex` directly on a `<td>`** – it breaks
  table-cell layout (row height, vertical-align, background fill).
  For icon overlays inside a table row, keep the `<td>` as a normal
  cell and position the overlay with `position:absolute` instead
  (see `bar_table.css`'s `&.name`/`.icons`).
- Touch-specific spacing/sizing belongs in `@media (pointer: coarse)`,
  not a narrow-viewport `max-width` query – "touch" and "narrow" are
  orthogonal (a resized desktop window isn't touch; a tablet/landscape
  phone is touch but not narrow).
- A `position:absolute` child inside a container with no explicit size
  collapses that container to its in-flow sibling's size, which can
  silently misplace the absolute child's `top`/`left` offsets. Give the
  container an explicit size when something needs to be centered inside
  it (see `heading_container.css`'s `.user_listening_image`).

## Asset cache-busting

CSS/JS asset URLs are versioned with `?v=<filemtime>` via the
`versioned_asset($path)` helper (`application/helpers/general_helper.php`,
autoloaded). Every `link_tag()`/`<script src>` in `header.php` and
`footer.php` is wrapped in it. **Any new CSS/JS `<link>` or `<script>`
tag added to a template must also be wrapped in `versioned_asset()`**,
or it won't cache-bust on deploy. Note `.htaccess`'s `mod_expires`
directives are a documented no-op on both servers (module not loaded);
`mod_headers`' `Header set Cache-Control` (1 week on .css/.js) is the
one that actually applies, which is why cache-busting the URL (rather
than shortening the cache duration) was the fix.

## Flash messages (`.flash_msg`)

`app.showFlashMsg(el, text, fadeout)` (`media/js/statster.js`) accepts
either a plain string, or `{ short, full }` to show a short form
(`Ok!`/`Del!`) on narrow screens and a full descriptive form
(`You're in love!`, `Owned!`, etc.) on desktop. CSS
(`artist_album_user.css` + `responsive.css`'s `max-width:480px` block)
toggles which span is visible by breakpoint. When adding a new
flash-triggering action, follow the existing call sites in
`media/js/music/album.js` / `artist.js` (and the ~12 near-duplicate
page-variant files under `media/js/{like,format,music,shout,tag}/`) –
pass an object with both forms, don't hardcode one string.

## Owned-album-icon visibility rule

Icons showing which formats a user owns an album in follow one rule:
**logged-out visitors see no icons anywhere, except on
profile-scoped pages** (the Favorite-albums grid, and
`/user/<username>/collection`), which always show the *viewed* user's
data regardless of the visitor's own login state. Everywhere else
(Discography, mosaic, album-list tiles, etc.) icons reflect the
*logged-in viewer's own* collection and are omitted entirely when
logged out. Keep this distinction in mind before adding owned-icon
rendering to a new page – check whether it's profile-scoped (viewed
user's data) or generic (viewer's own data, hidden when anonymous).

## Testing without a reliable window-resize tool

The `resize_window` browser automation tool is unreliable in this
environment (returns inconsistent/wrong viewport widths). To test a
specific breakpoint, inject a fixed-width `<iframe>` pointing at the
target URL and inspect/screenshot inside that iframe instead – confirm
the iframe's real width via `iframe.contentWindow.innerWidth`.

## Database

- Local: `mysql -u statster -pstatster statster`.
- Artist/album foreign keys are all `ON DELETE CASCADE` – don't add new
  FKs that rely on "harmless orphan" reasoning, it's inconsistent with
  the rest of the schema.
- The app connects as `utf8mb3` even though columns are `utf8mb4` –
  this is a known, accepted quirk, not a bug to fix.

## Dormant/live feature status (as of last audit)

`similar_artists` and `user_album` (the owned-album feature) are live.
`bmi`, `foodbill`, `*_equalize`, `listening_info` are still dormant and
undecided – don't assume referencing them is safe without checking.
Registration is deliberately closed (not broken).

## Sole user

Teemo is Statster's sole active user (an Apple Music listener). Dead or
low-traffic features can be left dormant rather than hardened for
scale; prioritize fixes/features Teemo will actually feel.
