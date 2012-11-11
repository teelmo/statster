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

    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $artist_id = !empty($opts['artist_id']) ? $opts['artist_id'] : '%';
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT " . TBL_fan . ".`id`, 'fan' as `type`
            FROM " . TBL_fan . "
            WHERE " . TBL_fan . ".`user_id` LIKE " . $ci->db->escape($user_id) . "
              AND " . TBL_fan . ".`artist_id` LIKE " . $ci->db->escape($artist_id);
    $query = $ci->db->query($sql);
    return _json_return_helper($query, $human_readable);
  }
}

/**
 * Add fan information
 *
 * @param array $opts.
 *          'artist_id'  => Artist ID
 *
 * @return string JSON.
 */
if (!function_exists('addFan')) {
  function addFan($artist_id) {
    $ci=& get_instance();
    $ci->load->database();

    // Get user id from session
    if (!$user_id = $ci->session->userdata('user_id')) {
      return header("HTTP/1.1 401 Unauthorized");
    }
    
    // Add fan data to DB
    $sql = "INSERT
              INTO " . TBL_fan . " (`user_id`, `artist_id`)
              VALUES ($user_id, $artist_id)";
    $query = $ci->db->query($sql);
    if ($ci->db->affected_rows() === 1) {
      header("HTTP/1.1 201 Created");
      return json_encode(array('success' => array('msg' => $ci->db->insert_id())));
    }
    return header("HTTP/1.1 400 Bad Request");
  }
}

/**
 * Delete fan information
 *
 * @param array $opts.
 *          'artist_id'  => Artist ID
 *
 * @return string JSON.
 */
if (!function_exists('deleteFan')) {
  function deleteFan($artist_id) {
    $ci=& get_instance();
    $ci->load->database();

    // Get user id from session
    if (!$user_id = $ci->session->userdata('user_id')) {
      return header("HTTP/1.1 401 Unauthorized");
    }

    $ci->db->query('SET AUTOCOMMIT = 0');
    // Add log data
    $sql = "INSERT
              INTO " . TBL_fan_log . " (`user_id`, `artist_id`, `added`)
              VALUES ($user_id, $artist_id,
               (SELECT " . TBL_fan . ".`created`
                FROM " . TBL_fan . "
                WHERE " . TBL_fan . ".`user_id` = $user_id
                  AND " . TBL_fan . ".`artist_id` = $artist_id))";
    $query = $ci->db->query($sql);
    if ($ci->db->affected_rows() === 1) {
      // Delete fan data from DB
      $query = $ci->db->delete(TBL_fan, array('user_id' => $user_id,
                                              'artist_id' => $artist_id));
      $ci->db->query('COMMIT');
      $ci->db->query('SET AUTOCOMMIT = 1');
      if ($ci->db->affected_rows() === 0) {
        return header("HTTP/1.1 204 No Content");
      }
      return header("HTTP/1.1 400 Bad Request");
    }
    return header("HTTP/1.1 400 Bad Request");
  }
}
?>