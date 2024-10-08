<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Tells if the given artist is faned by the given user.
  *
  * @param array $opts.
  *          'artist_id' => Artist ID
  *          'limit'     => Limit
  *          'user_id'   => User ID
  *          'username'  => Username
  *
  * @return string JSON.
  */
if (!function_exists('getFan')) {
  function getFan($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_id = !empty($opts['artist_id']) ? $opts['artist_id'] : '%';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT " . TBL_fan . ".`id`,
                   " . TBL_artist . ".`id` as `artist_id`,
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_user . ".`username`,
                   " . TBL_fan . ".`created`,
                   'star' as `type`
            FROM " . TBL_fan . ",
                 " . TBL_artist . ",
                 " . TBL_user . "
            WHERE " . TBL_fan . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_fan . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_fan . ".`artist_id` LIKE ?
              AND " . TBL_user . ".`username` LIKE ?
              AND " . TBL_fan . ".`user_id` LIKE ?
            ORDER BY " . TBL_fan . ".`created` DESC
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($artist_id, $username, $user_id));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}

/**
  * Get most faned artists.
  *
  * @param array $opts.
  *          'artist_id'  => Artist ID
  *          'limit'      => Limit
  *
  * @return string JSON.
  */
if (!function_exists('getFans')) {
  function getFans($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    
    $artist_id = !empty($opts['artist_id']) ? $opts['artist_id'] : '%';
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT count(*) as `count`,
                   " . TBL_artist . ".`id` as `artist_id`,
                   " . TBL_artist . ".`artist_name`,
                   'star' as `type`
            FROM " . TBL_fan . ",
                 " . TBL_user . ",
                 " . TBL_artist . "
            WHERE " . TBL_fan . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_fan . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_fan . ".`artist_id` LIKE ?
              AND " . TBL_user . ".`username` LIKE ?
              " . $ci->db->escape_str($where) . "
            GROUP BY " . TBL_fan . ".`artist_id`
            ORDER BY `count` DESC,
                    " . TBL_artist . ".`artist_name` ASC
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($artist_id, $username));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}

/**
  * Get fan count.
  *
  * @param array $opts.
  *          'artist_id'  => Artist ID
  *          'user_id'    => User ID
  *
  * @return string JSON.
  */
if (!function_exists('getFanCount')) {
  function getFanCount($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_id = !empty($opts['artist_id']) ? $opts['artist_id'] : '%';
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] . ' 00:00:00' : '1970-00-00  00:00:00';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT " . TBL_fan . ".`artist_id`
            FROM " . TBL_fan . "
            WHERE " . TBL_fan . ".`artist_id` LIKE ?
              AND " . TBL_fan . ".`user_id` LIKE ?
              AND " . TBL_fan . ".`created` BETWEEN ? AND ?
              " . $ci->db->escape_str($where) . "";

    $query = $ci->db->query($sql, array($artist_id, $user_id, $lower_limit, $upper_limit));
    return $query->num_rows();
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
      return header('HTTP/1.1 401 Unauthorized');
    }
    
    // Add fan data to DB
    $sql = "INSERT
              INTO " . TBL_fan . " (`user_id`, `artist_id`)
              VALUES (?, ?)";
    $query = $ci->db->query($sql, array($user_id, $artist_id));
    if ($ci->db->affected_rows() === 1) {
      header("HTTP/1.1 201 Created");
      return json_encode(array('success' => array('msg' => $ci->db->insert_id())));
    }
    return header('HTTP/1.1 400 Bad Request');
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
      return header('HTTP/1.1 401 Unauthorized');
    }

    $ci->db->query('SET AUTOCOMMIT = 0');
    // Add log data
    $sql = "INSERT
              INTO " . TBL_fan_log . " (`user_id`, `artist_id`, `added`)
              VALUES (?, ?,
               (SELECT " . TBL_fan . ".`created`
                FROM " . TBL_fan . "
                WHERE " . TBL_fan . ".`user_id` = ?
                  AND " . TBL_fan . ".`artist_id` = ?))";
    $query = $ci->db->query($sql, array($user_id, $artist_id, $user_id, $artist_id));
    if ($ci->db->affected_rows() === 1) {
      // Delete fan data from DB
      $query = $ci->db->delete(TBL_fan, array('user_id' => $user_id,
                                              'artist_id' => $artist_id));
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