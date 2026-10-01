<?php
/**
 * One-time migration: bulletin + bulletins (legacy, dormant since 2014) ->
 * message, message_recipient, notification.
 *
 * - /sent/ rows + their `bulletins` fan-out are the sole source of truth for
 *   `message`/`message_recipient` - every /inbox/ and /trash/ physical copy
 *   is already covered by a /sent/+bulletins pairing (verified 76/76 before
 *   this script was written), so they are used only to enrich per-recipient
 *   state/folder, never to create their own message rows.
 * - /shares/ rows each become their own single-recipient `message`
 *   (type='share').
 * - /notices/ and /notifications/ rows become `notification` rows.
 *
 * Usage: CI_PASSWD=<password> php scripts/migrate_bulletins_to_messages.php
 *
 * Idempotent-safe only from a clean slate: refuses to run if message/
 * message_recipient/notification already contain any rows. The whole run is
 * one transaction - any failure rolls back completely and can simply be
 * re-run. Read-only against bulletin/bulletins; never writes to them.
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

foreach (array('message', 'message_recipient', 'notification') as $table) {
  $result = $mysqli->query("SELECT COUNT(*) AS c FROM `$table`");
  $count = (int) $result->fetch_assoc()['c'];
  if ($count > 0) {
    fwrite(STDERR, "Refusing to run: `$table` already has $count row(s) - not safe to re-run on a partial migration.\n");
    exit(1);
  }
}

/**
 * Converts the handful of HTML shapes that ever appear in bulletin content
 * to Markdown. Order matters: the malformed "shared album/artist" template
 * must match before the generic <a> pattern, or the generic regex would
 * swallow the nested <div><img></div> as literal link text.
 */
function bulletinHtmlToMarkdown($html) {
  // 1. Known malformed share template: <a href="X"><div ...><img .../></a></div>
  $html = preg_replace_callback(
    '#<a\s+href="([^"]*)"[^>]*>\s*<div[^>]*>\s*<img\s+[^>]*?src="([^"]*)"(?:[^>]*?alt="([^"]*)")?[^>]*/?>\s*</a>\s*</div>#is',
    function ($m) {
      $href = html_entity_decode($m[1], ENT_QUOTES, 'UTF-8');
      $src = html_entity_decode($m[2], ENT_QUOTES, 'UTF-8');
      $alt = isset($m[3]) ? html_entity_decode($m[3], ENT_QUOTES, 'UTF-8') : '';
      return '[![' . $alt . '](' . $src . ')](' . $href . ')';
    },
    $html
  );

  // 2. Remaining well-formed <a href="X">text</a>
  $html = preg_replace_callback(
    '#<a\s+href="([^"]*)"[^>]*>(.*?)</a>#is',
    function ($m) {
      return '[' . trim($m[2]) . '](' . html_entity_decode($m[1], ENT_QUOTES, 'UTF-8') . ')';
    },
    $html
  );

  // 3. Remaining bare <img src="X" alt="Y">
  $html = preg_replace_callback(
    '#<img\s+[^>]*?src="([^"]*)"(?:[^>]*?alt="([^"]*)")?[^>]*/?>#is',
    function ($m) {
      return '![' . (isset($m[2]) ? $m[2] : '') . '](' . html_entity_decode($m[1], ENT_QUOTES, 'UTF-8') . ')';
    },
    $html
  );

  // 4. <br>/<br /> -> Markdown hard line break (two trailing spaces).
  $html = preg_replace('#<br\s*/?>#i', "  \n", $html);

  // 5. Any leftover <div>/</div> wrapper (no other tags ever appear).
  $html = preg_replace('#</?div[^>]*>#i', '', $html);

  // 6. Bare URLs -> Markdown's explicit autolink syntax, skipping ones
  //    already inside a markdown link target.
  $html = preg_replace('#(?<!\]\()(https?://[^\s<)\]]+)#i', '<$1>', $html);

  return trim(html_entity_decode($html, ENT_QUOTES, 'UTF-8'));
}

/**
 * Resolves a bulletin.subject/message serialized-per-locale blob to plain
 * text - identical logic to inbox_helper.php's existing _bulletinText().
 */
function bulletinText($serialized) {
  $locales = @unserialize($serialized, array('allowed_classes' => FALSE));
  if (!is_array($locales) || empty($locales)) {
    return '';
  }
  return html_entity_decode(isset($locales['en_EN']) ? $locales['en_EN'] : reset($locales), ENT_QUOTES, 'UTF-8');
}

$mysqli->begin_transaction();

try {
  $summary = array();

  // === message + message_recipient, sourced solely from /sent/ + bulletins ===
  $sent_rows = $mysqli->query("SELECT id, sender_id, subject, message, date FROM bulletin WHERE path = '/sent/' ORDER BY id");
  $message_count = 0;
  $recipient_count = 0;
  $defaulted_state_count = 0;

  while ($sent = $sent_rows->fetch_assoc()) {
    $subject_text = bulletinText($sent['subject']);
    $body_md = bulletinHtmlToMarkdown(bulletinText($sent['message']));

    $stmt = $mysqli->prepare("INSERT INTO message (sender_id, type, subject, body, sender_hidden, date) VALUES (?, 'message', ?, ?, 0, ?)");
    $stmt->bind_param('isss', $sent['sender_id'], $subject_text, $body_md, $sent['date']);
    $stmt->execute();
    $message_id = $mysqli->insert_id;
    $message_count++;

    $recip_stmt = $mysqli->prepare("SELECT receiver_id FROM bulletins WHERE bulletin_id = ?");
    $recip_stmt->bind_param('i', $sent['id']);
    $recip_stmt->execute();
    $recip_result = $recip_stmt->get_result();

    while ($recip = $recip_result->fetch_assoc()) {
      $receiver_id = (int) $recip['receiver_id'];

      // Enrich with the recipient's real read-state/folder when their
      // physical copy still exists (same sender+subject+recipient).
      $enrich_stmt = $mysqli->prepare("SELECT state, path FROM bulletin WHERE sender_id = ? AND subject = ? AND inbox = ? AND path IN ('/inbox/', '/trash/') LIMIT 1");
      $enrich_stmt->bind_param('isi', $sent['sender_id'], $sent['subject'], $receiver_id);
      $enrich_stmt->execute();
      $enrich_row = $enrich_stmt->get_result()->fetch_assoc();

      if ($enrich_row) {
        $state = (int) $enrich_row['state'];
        $folder = ($enrich_row['path'] === '/trash/') ? 'trash' : 'inbox';
      }
      else {
        $state = 1;
        $folder = 'inbox';
        $defaulted_state_count++;
      }

      $mr_stmt = $mysqli->prepare("INSERT INTO message_recipient (message_id, recipient_id, folder, state) VALUES (?, ?, ?, ?)");
      $mr_stmt->bind_param('iisi', $message_id, $receiver_id, $folder, $state);
      $mr_stmt->execute();
      $recipient_count++;
    }
  }
  $summary['message (from /sent/)'] = $message_count;
  $summary['message_recipient'] = $recipient_count;
  $summary['  of which defaulted (no physical copy to enrich from)'] = $defaulted_state_count;

  // === message (type='share'), one recipient each, from /shares/ ===
  $share_rows = $mysqli->query("SELECT id, sender_id, inbox, subject, message, state, date FROM bulletin WHERE path = '/shares/' ORDER BY id");
  $share_count = 0;

  while ($share = $share_rows->fetch_assoc()) {
    $subject_text = bulletinText($share['subject']);
    $body_md = bulletinHtmlToMarkdown(bulletinText($share['message']));

    $stmt = $mysqli->prepare("INSERT INTO message (sender_id, type, subject, body, sender_hidden, date) VALUES (?, 'share', ?, ?, 0, ?)");
    $stmt->bind_param('isss', $share['sender_id'], $subject_text, $body_md, $share['date']);
    $stmt->execute();
    $message_id = $mysqli->insert_id;

    $recipient_id = (int) $share['inbox'];
    $state = (int) $share['state'];
    $folder = 'inbox';
    $mr_stmt = $mysqli->prepare("INSERT INTO message_recipient (message_id, recipient_id, folder, state) VALUES (?, ?, ?, ?)");
    $mr_stmt->bind_param('iisi', $message_id, $recipient_id, $folder, $state);
    $mr_stmt->execute();
    $share_count++;
  }
  $summary['message (from /shares/)'] = $share_count;

  // === notification, from /notices/ + /notifications/ ===
  $notif_rows = $mysqli->query("SELECT id, inbox, path, subject, message, state, date FROM bulletin WHERE path IN ('/notices/', '/notifications/') ORDER BY id");
  $notif_count = 0;

  while ($n = $notif_rows->fetch_assoc()) {
    $subject_text = bulletinText($n['subject']);
    $body_md = bulletinHtmlToMarkdown(bulletinText($n['message']));
    $type = ($n['path'] === '/notices/') ? 'notice' : 'notification';
    $recipient_id = (int) $n['inbox'];
    $state = (int) $n['state'];

    $stmt = $mysqli->prepare("INSERT INTO notification (recipient_id, type, subject, body, state, date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('isssis', $recipient_id, $type, $subject_text, $body_md, $state, $n['date']);
    $stmt->execute();
    $notif_count++;
  }
  $summary['notification'] = $notif_count;

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
