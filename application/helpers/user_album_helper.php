<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Gets the formats (from listening_format) that make sense to mark an
  * album as owned in - excludes Not Chosen/Stream/Live, which describe how
  * something was listened to, not a format you can physically or digitally
  * own a copy of.
  *
  * @return array Rows: id, name, img.
  */
if (!function_exists('getOwnableFormats')) {
  function getOwnableFormats() {
    $ci=& get_instance();
    $ci->load->database();

    $sql = "SELECT " . TBL_listening_format . ".`id`,
                   " . TBL_listening_format . ".`name`,
                   " . TBL_listening_format . ".`img`
            FROM " . TBL_listening_format . "
            WHERE " . TBL_listening_format . ".`name` NOT IN ('Not Chosen', 'Stream', 'Live')
            ORDER BY " . TBL_listening_format . ".`name` ASC";
    $query = $ci->db->query($sql);
    return $query->result_array();
  }
}

/**
  * Tells which format(s) the given album is owned in by the given user.
  *
  * @param array $opts.
  *          'album_id'  => Album ID
  *          'user_id'   => User ID
  *
  * @return string JSON.
  */
if (!function_exists('getOwnedAlbum')) {
  function getOwnedAlbum($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : 0;
    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : 0;

    $sql = "SELECT " . TBL_user_album . ".`listening_format_id`
            FROM " . TBL_user_album . "
            WHERE " . TBL_user_album . ".`record_id` = ?
              AND " . TBL_user_album . ".`user_id` = ?";
    $query = $ci->db->query($sql, array($album_id, $user_id));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}

/**
  * Marks an album as owned, in a specific format, by the logged-in user.
  *
  * @param int $album_id Album ID.
  * @param int $format_id listening_format ID.
  *
  * @return string JSON.
  */
if (!function_exists('addOwnedAlbum')) {
  function addOwnedAlbum($album_id, $format_id) {
    $ci=& get_instance();
    $ci->load->database();

    // Get user id from session
    if (!$user_id = $ci->session->userdata('user_id')) {
      return header('HTTP/1.1 401 Unauthorized');
    }

    $sql = "INSERT
              INTO " . TBL_user_album . " (`user_id`, `record_id`, `listening_format_id`)
              VALUES (?, ?, ?)";
    $query = $ci->db->query($sql, array($user_id, $album_id, $format_id));
    if ($ci->db->affected_rows() === 1) {
      header('HTTP/1.1 201 Created');
      return json_encode(array('success' => array('msg' => $ci->db->insert_id())));
    }
    return header('HTTP/1.1 400 Bad Request');
  }
}

/**
  * Unmarks an album as owned in a specific format by the logged-in user.
  *
  * @param int $album_id Album ID.
  * @param int $format_id listening_format ID.
  *
  * @return string JSON.
  */
if (!function_exists('deleteOwnedAlbum')) {
  function deleteOwnedAlbum($album_id, $format_id) {
    $ci=& get_instance();
    $ci->load->database();

    // Get user id from session
    if (!$user_id = $ci->session->userdata('user_id')) {
      return header('HTTP/1.1 401 Unauthorized');
    }

    $ci->db->delete(TBL_user_album, array(
      'user_id' => $user_id,
      'record_id' => $album_id,
      'listening_format_id' => $format_id
    ));
    if ($ci->db->affected_rows() === 1) {
      return header('HTTP/1.1 204 No Content');
    }
    return header('HTTP/1.1 400 Bad Request');
  }
}
?>