<?php
/**
 * One-time fix: 9 blog posts have a leftover addslashes()-without-
 * stripslashes() artifact - literal \" and \' sequences where a real "
 * or ' was meant (e.g. \"Ghosts\" instead of "Ghosts").
 *
 * Deliberately does NOT do a blind stripslashes() - one of the nine
 * (id 106) also contains a literal "\n" as actual discussed text (a post
 * about line-break escape sequences), which stripslashes() would wrongly
 * mangle into "n". Only \" and \' are touched, nothing else.
 *
 * Detection is done in PHP (fetch every row, compare before/after),
 * not via a SQL LIKE pattern - a LIKE pattern meant to match a literal
 * \" collides with MySQL's own LIKE escape character (backslash) on top
 * of PHP's own string escaping, and ends up matching any row containing
 * a plain " instead (confirmed: matched 38 rows instead of the real 9).
 *
 * blog.updated has ON UPDATE CURRENT_TIMESTAMP - explicitly re-written
 * to its original fetched value in the same UPDATE, same reasoning as
 * migrate_blog_html_to_markdown.php.
 *
 * Usage: CI_PASSWD=<password> php scripts/fix_blog_escaped_quotes.php
 *
 * Idempotent-safe: only writes rows where the fix actually changes the
 * text, so a second run touches nothing.
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

$rows = $mysqli->query('SELECT id, text, updated FROM blog');
$update = $mysqli->prepare('UPDATE blog SET text = ?, updated = ? WHERE id = ?');
$count = 0;
while ($row = $rows->fetch_assoc()) {
  $fixed = str_replace(array('\\"', "\\'"), array('"', "'"), $row['text']);
  if ($fixed === $row['text']) {
    continue;
  }
  $update->bind_param('ssi', $fixed, $row['updated'], $row['id']);
  $update->execute();
  echo "id={$row['id']}: updated\n";
  $count++;
}

echo "\n$count row(s) updated.\n";
?>
