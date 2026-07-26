#!/usr/bin/env node
// Bundles and minifies the theme-independent site_template CSS files into one
// file for production, so header.php can emit a single <link> instead of ~22
// (see header.php's ENVIRONMENT check). Both statster.local and statster.info
// serve plain HTTP/1.1 (no multiplexing), so cutting request count here is a
// real win, not just cosmetic.
//
// Uses lightningcss, not a generic minifier like clean-css - this codebase
// uses native CSS nesting (&) throughout, and clean-css silently drops
// nested rules it doesn't understand instead of erroring, which would
// corrupt the bundle without any visible failure. lightningcss parses and
// preserves CSS Nesting correctly (verified against every file here before
// switching).
//
// Theme files (media/css/themes/{light,dark}/{colors,styles}.css) stay
// separate and out of this bundle - they're chosen per-request based on the
// logged-in user's theme, so they can't be folded into one static bundle.
//
// Run manually (npm run build-css) whenever a site_template CSS file changes,
// before committing/deploying - there's no CI here, deploy is a plain
// `git pull`, so the built bundle has to be committed like any other file.

const fs = require('node:fs');
const path = require('node:path');
const { transform } = require('lightningcss');

// Must match header.php's development-mode <link> order exactly (minus the
// two theme files, which stay separate).
const FILES = [
  'top_container.css',
  'heading_container.css',
  'main_container.css',
  'loaders.css',
  'icons.css',
  'foundation.css',
  'artist_album_user.css',
  'tags.css',
  'forms.css',
  'table_misc.css',
  'music_table.css',
  'side_table.css',
  'bar_table.css',
  'shout_table.css',
  'music_wall.css',
  'lists.css',
  'images.css',
  'widget_controls.css',
  'date_picker.css',
  'utilities.css',
  'footer.css',
  'responsive.css'
];

const siteTemplateDir = path.join(__dirname, '..', 'media', 'css', 'site_template');
const outDir = path.join(__dirname, '..', 'media', 'css', 'dist');
const outFile = path.join(outDir, 'bundle.min.css');

const rawSource = FILES.map(name => fs.readFileSync(path.join(siteTemplateDir, name), 'utf8')).join('\n');

// @import must be the first thing in a stylesheet - icons.css's imports are
// fine in its own file, but land mid-bundle once concatenated after
// top_container.css/etc. Hoist any @import lines to the very top.
const importLines = [];
const withoutImports = rawSource.replace(/^@import\s+[^;]+;\s*$/gm, match => {
  importLines.push(match);
  return '';
});
const source = `${importLines.join('\n')}\n${withoutImports}`;

const { code, warnings } = transform({
  filename: 'bundle.css',
  code: Buffer.from(source),
  minify: true
});

if (warnings.length > 0) {
  console.error('build-css: lightningcss warnings (aborting - check these before bundling):');
  for (const w of warnings) {
    console.error(' ', w.message, `(${w.loc.line}:${w.loc.column})`);
  }
  process.exit(1);
}

fs.mkdirSync(outDir, { recursive: true });
fs.writeFileSync(outFile, code);

const before = Buffer.byteLength(source, 'utf8');
const after = code.length;
console.log(`build-css: ${FILES.length} files -> ${path.relative(process.cwd(), outFile)} (${before} -> ${after} bytes, ${(100 - (after / before) * 100).toFixed(1)}% smaller)`);
