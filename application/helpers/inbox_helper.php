<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Gets all messages/notifications in a given folder for a given user.
  *
  * @param array $opts.
  *          'folder'   => 'inbox', 'sent', 'notices', 'notifications', 'shares', or 'trash'.
  *          'user_id'  => Viewing user's ID (server-derived, never client-supplied).
  *
  * @return string JSON encoded list.
  */
if (!function_exists('getBulletins')) {
  function getBulletins($opts = array()) {
    $folder = isset($opts['folder']) ? $opts['folder'] : 'inbox';
    $user_id = isset($opts['user_id']) ? $opts['user_id'] : 0;

    if ($folder === 'sent') {
      $results = _getSentMessages($user_id);
    }
    elseif ($folder === 'notices' || $folder === 'notifications') {
      $results = _getNotifications(($folder === 'notices') ? 'notice' : 'notification', $user_id);
    }
    else {
      $results = _getReceivedMessages($folder, $user_id);
    }

    if (!empty($results)) {
      header('HTTP/1.1 200 OK');
      return json_encode($results);
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
  *
  * @return array Rows shaped for getBulletins()'s JSON output.
  */
if (!function_exists('_getReceivedMessages')) {
  function _getReceivedMessages($folder, $user_id) {
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

    $sql = "SELECT " . TBL_message . ".`id`,
                   " . TBL_message . ".`sender_id` AS `other_user_id`,
                   " . TBL_user . ".`username` AS `other_username`,
                   " . TBL_message . ".`subject`,
                   " . TBL_message . ".`body`,
                   " . TBL_message_recipient . ".`state`,
                   " . TBL_message . ".`date`
            FROM " . TBL_message_recipient . "
            INNER JOIN " . TBL_message . " ON " . TBL_message . ".`id` = " . TBL_message_recipient . ".`message_id`
            INNER JOIN " . TBL_user . " ON " . TBL_user . ".`id` = " . TBL_message . ".`sender_id`
            WHERE " . TBL_message_recipient . ".`recipient_id` = ?
              AND " . TBL_message_recipient . ".`folder` = ?
              " . $type_filter . "
            ORDER BY " . TBL_message . ".`date` DESC";
    $rows = $ci->db->query($sql, array($user_id, $mr_folder))->result_array();

    $results = array();
    foreach ($rows as $row) {
      $results[] = array(
        'id' => (int) $row['id'],
        'type' => 'message',
        'is_sent' => 0,
        'other_users' => array(array('user_id' => (int) $row['other_user_id'], 'username' => $row['other_username'])),
        'subject' => $row['subject'],
        'message' => $row['body'],
        'state' => (int) $row['state'],
        'date' => $row['date']
      );
    }
    return $results;
  }
}

/**
  * Gets messages a user has sent, with every real recipient attached.
  *
  * @param int $user_id Viewing (sender) user's ID.
  *
  * @return array Rows shaped for getBulletins()'s JSON output.
  */
if (!function_exists('_getSentMessages')) {
  function _getSentMessages($user_id) {
    $ci=& get_instance();
    $ci->load->database();

    $sql = "SELECT " . TBL_message . ".`id`,
                   " . TBL_message . ".`subject`,
                   " . TBL_message . ".`body`,
                   " . TBL_message . ".`date`,
                   " . TBL_message_recipient . ".`recipient_id` AS `other_user_id`,
                   " . TBL_user . ".`username` AS `other_username`
            FROM " . TBL_message . "
            LEFT JOIN " . TBL_message_recipient . " ON " . TBL_message_recipient . ".`message_id` = " . TBL_message . ".`id`
            LEFT JOIN " . TBL_user . " ON " . TBL_user . ".`id` = " . TBL_message_recipient . ".`recipient_id`
            WHERE " . TBL_message . ".`sender_id` = ?
              AND " . TBL_message . ".`sender_hidden` = 0
              AND " . TBL_message . ".`type` = 'message'
            ORDER BY " . TBL_message . ".`date` DESC, " . TBL_user . ".`username` ASC";
    $rows = $ci->db->query($sql, array($user_id))->result_array();

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
          'state' => 1,
          'date' => $row['date']
        );
      }
      if ($row['other_user_id'] !== NULL) {
        $messages[$id]['other_users'][] = array('user_id' => (int) $row['other_user_id'], 'username' => $row['other_username']);
      }
    }
    return array_values($messages);
  }
}

/**
  * Gets a user's system notifications of a given type.
  *
  * @param string $type 'notice' or 'notification'.
  * @param int $user_id Viewing (recipient) user's ID.
  *
  * @return array Rows shaped for getBulletins()'s JSON output.
  */
if (!function_exists('_getNotifications')) {
  function _getNotifications($type, $user_id) {
    $ci=& get_instance();
    $ci->load->database();

    $sql = "SELECT " . TBL_notification . ".`id`,
                   " . TBL_notification . ".`subject`,
                   " . TBL_notification . ".`body`,
                   " . TBL_notification . ".`state`,
                   " . TBL_notification . ".`date`
            FROM " . TBL_notification . "
            WHERE " . TBL_notification . ".`recipient_id` = ?
              AND " . TBL_notification . ".`type` = ?
            ORDER BY " . TBL_notification . ".`date` DESC";
    $rows = $ci->db->query($sql, array($user_id, $type))->result_array();

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
