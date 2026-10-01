<?php
/**
 * One-time backfill: infers message.parent_id for existing messages using
 * the old "RE: subject" convention - the only signal available, since no
 * real thread link was ever stored in the legacy bulletin data (confirmed:
 * no thread/parent/reply column exists anywhere in that schema). This is
 * best-effort reconstruction, not a guaranteed-accurate one.
 *
 * For each message whose subject has a leading "RE: " (repeated prefixes
 * stripped too, e.g. "RE: RE: X"), finds the most recent earlier message
 * with the same base subject between the same two people (sender/recipient
 * in either direction, to catch reply-to and reply-from both), and sets
 * parent_id to it. Only considers type='message' - shares are always
 * system-template subjects, never genuine replies.
 *
 * Usage: CI_PASSWD=<password> php scripts/link_message_threads.php
 *
 * Idempotent-safe only from a clean slate: refuses to run if any message
 * already has parent_id set.
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

$result = $mysqli->query("SELECT COUNT(*) AS c FROM message WHERE parent_id IS NOT NULL");
$already_linked = (int) $result->fetch_assoc()['c'];
if ($already_linked > 0) {
  fwrite(STDERR, "Refusing to run: $already_linked message(s) already have parent_id set.\n");
  exit(1);
}

function stripReplyPrefix($subject) {
  while (preg_match('/^re:\s*/i', $subject)) {
    $subject = preg_replace('/^re:\s*/i', '', $subject, 1);
  }
  return trim($subject);
}

// Load every message, including shares - a reply ("RE: Shared album: X")
// is always type='message', but what it's replying to can be a share.
$messages = array();
$result = $mysqli->query("SELECT id, sender_id, type, subject, date FROM message ORDER BY date ASC, id ASC");
while ($row = $result->fetch_assoc()) {
  $id = (int) $row['id'];
  $messages[$id] = array(
    'id' => $id,
    'sender_id' => (int) $row['sender_id'],
    'type' => $row['type'],
    'subject' => $row['subject'],
    'base_subject' => stripReplyPrefix($row['subject']),
    'date' => $row['date'],
    'recipients' => array()
  );
}

$result = $mysqli->query("SELECT message_id, recipient_id FROM message_recipient");
while ($row = $result->fetch_assoc()) {
  $message_id = (int) $row['message_id'];
  if (isset($messages[$message_id])) {
    $messages[$message_id]['recipients'][] = (int) $row['recipient_id'];
  }
}

// Index messages by base subject, so candidates are cheap to look up.
$by_subject = array();
foreach ($messages as $id => $m) {
  $by_subject[$m['base_subject']][] = $id;
}

$linked = 0;
$no_match = 0;

foreach ($messages as $id => $m) {
  if ($m['type'] !== 'message' || $m['subject'] === $m['base_subject']) {
    continue; // Shares are never replies themselves; no "RE: " prefix means not a reply either.
  }

  $candidates = isset($by_subject[$m['base_subject']]) ? $by_subject[$m['base_subject']] : array();
  $best_id = null;
  $best_date = null;

  foreach ($candidates as $candidate_id) {
    if ($candidate_id === $id) {
      continue;
    }
    $c = $messages[$candidate_id];
    if ($c['date'] >= $m['date']) {
      continue; // A parent must come strictly before its reply.
    }
    // Same two people, in either direction.
    $related = in_array($m['sender_id'], $c['recipients'], TRUE)
      || in_array($c['sender_id'], $m['recipients'], TRUE);
    if (!$related) {
      continue;
    }
    if ($best_id === null || $c['date'] > $best_date) {
      $best_id = $candidate_id;
      $best_date = $c['date'];
    }
  }

  if ($best_id !== null) {
    $stmt = $mysqli->prepare("UPDATE message SET parent_id = ? WHERE id = ?");
    $stmt->bind_param('ii', $best_id, $id);
    $stmt->execute();
    $linked++;
  }
  else {
    $no_match++;
  }
}

echo "Messages with a 'RE:' subject: " . ($linked + $no_match) . "\n";
echo "  linked to a parent: $linked\n";
echo "  no matching earlier message found: $no_match\n";
