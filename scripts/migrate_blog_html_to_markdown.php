<?php
/**
 * One-time migration: blog.text and blog_comment.text, raw HTML (the
 * format the dormant blog feature stored content in) -> Markdown, so the
 * revived read-only blog views can render them through the same
 * renderMarkdown()/Parsedown safe-mode path already used for inbox
 * messages and notifications.
 *
 * Confirmed tag set across all 187 blog + 134 blog_comment rows (nothing
 * else appears): <br>, <strong>, <em>, <del>, <ins>, <a href>, <img>. This
 * reuses migrate_bulletins_to_messages.php's bulletinHtmlToMarkdown() as
 * its base (same <a>/<img>/<br>/autolink handling), adding the four tag
 * mappings that never appeared in the old bulletin data.
 *
 * Usage: CI_PASSWD=<password> php scripts/migrate_blog_html_to_markdown.php
 *
 * Idempotent-safe only from a clean slate: refuses to run if any row
 * already contains Markdown link/bold syntax ("](" or "**" - confirmed
 * absent from every row before migration, so their presence means a
 * partial or already-completed run). The whole run is one transaction -
 * any failure rolls back completely and can simply be re-run.
 *
 * Both tables have an ON UPDATE CURRENT_TIMESTAMP column alongside `text`
 * (blog.updated, blog_comment.created) - left untouched by a plain
 * UPDATE ... SET text = ?, these would otherwise silently get stamped
 * with today's date as a side effect. This script explicitly re-writes
 * each back to its original fetched value in the same UPDATE to prevent
 * that.
 */

$password = getenv('CI_PASSWD') ?: ($_SERVER['CI_PASSWD'] ?? null);
if (!$password) {
  fwrite(STDERR, "CI_PASSWD not set. Run as: CI_PASSWD=<password> php " . basename(__FILE__) . "\n");
  exit(1);
}

$mysqli = new mysqli('localhost', 'statster', $password, 'statster');
if ($mysqli->connect_errno) {
  fwrite(STDERR, "DB connection failed: " . $mysqli->connect_error . "\n");
  exit(1);
}
$mysqli->set_charset('utf8mb4');

foreach (array('blog', 'blog_comment') as $table) {
  $already_migrated = (int) $mysqli->query("SELECT COUNT(*) AS c FROM `$table` WHERE `text` LIKE '%](%' OR `text` LIKE '%**%'")->fetch_assoc()['c'];
  if ($already_migrated > 0) {
    fwrite(STDERR, "Refusing to run: `$table` has $already_migrated row(s) that already look like Markdown - not safe to re-run on a partial or completed migration.\n");
    exit(1);
  }
}

/**
 * Converts the HTML shapes found in blog/blog_comment content to Markdown.
 * Based on migrate_bulletins_to_messages.php's bulletinHtmlToMarkdown(),
 * with <strong>/<em>/<del>/<ins> handling added (never appeared in the
 * old bulletin data, but common in blog posts/comments).
 */
function blogHtmlToMarkdown($html) {
  // 1. <a href="X">text</a>
  $html = preg_replace_callback(
    '#<a\s+href="([^"]*)"[^>]*>(.*?)</a>#is',
    function ($m) {
      return '[' . trim($m[2]) . '](' . html_entity_decode($m[1], ENT_QUOTES, 'UTF-8') . ')';
    },
    $html
  );

  // 2. <img src="X" alt="Y">
  $html = preg_replace_callback(
    '#<img\s+[^>]*?src="([^"]*)"(?:[^>]*?alt="([^"]*)")?[^>]*/?>#is',
    function ($m) {
      return '![' . (isset($m[2]) ? $m[2] : '') . '](' . html_entity_decode($m[1], ENT_QUOTES, 'UTF-8') . ')';
    },
    $html
  );

  // 2b. One post has a genuine copy-paste duplication bug in the source
  //     data: <strong><strong>Johannes:</strong></strong> (self-nested
  //     identical tags - a non-greedy single-pass regex can't pair these
  //     correctly). Collapse any immediately-doubled identical tag pair
  //     before the real conversion - doubled formatting is a no-op
  //     regardless, so this is safe everywhere, not just that one post.
  $html = preg_replace('#<(strong|em|del|ins)>\s*<\1>#i', '<$1>', $html);
  $html = preg_replace('#</(strong|em|del|ins)>\s*</\1>#i', '</$1>', $html);

  // 3. <strong>/<em>/<del> -> Markdown bold/italic/strikethrough (this
  //    vendored Parsedown supports ~~strikethrough~~ natively). The
  //    (?=[\s>]) boundary after the tag name is required: without it,
  //    e.g. "<b" would also match the start of "<br" (a bare "b"/"i"
  //    alias is a prefix of "br"/"ins" - confirmed to actually happen).
  $html = preg_replace('#<strong(?=[\s>])[^>]*>(.*?)</strong>#is', '**$1**', $html);
  $html = preg_replace('#<em(?=[\s>])[^>]*>(.*?)</em>#is', '_$1_', $html);
  $html = preg_replace('#<del(?=[\s>])[^>]*>(.*?)</del>#is', '~~$1~~', $html);

  // 4. <ins> has no Markdown equivalent - unwrap it, keep the text.
  $html = preg_replace('#</?ins(?=[\s>])[^>]*>#i', '', $html);

  // 5. <br>/<br /> -> Markdown hard line break (two trailing spaces).
  $html = preg_replace('#<br\s*/?>#i', "  \n", $html);

  // 6. The source data interleaves real newlines around <br><br> pairs
  //    (paragraph breaks), which step 5 turns into redundant runs of
  //    blank/whitespace-only lines. Collapse any such run (2+ consecutive
  //    newlines, each optionally preceded by the two-space hard-break
  //    marker) into one clean paragraph break. A single isolated hard
  //    break (e.g. a real intra-paragraph line break) is left untouched.
  $html = preg_replace('/(?: {0,2}\n){2,}/', "\n\n", $html);

  // 7. Any leftover <div>/</div> wrapper (no other tags ever appear).
  $html = preg_replace('#</?div[^>]*>#i', '', $html);

  // 8. Bare URLs -> Markdown's explicit autolink syntax, skipping ones
  //    already inside a markdown link target.
  $html = preg_replace('#(?<!\]\()(https?://[^\s<)\]]+)#i', '<$1>', $html);

  return trim(html_entity_decode($html, ENT_QUOTES, 'UTF-8'));
}

$mysqli->begin_transaction();

try {
  $summary = array();

  $blog_rows = $mysqli->query('SELECT id, text, updated FROM blog');
  $blog_count = 0;
  while ($row = $blog_rows->fetch_assoc()) {
    $body_md = blogHtmlToMarkdown($row['text']);
    $stmt = $mysqli->prepare('UPDATE blog SET text = ?, updated = ? WHERE id = ?');
    $stmt->bind_param('ssi', $body_md, $row['updated'], $row['id']);
    $stmt->execute();
    $blog_count++;
  }
  $summary['blog'] = $blog_count;

  $comment_rows = $mysqli->query('SELECT id, text, created FROM blog_comment');
  $comment_count = 0;
  while ($row = $comment_rows->fetch_assoc()) {
    $body_md = blogHtmlToMarkdown($row['text']);
    $stmt = $mysqli->prepare('UPDATE blog_comment SET text = ?, created = ? WHERE id = ?');
    $stmt->bind_param('ssi', $body_md, $row['created'], $row['id']);
    $stmt->execute();
    $comment_count++;
  }
  $summary['blog_comment'] = $comment_count;

  echo "Migration summary:\n";
  foreach ($summary as $label => $count) {
    echo "  $label: $count\n";
  }

  $mysqli->commit();
  echo "\nCommitted.\n";
}
catch (Throwable $e) {
  $mysqli->rollback();
  fwrite(STDERR, "Migration failed, rolled back: " . $e->getMessage() . "\n");
  exit(1);
}
