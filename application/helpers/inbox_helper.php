<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

if (!defined('INBOX_PAGE_SIZE')) {
  define('INBOX_PAGE_SIZE', 20);
}

/**
  * Gets one page of messages/notifications in a given folder for a given user.
  *
  * @param array $opts.
  *          'folder'   => 'inbox', 'sent', 'notices', 'notifications', 'shares', or 'trash'.
  *          'user_id'  => Viewing user's ID (server-derived, never client-supplied).
  *          'page'     => 1-indexed page number.
  *
  * @return string JSON encoded {rows, page, has_more, has_prev}.
  */
if (!function_exists('getBulletins')) {
  function getBulletins($opts = array()) {
    $folder = isset($opts['folder']) ? $opts['folder'] : 'inbox';
    $user_id = isset($opts['user_id']) ? $opts['user_id'] : 0;
    $page = isset($opts['page']) ? max(1, (int) $opts['page']) : 1;

    if ($folder === 'sent') {
      $page_result = _getSentMessages($user_id, $page);
    }
    elseif ($folder === 'notices' || $folder === 'notifications') {
      $page_result = _getNotifications(($folder === 'notices') ? 'notice' : 'notification', $user_id, $page);
    }
    else {
      $page_result = _getReceivedMessages($folder, $user_id, $page);
    }

    if (!empty($page_result['rows'])) {
      header('HTTP/1.1 200 OK');
      return json_encode(array(
        'rows' => $page_result['rows'],
        'page' => $page,
        'has_more' => $page_result['has_more'],
        'has_prev' => ($page > 1)
      ));
    }
    header('HTTP/1.1 204 No Content');
    return '';
  }
}

/**
  * Gets messages/shares a user has received, filed in a given folder.
  *
  * @param string $folder 'inbox', 'shares', or 'trash'.
  * @param int $user_id Viewing (recipient) user's ID.
  * @param int $page 1-indexed page number.
  *
  * @return array {rows, has_more} - rows shaped for getBulletins()'s JSON output.
  */
if (!function_exists('_getReceivedMessages')) {
  function _getReceivedMessages($folder, $user_id, $page = 1) {
    $ci=& get_instance();
    $ci->load->database();

    $mr_folder = ($folder === 'trash') ? 'trash' : 'inbox';
    $type_filter = '';
    if ($folder === 'shares') {
      $type_filter = "AND " . TBL_message . ".`type` = 'share'";
    }
    elseif ($folder === 'inbox') {
      $type_filter = "AND " . TBL_message . ".`type` = 'message'";
    }
    // Trash shows both types - a user's trash isn't split by what kind of thing they binned.

    $limit = INBOX_PAGE_SIZE;
    $offset = ($page - 1) * $limit;

    $sql = "SELECT " . TBL_message . ".`id`,
                   " . TBL_message . ".`sender_id` AS `other_user_id`,
                   " . TBL_user . ".`username` AS `other_username`,
                   " . TBL_message . ".`subject`,
                   " . TBL_message . ".`body`,
                   " . TBL_message . ".`parent_id`,
                   (SELECT COUNT(*) FROM " . TBL_message . " AS `m2` WHERE `m2`.`parent_id` = " . TBL_message . ".`id`) AS `reply_count`,
                   " . TBL_message_recipient . ".`state`,
                   " . TBL_message . ".`date`
            FROM " . TBL_message_recipient . "
            INNER JOIN " . TBL_message . " ON " . TBL_message . ".`id` = " . TBL_message_recipient . ".`message_id`
            INNER JOIN " . TBL_user . " ON " . TBL_user . ".`id` = " . TBL_message . ".`sender_id`
            WHERE " . TBL_message_recipient . ".`recipient_id` = ?
              AND " . TBL_message_recipient . ".`folder` = ?
              " . $type_filter . "
            ORDER BY " . TBL_message . ".`date` DESC
            LIMIT ? OFFSET ?";
    $rows = $ci->db->query($sql, array($user_id, $mr_folder, $limit + 1, $offset))->result_array();
    $has_more = count($rows) > $limit;
    $rows = array_slice($rows, 0, $limit);

    $results = array();
    foreach ($rows as $row) {
      $results[] = array(
        'id' => (int) $row['id'],
        'type' => 'message',
        'is_sent' => 0,
        'other_users' => array(array('user_id' => (int) $row['other_user_id'], 'username' => $row['other_username'])),
        'subject' => $row['subject'],
        'message' => $row['body'],
        'in_thread' => ($row['parent_id'] !== NULL || (int) $row['reply_count'] > 0) ? 1 : 0,
        'state' => (int) $row['state'],
        'date' => $row['date']
      );
    }
    return array('rows' => $results, 'has_more' => $has_more);
  }
}

/**
  * Gets messages a user has sent, with every real recipient attached.
  *
  * @param int $user_id Viewing (sender) user's ID.
  * @param int $page 1-indexed page number.
  *
  * @return array {rows, has_more} - rows shaped for getBulletins()'s JSON output.
  */
if (!function_exists('_getSentMessages')) {
  function _getSentMessages($user_id, $page = 1) {
    $ci=& get_instance();
    $ci->load->database();

    $limit = INBOX_PAGE_SIZE;
    $offset = ($page - 1) * $limit;

    // Page over distinct messages first - the join below fans out to one row
    // per recipient, so paging that directly would split/duplicate messages
    // with more than one recipient across pages.
    $sql = "SELECT `id`
            FROM " . TBL_message . "
            WHERE `sender_id` = ?
              AND `sender_hidden` = 0
              AND `type` = 'message'
            ORDER BY `date` DESC, `id` DESC
            LIMIT ? OFFSET ?";
    $id_rows = $ci->db->query($sql, array($user_id, $limit + 1, $offset))->result_array();
    $has_more = count($id_rows) > $limit;
    $id_rows = array_slice($id_rows, 0, $limit);
    $ids = array();
    foreach ($id_rows as $id_row) {
      $ids[] = (int) $id_row['id'];
    }
    if (empty($ids)) {
      return array('rows' => array(), 'has_more' => FALSE);
    }

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sql = "SELECT " . TBL_message . ".`id`,
                   " . TBL_message . ".`subject`,
                   " . TBL_message . ".`body`,
                   " . TBL_message . ".`parent_id`,
                   (SELECT COUNT(*) FROM " . TBL_message . " AS `m2` WHERE `m2`.`parent_id` = " . TBL_message . ".`id`) AS `reply_count`,
                   " . TBL_message . ".`date`,
                   " . TBL_message_recipient . ".`recipient_id` AS `other_user_id`,
                   " . TBL_user . ".`username` AS `other_username`
            FROM " . TBL_message . "
            LEFT JOIN " . TBL_message_recipient . " ON " . TBL_message_recipient . ".`message_id` = " . TBL_message . ".`id`
            LEFT JOIN " . TBL_user . " ON " . TBL_user . ".`id` = " . TBL_message_recipient . ".`recipient_id`
            WHERE " . TBL_message . ".`id` IN ($placeholders)
            ORDER BY " . TBL_user . ".`username` ASC";
    $rows = $ci->db->query($sql, $ids)->result_array();

    // Group by message id - a message can have multiple recipient rows.
    $messages = array();
    foreach ($rows as $row) {
      $id = (int) $row['id'];
      if (!isset($messages[$id])) {
        $messages[$id] = array(
          'id' => $id,
          'type' => 'message',
          'is_sent' => 1,
          'other_users' => array(),
          'subject' => $row['subject'],
          'message' => $row['body'],
          'in_thread' => ($row['parent_id'] !== NULL || (int) $row['reply_count'] > 0) ? 1 : 0,
          'state' => 1,
          'date' => $row['date']
        );
      }
      if ($row['other_user_id'] !== NULL) {
        $messages[$id]['other_users'][] = array('user_id' => (int) $row['other_user_id'], 'username' => $row['other_username']);
      }
    }

    // Restore the original date-ordered page sequence - the join query above
    // orders by recipient username (for stable grouping), not date.
    $results = array();
    foreach ($ids as $id) {
      if (isset($messages[$id])) {
        $results[] = $messages[$id];
      }
    }
    return array('rows' => $results, 'has_more' => $has_more);
  }
}

/**
  * Gets a user's system notifications of a given type.
  *
  * @param string $type 'notice' or 'notification'.
  * @param int $user_id Viewing (recipient) user's ID.
  * @param int $page 1-indexed page number.
  *
  * @return array {rows, has_more} - rows shaped for getBulletins()'s JSON output.
  */
if (!function_exists('_getNotifications')) {
  function _getNotifications($type, $user_id, $page = 1) {
    $ci=& get_instance();
    $ci->load->database();

    $limit = INBOX_PAGE_SIZE;
    $offset = ($page - 1) * $limit;

    $sql = "SELECT " . TBL_notification . ".`id`,
                   " . TBL_notification . ".`subject`,
                   " . TBL_notification . ".`body`,
                   " . TBL_notification . ".`state`,
                   " . TBL_notification . ".`date`
            FROM " . TBL_notification . "
            WHERE " . TBL_notification . ".`recipient_id` = ?
              AND " . TBL_notification . ".`type` = ?
            ORDER BY " . TBL_notification . ".`date` DESC
            LIMIT ? OFFSET ?";
    $rows = $ci->db->query($sql, array($user_id, $type, $limit + 1, $offset))->result_array();
    $has_more = count($rows) > $limit;
    $rows = array_slice($rows, 0, $limit);

    $results = array();
    foreach ($rows as $row) {
      $results[] = array(
        'id' => (int) $row['id'],
        'type' => 'notification',
        'is_sent' => 0,
        'other_users' => array(),
        'subject' => $row['subject'],
        'message' => $row['body'],
        'state' => (int) $row['state'],
        'date' => $row['date']
      );
    }
    return array('rows' => $results, 'has_more' => $has_more);
  }
}

/**
  * Gets every message in the same thread as a given message (its full
  * ancestor chain and every descendant reply, walked via parent_id), for a
  * participant to view the whole conversation at once.
  *
  * @param array $opts.
  *          'message_id' => The message whose thread to fetch.
  *          'user_id'    => Viewing user's ID (server-derived, never client-supplied).
  *
  * @return array Rows shaped like getBulletins()'s output, newest first -
  *         matching the folder list above it, so reading down from the
  *         toggled row continues naturally backward in time.
  */
if (!function_exists('getThread')) {
  function getThread($opts = array()) {
    $message_id = isset($opts['message_id']) ? (int) $opts['message_id'] : 0;
    $user_id = isset($opts['user_id']) ? (int) $opts['user_id'] : 0;

    $ci=& get_instance();
    $ci->load->database();

    // Only a participant (sender or recipient) may view the thread.
    $sql = "SELECT " . TBL_message . ".`id`
            FROM " . TBL_message . "
            LEFT JOIN " . TBL_message_recipient . " ON " . TBL_message_recipient . ".`message_id` = " . TBL_message . ".`id`
            WHERE " . TBL_message . ".`id` = ?
              AND (" . TBL_message . ".`sender_id` = ? OR " . TBL_message_recipient . ".`recipient_id` = ?)
            LIMIT 1";
    $participant = $ci->db->query($sql, array($message_id, $user_id, $user_id))->row_array();
    if (!$participant) {
      return array();
    }

    // Walk outward (ancestors and every descendant reply) iteratively - no
    // recursive CTE, matching the rest of this codebase. Threads here are
    // shallow, so this is a handful of small round trips at most.
    $thread_ids = array($message_id => TRUE);
    $parent_of = array();
    $frontier = array($message_id);
    while (!empty($frontier)) {
      $placeholders = implode(',', array_fill(0, count($frontier), '?'));
      $sql = "SELECT `id`, `parent_id`
              FROM " . TBL_message . "
              WHERE `id` IN ($placeholders)
                 OR `parent_id` IN ($placeholders)";
      $rows = $ci->db->query($sql, array_merge($frontier, $frontier))->result_array();
      $next_frontier = array();
      foreach ($rows as $row) {
        $id = (int) $row['id'];
        $parent_id = ($row['parent_id'] !== NULL) ? (int) $row['parent_id'] : NULL;
        $parent_of[$id] = $parent_id;
        $found = array($id);
        if ($parent_id !== NULL) {
          $found[] = $parent_id;
        }
        foreach ($found as $found_id) {
          if (!isset($thread_ids[$found_id])) {
            $thread_ids[$found_id] = TRUE;
            $next_frontier[] = $found_id;
          }
        }
      }
      $frontier = $next_frontier;
    }

    // Depth within the thread (0 = root), for indenting replies in the UI
    // like a reddit-style comment chain.
    $depth_of = array();
    foreach ($thread_ids as $id => $_) {
      $depth = 0;
      $current = $id;
      while (isset($parent_of[$current]) && $parent_of[$current] !== NULL) {
        $current = $parent_of[$current];
        $depth++;
      }
      $depth_of[$id] = $depth;
    }

    $ids = array_keys($thread_ids);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $sql = "SELECT " . TBL_message . ".`id`,
                   " . TBL_message . ".`sender_id`,
                   " . TBL_user . ".`username` AS `sender_username`,
                   " . TBL_message . ".`subject`,
                   " . TBL_message . ".`body`,
                   " . TBL_message . ".`date`,
                   " . TBL_message_recipient . ".`state`
            FROM " . TBL_message . "
            INNER JOIN " . TBL_user . " ON " . TBL_user . ".`id` = " . TBL_message . ".`sender_id`
            LEFT JOIN " . TBL_message_recipient . " ON " . TBL_message_recipient . ".`message_id` = " . TBL_message . ".`id`
              AND " . TBL_message_recipient . ".`recipient_id` = ?
            WHERE " . TBL_message . ".`id` IN ($placeholders)
            ORDER BY " . TBL_message . ".`date` DESC, " . TBL_message . ".`id` DESC";
    $rows = $ci->db->query($sql, array_merge(array($user_id), $ids))->result_array();

    $sql = "SELECT " . TBL_message_recipient . ".`message_id`,
                   " . TBL_message_recipient . ".`recipient_id`,
                   " . TBL_user . ".`username`
            FROM " . TBL_message_recipient . "
            INNER JOIN " . TBL_user . " ON " . TBL_user . ".`id` = " . TBL_message_recipient . ".`recipient_id`
            WHERE " . TBL_message_recipient . ".`message_id` IN ($placeholders)";
    $recipient_rows = $ci->db->query($sql, $ids)->result_array();
    $recipients_by_message = array();
    foreach ($recipient_rows as $row) {
      $recipients_by_message[(int) $row['message_id']][] = array('user_id' => (int) $row['recipient_id'], 'username' => $row['username']);
    }

    $results = array();
    foreach ($rows as $row) {
      $id = (int) $row['id'];
      $is_sent = ((int) $row['sender_id'] === $user_id);
      $other_users = $is_sent
        ? (isset($recipients_by_message[$id]) ? $recipients_by_message[$id] : array())
        : array(array('user_id' => (int) $row['sender_id'], 'username' => $row['sender_username']));
      $results[] = array(
        'id' => $id,
        'type' => 'message',
        'is_sent' => $is_sent ? 1 : 0,
        'other_users' => $other_users,
        'sender' => array('user_id' => (int) $row['sender_id'], 'username' => $row['sender_username']),
        'subject' => $row['subject'],
        'message' => $row['body'],
        'depth' => $depth_of[$id],
        'state' => $is_sent ? 1 : (int) $row['state'],
        'date' => $row['date']
      );
    }
    return $results;
  }
}

/**
  * Marks a received message/share as read.
  *
  * @param array $opts.
  *          'id'       => message_recipient ID.
  *          'user_id'  => Viewing user's ID (server-derived, never client-supplied).
  *
  * @return void
  */
if (!function_exists('updateMessageRecipientState')) {
  function updateMessageRecipientState($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $id = isset($opts['id']) ? $opts['id'] : 0;
    $user_id = isset($opts['user_id']) ? $opts['user_id'] : 0;
    if (empty($user_id)) {
      header('HTTP/1.1 401 Unauthorized');
      return;
    }

    $sql = "UPDATE " . TBL_message_recipient . "
            SET `state` = 1
            WHERE `message_id` = ?
              AND `recipient_id` = ?";
    $ci->db->query($sql, array($id, $user_id));
    header($ci->db->affected_rows() > 0 ? 'HTTP/1.1 200 OK' : 'HTTP/1.1 204 No Content');
  }
}

/**
  * Marks a notification as read.
  *
  * @param array $opts.
  *          'id'       => notification ID.
  *          'user_id'  => Viewing user's ID (server-derived, never client-supplied).
  *
  * @return void
  */
if (!function_exists('updateNotificationState')) {
  function updateNotificationState($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $id = isset($opts['id']) ? $opts['id'] : 0;
    $user_id = isset($opts['user_id']) ? $opts['user_id'] : 0;
    if (empty($user_id)) {
      header('HTTP/1.1 401 Unauthorized');
      return;
    }

    $sql = "UPDATE " . TBL_notification . "
            SET `state` = 1
            WHERE `id` = ?
              AND `recipient_id` = ?";
    $ci->db->query($sql, array($id, $user_id));
    header($ci->db->affected_rows() > 0 ? 'HTTP/1.1 200 OK' : 'HTTP/1.1 204 No Content');
  }
}
?>
