<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * Tells if the given album is loved by the given user.
 *
 * @param array $opts.
 *          'album_id'  => Album ID
 *          'user_id'   => User ID
 *
 * @return string JSON.
 */
if (!function_exists('getLove')) {
  function getLove($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    
    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : '%';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT " . TBL_love . ".`id`, " . TBL_album . ".`id` as `album_id`, ". TBL_artist . ".`artist_name`, " . TBL_album . ".`album_name`, " . TBL_user . ".`username`, " . TBL_love . ".`created`, 'love' as `type`
            FROM " . TBL_love . ", " . TBL_artist . ", " . TBL_album . ", " . TBL_user . "
            WHERE " . TBL_love . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_love . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_love . ".`album_id` LIKE ?
              AND " . TBL_user . ".`username` LIKE ?
            ORDER BY " . TBL_love . ".`created` DESC
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($album_id, $username));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
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
  function addLove($album_id) {
    $ci=& get_instance();
    $ci->load->database();

    // Get user id from session
    if (!$user_id = $ci->session->userdata('user_id')) {
      return header('HTTP/1.1 401 Unauthorized');
    }

    // Add love data to DB
    $sql = "INSERT
              INTO " . TBL_love . " (`user_id`, `album_id`)
              VALUES (?, ?)";
    $query = $ci->db->query($sql, array($user_id, $album_id));
    if ($ci->db->affected_rows() === 1) {
      header('HTTP/1.1 201 Created');
      return json_encode(array('success' => array('msg' => $ci->db->insert_id())));
    }
    return header('HTTP/1.1 400 Bad Request');
  }
}

/**
 * Delete love information
 *
 * @param array $opts.
 *          'album_id'  => Album ID
 *
 * @return string JSON.
 */
if (!function_exists('deleteLove')) {
  function deleteLove($album_id) {
    $ci=& get_instance();
    $ci->load->database();

    // Get user id from session
    if (!$user_id = $ci->session->userdata('user_id')) {
      return header("HTTP/1.1 401 Unauthorized");
    }

    $ci->db->query('SET AUTOCOMMIT = 0');
    // Add log data
    $sql = "INSERT
              INTO " . TBL_love_log . " (`user_id`, `album_id`, `added`)
              VALUES (?, ?,
               (SELECT " . TBL_love . ".`created`
                FROM " . TBL_love . "
                WHERE " . TBL_love . ".`user_id` = ?
                  AND " . TBL_love . ".`album_id` = ?))";
    $query = $ci->db->query($sql, array($user_id, $album_id, $user_id, $album_id));
    if ($ci->db->affected_rows() === 1) {
      // Delete love data from DB
      $query = $ci->db->delete(TBL_love, array('user_id' => $user_id,
                                               'album_id' => $album_id));
      $ci->db->query('COMMIT');
      $ci->db->query('SET AUTOCOMMIT = 1');
      if ($ci->db->affected_rows() === 0) {
        return header('HTTP/1.1 204 No Content');
      }
      return header('HTTP/1.1 400 Bad Request');
    }
    return header('HTTP/1.1 400 Bad Request');
  }
}
?>