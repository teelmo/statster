<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * Tells if the given album is loved by the given user.
 *
 * @param array $opts.
 *          'album_id'  => Artist ID
 *          'user_id'   => User ID
 *
 * @return string JSON.
 */
if (!function_exists('getLove')) {
  function getLove($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    
    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : '';
    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT " . TBL_love . ".`id`, 'love' as `type`
            FROM " . TBL_love . "
            WHERE " . TBL_love . ".`user_id` LIKE " . $ci->db->escape($user_id) . "
              AND " . TBL_love . ".`album_id` = " . $ci->db->escape($album_id);
    $query = $ci->db->query($sql);
    return _json_return_helper($query, $human_readable);
  }
}

/**
 * Add love information
 *
 * @param array $opts.
 *          'album_id'  => Album ID
 *
 * @return string JSON.
 */
if (!function_exists('addLove')) {
  function addLove($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    // Get user id from session
    if (!$opts['user_id'] = $ci->session->userdata('user_id')) {
      header("HTTP/1.1 401 Unauthorized");
      return json_encode(array('error' => array('msg' => 'Username error. Can\'t solve user id.')));
    }

    // Check artist id */
    if (empty($opts['album_id'])) {
      header("HTTP/1.1 404 Not Found");
      return json_encode(array('error' => array('msg' => 'Album error. Can\'t solve album id.')));
    }

    // Add fan data to DB
    $sql = "INSERT
              INTO " . TBL_love . " (`user_id`, `album_id`)
              VALUES ({$opts['user_id']}, {$opts['album_id']})";
    $query = $ci->db->query($sql);
    if ($ci->db->affected_rows() == 1) {
      $opts['fan_id'] = $ci->db->insert_id();
      header("HTTP/1.1 201 Created");
      return json_encode(array('success' => array('msg' => $opts)));
    }
    else {
      header("HTTP/1.1 400 Bad Request");
      return json_encode(array('error' => array('msg' => ERR_GENERAL)));
    }
  }
}
?>