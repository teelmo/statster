<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Tells if the given album is loved by the given user.
  *
  * @param array $opts.
  *          'album_id'  => Album ID
  *          'limit'     => Limit
  *          'user_id'   => User ID
  *          'username'  => Username
  *
  * @return string JSON.
  */
if (!function_exists('getLove')) {
  function getLove($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    
    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : '%';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $select = !empty($opts['select']) ? $opts['select'] : '%';
    $sql = "SELECT " . TBL_love . ".`id`,
                   " . TBL_album . ".`id` as `album_id`,
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_album . ".`album_name`,
                   " . TBL_user . ".`username`,
                   " . TBL_love . ".`created`,
                   'heart' as `type`
            FROM " . TBL_love . ",
                 " . TBL_artist . ",
                 " . TBL_album . ",
                 " . TBL_user . "
            WHERE " . TBL_love . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_love . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_love . ".`album_id` LIKE ?
              AND " . TBL_user . ".`username` LIKE ?
              AND " . TBL_love . ".`user_id` LIKE ?
            ORDER BY " . TBL_love . ".`created` DESC
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($album_id, $username, $user_id));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Get most loved albums.
  *
  * @param array $opts.
  *          'album_id'  => Album ID
  *          'limit'     => Limit
  *
  * @return string JSON.
  */
if (!function_exists('getLoves')) {
  function getLoves($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    
    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : '%';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $sql = "SELECT count(*) as `count`,
                   " . TBL_album . ".`id` as `album_id`,
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_album . ".`album_name`,
                   'heart' as `type`
            FROM " . TBL_love . ",
                 " . TBL_artist . ",
                 " . TBL_album . "
            WHERE " . TBL_love . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_love . ".`album_id` LIKE ?
            GROUP BY " . TBL_love . ".`album_id`
            ORDER BY `count` DESC, 
                     " . TBL_album . ".`album_name` ASC
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($album_id));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Get love count.
  *
  * @param array $opts.
  *          'artist_id'  => Artist ID
  *          'user_id'    => User ID
  *
  * @return string JSON.
  */
if (!function_exists('getLoveCount')) {
  function getLoveCount($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_id = !empty($opts['album_id']) ? $opts['album_id'] : '%';
    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $sql = "SELECT " . TBL_love . ".`album_id`
            FROM " . TBL_love . "
            WHERE " . TBL_love . ".`album_id` LIKE ?
              AND " . TBL_love . ".`user_id` LIKE ?";
              
    $query = $ci->db->query($sql, array($artist_id, $user_id));
    return $query->num_rows();
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