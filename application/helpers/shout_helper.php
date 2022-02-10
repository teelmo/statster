<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Get shout count.
  *
  * @param array $opts.
  *          'user_id'    => User ID
  *
  * @return string JSON.
  */
if (!function_exists('getShoutCount')) {
  function getShoutCount($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] . ' 00:00:00' : '1970-00-00  00:00:00';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $where = !empty($opts['where']) ? '' . $opts['where'] : '1';
    $sql = "SELECT * 
            FROM (SELECT " . TBL_artist_shout . ".`id`,
                         " . TBL_artist_shout . ".`created` as `created`,
                         " . TBL_artist_shout . ".`artist_id` as `target_id`,
                         'artist' as `target_type`
                  FROM " . TBL_artist_shout . "
                  WHERE " . TBL_artist_shout . ".`user_id` LIKE ?
                    AND " . TBL_artist_shout . ".`created` BETWEEN ? AND ?
                  UNION
                  SELECT " . TBL_album_shout . ".`id`,
                         " . TBL_album_shout . ".`created` as `created`,
                         " . TBL_album_shout . ".`album_id` as `target_id`,
                         'album' as `target_type`
                  FROM " . TBL_album_shout . "
                  WHERE " . TBL_album_shout . ".`user_id` LIKE ?
                    AND " . TBL_album_shout . ".`created` BETWEEN ? AND ?
                  UNION
                  SELECT " . TBL_user_shout . ".`id`,
                         " . TBL_user_shout . ".`created` as `created`,
                         " . TBL_user_shout . ".`user_id` as `target_id`,
                         'user' as `target_type`
                  FROM " . TBL_user_shout . "
                  WHERE " . TBL_user_shout . ".`adder_id` LIKE ?
                    AND " . TBL_user_shout . ".`created` BETWEEN ? AND ?) AS `shouts`
            WHERE " . $ci->db->escape_str($where) . "";
    $query = $ci->db->query($sql, array($user_id, $lower_limit, $upper_limit, $user_id, $lower_limit, $upper_limit, $user_id, $lower_limit, $upper_limit));
    return $query->num_rows();
  }
}

/**
  * Get shout counts per user.
  *
  * @param array $opts.
  *          'human_readable'  => Output format
  *
  * @return string JSON.
  */
if (!function_exists('getShoutCountUser')) {
  function getShoutCountUser($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $sql = "SELECT SUM(`count`) AS `count`,
                  `user_id`,
                  `username`
            FROM (SELECT count(" . TBL_artist_shout . ".`id`) as `count`,
                         " . TBL_artist_shout . ".`user_id` as `user_id`,
                         " . TBL_user . ".`username` as `username`
                  FROM " . TBL_artist_shout . ", " . TBL_user . "
                  WHERE " . TBL_artist_shout . ".`user_id` = " . TBL_user . ".`id`
                  GROUP BY `user_id`
                  UNION
                  SELECT count(" . TBL_album_shout . ".id) as `count`,
                        " . TBL_album_shout . ".`user_id` as `user_id`,
                        " . TBL_user . ".`username` as `username`
                  FROM " . TBL_album_shout . ", " . TBL_user . "
                  WHERE " . TBL_album_shout . ".`user_id` = " . TBL_user . ".`id`
                  GROUP BY `user_id`
                  UNION
                  SELECT count(" . TBL_user_shout . ".id) as `count`,
                        " . TBL_user_shout . ".`adder_id` as `user_id`,
                        " . TBL_user . ".`username` as `username`
                  FROM " . TBL_user_shout . ", " . TBL_user . "
                  WHERE " . TBL_user_shout . ".`adder_id` = " . TBL_user . ".`id`
                  GROUP BY `adder_id`) `t`
            WHERE 1
            GROUP BY `user_id`,
                     `username`
            ORDER BY `count` DESC";
    $query = $ci->db->query($sql);

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Get album shouts.
  *
  * @param array $opts.
  *          'album_name'   => Album name
  *          'artist_name'  => Artist name
  *          'username'     => Username
  *
  * @return array Shout information.
  */
if (!function_exists('getAlbumShout')) {
  function getAlbumShout($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    // Load helpers.
    $ci->load->helper(array('id_helper'));

    $album_id = isset($opts['album_name']) ? getAlbumID($opts) : '%';
    $artist_id = (isset($opts['artist_name']) && !isset($opts['album_name'])) ? getArtistID($opts) : '%';
    $sub_group_by = ($album_id !== '%') ? "GROUP BY " . TBL_artists . ".`album_id`" : (($artist_id !== '%') ? '' : "GROUP BY " . TBL_artists . ".`artist_id`");
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] . ' 00:00:00' : '1970-00-00  00:00:00';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT " . TBL_album_shout . ".`id` as `shout_id`,
                   " . TBL_album_shout . ".`album_id`,
                   " . TBL_album_shout . ".`created`,
                   " . TBL_album_shout . ".`text`,
                   " . TBL_album . ".`album_name`,
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_user . ".`id` as `user_id`,
                   " . TBL_user . ".`username`,
                  (SELECT count(" . TBL_album_shout . ".`album_id`)
                   FROM " . TBL_album_shout . "
                   WHERE " . TBL_album_shout . ".`album_id` LIKE ?
                   ) AS `count`,
                   'album' as `type`
            FROM " . TBL_album_shout . ",
                 " . TBL_album . ",
                 " . TBL_artist . ",
                 (SELECT " . TBL_artists . ".`artist_id`,
                         " . TBL_artists . ".`album_id`
                  FROM " . TBL_artists . "
                  " . $sub_group_by . ") AS " . TBL_artists . ",
                 " . TBL_user . "
            WHERE " . TBL_album_shout . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_album_shout . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_artists . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_artist . ".`id` LIKE ?
              AND " . TBL_album . ".`id` LIKE ?
              AND " . TBL_user . ".`username` LIKE ?
              AND " . TBL_album_shout . ".`created` BETWEEN ? AND ?
            ORDER BY " . TBL_album_shout . ".`created` DESC
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($album_id, $artist_id, $album_id, $username, $lower_limit, $upper_limit));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Get artist shouts.
  *
  * @param array $opts.
  *          'artist_name'  => Artist name
  *          'username'     => Username
  *
  * @return array Shout information.
  */
if (!function_exists('getArtistShout')) {
  function getArtistShout($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : '%';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] . ' 00:00:00' : '1970-00-00  00:00:00';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT " . TBL_artist_shout . ".`id` as `shout_id`,
                   " . TBL_artist_shout . ".`artist_id`,
                   " . TBL_artist_shout . ".`created`,
                   " . TBL_artist_shout . ".`text`,
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_user . ".`id` as `user_id`,
                   " . TBL_user . ".`username`,
                   (SELECT count(" . TBL_artist_shout . ".`artist_id`)
                     FROM " . TBL_artist_shout . "
                     WHERE " . TBL_artist_shout . ".`artist_id` = " . TBL_artist . ".`id`
                       AND " . TBL_artist . ".`artist_name` LIKE ?
                   ) AS `count`,
                   'artist' as `type`
            FROM " . TBL_artist_shout . ",
                 " . TBL_artist . ",
                 " . TBL_user . "
            WHERE " . TBL_artist_shout . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_artist_shout . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_artist . ".`artist_name` LIKE ?
              AND " . TBL_user . ".`username` LIKE ?
              AND " . TBL_artist_shout . ".`created` BETWEEN ? AND ?
            ORDER BY " . TBL_artist_shout . ".`created` DESC
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($artist_name, $artist_name, $username, $lower_limit, $upper_limit));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Get user shouts.
  *
  * @param array $opts.
  *          'username'  => Username
  *
  * @return array Shout information.
  */
if (!function_exists('getUserShout')) {
  function getUserShout($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] . ' 00:00:00' : '1970-00-00  00:00:00';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
    $username = !empty($opts['username']) ? $opts['username'] : '%';

    if (isset($opts['type']) && $opts['type'] === 'user') {
      $sql = "SELECT " . TBL_user_shout . ".`id` as `shout_id`,
                     " . TBL_user_shout . ".`text`,
                     " . TBL_user_shout . ".`created`,
                     " . TBL_user_shout . ".`user_id` as `profile_id`,
                     " . TBL_user_shout . ".`adder_id` as `user_id`,
                     " . TBL_user . ".`username` as `profile`,
                     (SELECT count(" . TBL_user_shout . ".`user_id`)
                       FROM " . TBL_user_shout . "
                       WHERE " . TBL_user_shout . ".`user_id` = " . TBL_user . ".`id`
                         AND " . TBL_user . ".`username` LIKE ?
                     ) AS `count`,
                     (SELECT " . TBL_user . ".`username`
                       FROM " . TBL_user . "
                       WHERE " . TBL_user_shout . ".`adder_id` = " . TBL_user . ".`id`
                     ) AS `username`,
                     'user' as `type`
              FROM " . TBL_user_shout . ",
                   " . TBL_user . "
              WHERE " . TBL_user_shout . ".`adder_id` = " . TBL_user . ".`id`
                AND " . TBL_user . ".`username` LIKE ?
                AND " . TBL_user_shout . ".`created` BETWEEN ? AND ?
              ORDER BY " . TBL_user_shout . ".`created` DESC
              LIMIT " . $ci->db->escape_str($limit);
    }
    else {
      $sql = "SELECT " . TBL_user_shout . ".`id` as `shout_id`,
                     " . TBL_user_shout . ".`text`,
                     " . TBL_user_shout . ".`created`,
                     " . TBL_user_shout . ".`user_id` as `profile_id`,
                     " . TBL_user_shout . ".`adder_id` as `user_id`,
                     " . TBL_user . ".`username` as `profile`,
                     (SELECT count(" . TBL_user_shout . ".`user_id`)
                       FROM " . TBL_user_shout . "
                       WHERE " . TBL_user_shout . ".`user_id` = " . TBL_user . ".`id`
                         AND " . TBL_user . ".`username` LIKE ?
                     ) AS `count`,
                     (SELECT " . TBL_user . ".`username`
                       FROM " . TBL_user . "
                       WHERE " . TBL_user_shout . ".`adder_id` = " . TBL_user . ".`id`
                     ) AS `username`,
                     'user' as `type`
              FROM " . TBL_user_shout . ",
                   " . TBL_user . "
              WHERE " . TBL_user_shout . ".`user_id` = " . TBL_user . ".`id`
                AND " . TBL_user . ".`username` LIKE ?
                AND " . TBL_user_shout . ".`created` BETWEEN ? AND ?
              ORDER BY " . TBL_user_shout . ".`created` DESC
              LIMIT " . $ci->db->escape_str($limit);
    }
    $query = $ci->db->query($sql, array($username, $username, $lower_limit, $upper_limit));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Get album shout count.
  *
  * @param array $opts.
  *          'album_id'   => Album ID
  *          'user_id'    => User ID
  *
  * @return string JSON.
  */
if (!function_exists('getAlbumShoutCount')) {
  function getAlbumShoutCount($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : '%';
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] . ' 00:00:00' : '1970-00-00  00:00:00';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT " . TBL_album_shout . ".`album_id`
            FROM " . TBL_album_shout . "
            WHERE " . TBL_album_shout . ".`album_id` LIKE ?
              AND " . TBL_album_shout . ".`user_id` LIKE ?
              AND " . TBL_album_shout . ".`created` BETWEEN ? AND ?
              " . $ci->db->escape_str($where) . "";

    $query = $ci->db->query($sql, array($album_id, $user_id, $lower_limit, $upper_limit));
    return $query->num_rows();
  }
}

/**
  * Get artist shout count.
  *
  * @param array $opts.
  *          'artist_id'   => Artist ID
  *          'user_id'    => User ID
  *
  * @return string JSON.
  */
if (!function_exists('getArtistShoutCount')) {
  function getArtistShoutCount($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_id = !empty($opts['artist_id']) ? $opts['artist_id'] : '%';
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] . ' 00:00:00' : '1970-00-00  00:00:00';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT " . TBL_artist_shout . ".`artist_id`
            FROM " . TBL_artist_shout . "
            WHERE " . TBL_artist_shout . ".`artist_id` LIKE ?
              AND " . TBL_artist_shout . ".`user_id` LIKE ?
              AND " . TBL_artist_shout . ".`created` BETWEEN ? AND ?
              " . $ci->db->escape_str($where) . "";

    $query = $ci->db->query($sql, array($artist_id, $user_id, $lower_limit, $upper_limit));
    return $query->num_rows();
  }
}

/**
  * Get user shout count.
  *
  * @param array $opts.
  *          'user_id'    => User ID
  *
  * @return string JSON.
  */
if (!function_exists('getUserShoutCount')) {
  function getUserShoutCount($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] . ' 00:00:00' : '1970-00-00  00:00:00';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT " . TBL_user_shout . ".`adder_id`
            FROM " . TBL_user_shout . "
            WHERE " . TBL_user_shout . ".`adder_id` LIKE ?
              AND " . TBL_user_shout . ".`created` BETWEEN ? AND ?
              " . $ci->db->escape_str($where) . "";

    $query = $ci->db->query($sql, array($user_id, $lower_limit, $upper_limit));
    return $query->num_rows();
  }
}

/**
  * Add shout data.
  *
  * @param array $opts.
  *
  * @return string JSON.
  */
if (!function_exists('addShout')) {
  function addShout($opts = array()) {
    if (empty($opts)) {
      header('HTTP/1.1 400 Bad Request');
      return json_encode(array('error' => array('msg' => ERR_BAD_REQUEST)));
    }
    else {
      $ci=& get_instance();
      $ci->load->database();

      $data = array();

      // Get user id from session.
      if (!$data['user_id'] = $ci->session->userdata('user_id')) {
        header('HTTP/1.1 401 Unauthorized');
        return json_encode(array('error' => array('msg' => $data)));
      }
      $data['text'] = trim($opts['text']);
      if (empty($data['text'])) {
        header('HTTP/1.1 400 Bad Request');
        return json_encode(array('error' => array('msg' => ERR_GENERAL)));
      }
      // Add shout data to DB.
      switch ($opts['type']) {
        case 'album':
          $data['album_id'] = $opts['content_id'];
          $sql = "INSERT
                    INTO " . TBL_album_shout . " (`album_id`, `user_id`, `text`, `created`, `ip_address`)
                    VALUES (?, ?, ?, CURRENT_TIMESTAMP(), ?)";
          $query = $ci->db->query($sql, array($data['album_id'], $data['user_id'], $data['text'], $_SERVER['REMOTE_ADDR']));
          break;
        case 'artist':
          $data['artist_id'] = $opts['content_id'];
          $sql = "INSERT
                    INTO " . TBL_artist_shout . " (`artist_id`, `user_id`, `text`, `created`, `ip_address`)
                    VALUES (?, ?, ?, CURRENT_TIMESTAMP(), ?)";
          $query = $ci->db->query($sql, array($data['artist_id'], $data['user_id'], $data['text'], $_SERVER['REMOTE_ADDR']));
          break;
        case 'user':
          $data['profile_id'] = $opts['content_id'];
          $sql = "INSERT
                    INTO " . TBL_user_shout . " (`user_id`, `adder_id`, `text`, `created`, `ip_address`)
                    VALUES (?, ?, ?, CURRENT_TIMESTAMP(), ?)";
          $query = $ci->db->query($sql, array($data['profile_id'], $data['user_id'], $data['text'], $_SERVER['REMOTE_ADDR']));
          break;
        default:
          header('HTTP/1.1 400 Bad Request');
          return json_encode(array('error' => array('msg' => ERR_BAD_REQUEST)));
          break;
      }
      if ($ci->db->affected_rows() === 1) {
        $data['shout_id'] = $ci->db->insert_id();
        header('HTTP/1.1 201 Created');
        return json_encode(array('success' => array('msg' => $data)));
      }
      else {
        header('HTTP/1.1 400 Bad Request');
        return json_encode(array('error' => array('msg' => ERR_GENERAL)));
      }
    }
  }
}

/**
  * Delete shout data.
  *
  * @param array $opts.
  *
  * @return string JSON.
  */
if (!function_exists('deleteShout')) {
  function deleteShout($opts = array()) {
    $data = array();
    if (!$data['shout_id'] = $opts['shout_id']) {
      header('HTTP/1.1 400 Bad Request');
      return json_encode(array('error' => array('msg' => ERR_BAD_REQUEST)));
    }
    $ci=& get_instance();
    $ci->load->database();
    
    // Get user id from session.
    if (!$data['user_id'] = $ci->session->userdata('user_id')) {
      header('HTTP/1.1 401 Unauthorized');
      return json_encode(array('error' => array('msg' => $data)));
    }
    // Delete shout data from DB.
    switch ($opts['type']) {
      case 'album':
        $sql = "DELETE 
                  FROM " . TBL_album_shout . "
                  WHERE " . TBL_album_shout . ".`id` = ?
                    AND " . TBL_album_shout . ".`user_id` = ?";
        $query = $ci->db->query($sql, array($data['shout_id'], $data['user_id']));
        break;
      case 'artist':
        $sql = "DELETE 
                  FROM " . TBL_artist_shout . "
                  WHERE " . TBL_artist_shout . ".`id` = ?
                    AND " . TBL_artist_shout . ".`user_id` = ?";
        $query = $ci->db->query($sql, array($data['shout_id'], $data['user_id']));
        break;
      case 'user':
        $sql = "DELETE 
                  FROM " . TBL_user_shout . "
                  WHERE " . TBL_user_shout . ".`id` = ?
                    AND " . TBL_user_shout . ".`adder_id` = ?";
        $query = $ci->db->query($sql, array($data['shout_id'], $data['user_id']));
        break;
      default:
        header('HTTP/1.1 400 Bad Request');
        return json_encode(array('error' => array('msg' => ERR_BAD_REQUEST)));
        break;
    }
    if ($ci->db->affected_rows() === 1) {
      header('HTTP/1.1 200 OK');
      return json_encode(array());
    }
    else {
      header('HTTP/1.1 401 Unauthorized');
      return json_encode(array('error' => array('msg' => $data, 'affected' => $ci->db->affected_rows())));
    }
  }
}
?>