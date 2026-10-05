# Image server (img.statster.info)

These files are a local mirror of what's actually deployed on
`img.statster.info` - a **separate server** from the one running
`statster.info` itself (`saksa`/`vs4104`, `77.37.18.167`, vs. the main
app's `saksa2`/`vs6828`, `77.37.16.156`). Confirmed via `dig` + `/etc/hosts`
on 2026-10-05.

Live doc root: `teelmo@saksa:~/Sites/img.statster.info/`

## Deploy process - no git, scp only

This directory is tracked in the `statster.info` repo for reference and
history, but **that server has no git repo of its own** - these files are
pushed directly with `scp`, not `npm run push` / `npm run sync-prod`
(those only touch the main app server). To deploy a change:

```sh
scp media/img/image_server/<file> teelmo@saksa:~/Sites/img.statster.info/<file>
```

After pushing, verify the live file matches:

```sh
diff <(ssh teelmo@saksa "cat ~/Sites/img.statster.info/<file>") media/img/image_server/<file>
```

Because there's no git on that server, this local copy can silently drift
from what's actually live if a change is ever made directly on the server
(it happened once already - `constants.php` had `http://` locally while
the live file had been updated to `https://`, with no record of when or
why). Treat this directory as a cache of the live state, not the other way
around - if in doubt, pull fresh from the server rather than trusting what's
checked in here:

```sh
scp teelmo@saksa:~/Sites/img.statster.info/<file> media/img/image_server/<file>
```

## Files

- `getImage.php` - public read endpoint, resolves `?type=&size=&id=` to an image path. No restrictions.
- `addImage.php` - write/ingest endpoint, fetches + resizes an image URL into `album_img/`, `artist_img/`, or `user_img/`. No application-level auth, but Apache on that server restricts it to the statster.info server's IP only (confirmed by the user, not derivable from this file).
- `constants.php` - shared config (image sizes, base URL).
- `robots.txt` - blocks all crawlers (`Disallow: /` for everyone) plus an explicit list of AI crawlers as backup. Deployed 2026-10-05.
