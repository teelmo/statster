<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Gets all bulletins in a given folder for a given user.
  *
  * @param array $opts.
  *          'path'     => Folder, e.g. '/inbox/', '/sent/'.
  *          'user_id'  => Viewing user's ID (server-derived, never client-supplied).
  *
  * @return string JSON encoded list of bulletins.
  */
if (!function_exists('getBulletins')) {
  function getBulletins($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $path = isset($opts['path']) ? $opts['path'] : '/inbox/';
    $user_id = isset($opts['user_id']) ? $opts['user_id'] : 0;
    // In /sent/, the viewer is the sender and `inbox` holds the recipient -
    // every other folder, the viewer is the recipient (`inbox`).
    $owner_column = ($path === '/sent/') ? 'sender_id' : 'inbox';
    $other_column = ($path === '/sent/') ? 'inbox' : 'sender_id';

    $sql = "SELECT " . TBL_bulletin . ".`id`,
                   " . TBL_bulletin . ".`" . $other_column . "` AS `other_user_id`,
                   " . TBL_user . ".`username` AS `other_username`,
                   " . TBL_bulletin . ".`subject`,
                   " . TBL_bulletin . ".`message`,
                   " . TBL_bulletin . ".`state`,
                   " . TBL_bulletin . ".`path`,
                   " . TBL_bulletin . ".`date`
            FROM " . TBL_bulletin . "
            INNER JOIN " . TBL_user . " ON " . TBL_user . ".`id` = " . TBL_bulletin . ".`" . $other_column . "`
            WHERE " . TBL_bulletin . ".`path` = ?
              AND " . TBL_bulletin . ".`" . $owner_column . "` = ?
            ORDER BY " . TBL_bulletin . ".`date` DESC";
    $query = $ci->db->query($sql, array($path, $user_id));

    $results = array();
    foreach ($query->result_array() as $row) {
      $results[] = array(
        'id' => (int) $row['id'],
        'other_user_id' => (int) $row['other_user_id'],
        'other_username' => $row['other_username'],
        'subject' => _bulletinText($row['subject']),
        'message' => _bulletinText($row['message']),
        'state' => (int) $row['state'],
        'path' => $row['path'],
        'date' => $row['date']
      );
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
  * Resolves a bulletin's serialized per-locale subject/message to plain text.
  *
  * @param string $serialized PHP serialize()'d array keyed by locale.
  *
  * @return string Plain text, preferring 'en_EN', else the first available locale.
  */
if (!function_exists('_bulletinText')) {
  function _bulletinText($serialized) {
    $locales = @unserialize($serialized, array('allowed_classes' => FALSE));
    if (!is_array($locales) || empty($locales)) {
      return '';
    }
    return isset($locales['en_EN']) ? $locales['en_EN'] : reset($locales);
  }
}

/**
  * Marks a bulletin as read.
  *
  * @param array $opts.
  *          'id'       => Bulletin ID.
  *          'user_id'  => Viewing user's ID (server-derived, never client-supplied).
  *
  * @return void
  */
if (!function_exists('updateBulletin')) {
  function updateBulletin($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $id = isset($opts['id']) ? $opts['id'] : 0;
    $user_id = isset($opts['user_id']) ? $opts['user_id'] : 0;
    if (empty($user_id)) {
      header('HTTP/1.1 401 Unauthorized');
      return;
    }

    $sql = "UPDATE " . TBL_bulletin . "
            SET `state` = 1
            WHERE `id` = ?
              AND `inbox` = ?";
    $ci->db->query($sql, array($id, $user_id));
    header($ci->db->affected_rows() > 0 ? 'HTTP/1.1 200 OK' : 'HTTP/1.1 204 No Content');
  }
}
?>
