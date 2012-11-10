<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
   * Tells if the given artist is faned by the given user.
   *
   * @param array $opts.
   *          'user_id'   => User ID
   *          'artist_id' => Artist ID
   *
   * @return string JSON.
   */
if (!function_exists('getFan')) {
  function getFan($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    // Load helpers
    $ci->load->helper(array('return_helper'));

    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $artist_id = !empty($opts['artist_id']) ? $opts['artist_id'] : '';
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT " . TBL_fan . ".`id`, 'fan' as `type`
            FROM " . TBL_fan . "
            WHERE " . TBL_fan . ".`user_id` LIKE " . $ci->db->escape($user_id) . "
              AND " . TBL_fan . ".`artist_id` = " . $ci->db->escape($artist_id);
    $query = $ci->db->query($sql);
    return _json_return_helper($query, $human_readable);
  }
}

/**
 * Add love information
 *
 * @param array $opts.
 *          'artist_id'  => Artist ID
 *
 * @return string JSON.
 */
if (!function_exists('addFan')) {
  function addFan($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    // Get user id from session
    if (!$opts['user_id'] = $ci->session->userdata('user_id')) {
      header("HTTP/1.1 401 Unauthorized");
      return json_encode(array('error' => array('msg' => 'Username error. Can\'t solve user id.')));
    }

    // Check artist id */
    if (empty($opts['artist_id'])) {
      header("HTTP/1.1 404 Not Found");
      return json_encode(array('error' => array('msg' => 'Artist error. Can\'t solve artist id.')));
    }
    
    // Add fan data to DB
    $sql = "INSERT
              INTO " . TBL_fan . " (`user_id`, `artist_id`)
              VALUES ({$opts['user_id']}, {$opts['artist_id']})";
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