# Statster

Statster is a personal music listening tracker and community site –
*"reconcile with music."* It keeps a long-running diary of what's been
listened to, browsable by artist, album, format, release year, genre, tag
and nationality, alongside user profiles, likes, comments ("shouts"), a
private inbox, and a blog.

## Tech stack

- PHP, built on [CodeIgniter 3](#built-on-codeigniter)
- MySQL/MariaDB
- Vanilla JS, no frontend framework
- CSS with native nesting, bundled and minified for production via
  [lightningcss](https://lightningcss.dev/) (`npm run build-css`)

## Development

Local development assumes a standard Apache + PHP + MySQL setup serving
this directory (e.g. `statster.local`), with a local `statster` database.
There's no build step for day-to-day PHP/view work – only CSS changes
need `npm run build-css` run and committed before deploying, since
production serves one bundled `media/css/dist/bundle.min.css` rather than
the individual `site_template` files.

See `package.json` for the available scripts (CSS bundling, DB dump/
restore helpers, deploy).

## Deployment

The app deploys with a plain `git pull` on the production server:

```sh
npm run push       # pushes to the configured git remotes
npm run sync-prod   # pulls on the production server over SSH
```

Avatar/album-art images are served from a separate host
(`img.statster.info`) that has no git repo of its own – see
`media/img/image_server/README.md` for that server's own deploy process.

## Built on CodeIgniter

Statster runs on [CodeIgniter 3](https://codeigniter.com/), via the
[pocketarc/codeigniter](https://github.com/pocketarc/codeigniter)
maintenance fork that keeps CI3 working on current PHP versions now that
the original 3.x branch is no longer maintained. There's no intention to
migrate to CI4 or any other framework – CI3 does what this project
needs.
