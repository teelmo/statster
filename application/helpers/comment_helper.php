<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Get album comments.
  *
  * @param array $opts.
  *          'album_name'  => Album name
  *          'username'    => Username
  *
  * @return array Comment information.
  */
if (!function_exists('getAlbumComment')) {
  function getAlbumComment($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $album_name = !empty($opts['album_name']) ? $opts['album_name'] : '%';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT " . TBL_album_comment . ".`id` as `comment_id`,
                   " . TBL_album_comment . ".`album_id`,
                   " . TBL_album_comment . ".`created`,
                   " . TBL_album_comment . ".`text`,
                   " . TBL_user . ".`id` as `user_id`,
                   " . TBL_user . ".`username`,
                  (SELECT count(" . TBL_album_comment . ".`album_id`)
                   FROM " . TBL_album_comment . "
                   WHERE " . TBL_album_comment . ".`album_id` = " . TBL_album . ".`id`
                     AND " . TBL_album . ".`album_name` LIKE ?
                   ) AS `count`,
                   'album' as `type`
            FROM " . TBL_album_comment . ",
                 " . TBL_album . ",
                 " . TBL_user . "
            WHERE " . TBL_album_comment . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_album_comment . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`album_name` LIKE ?
              AND " . TBL_user . ".`username` LIKE ?
            ORDER BY " . TBL_album_comment . ".`created` DESC
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($album_name, $album_name, $username));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Get artist comments.
  *
  * @param array $opts.
  *          'artist_name'  => Artist name
  *          'username'     => Username
  *
  * @return array Comment information.
  */
if (!function_exists('getArtistComment')) {
  function getArtistComment($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT " . TBL_artist_comment . ".`id` as `comment_id`,
                   " . TBL_artist_comment . ".`artist_id`,
                   " . TBL_artist_comment . ".`created`,
                   " . TBL_artist_comment . ".`text`,
                   " . TBL_user . ".`id` as `user_id`,
                   " . TBL_user . ".`username`,
                   (SELECT count(" . TBL_artist_comment . ".`artist_id`)
                     FROM " . TBL_artist_comment . "
                     WHERE " . TBL_artist_comment . ".`artist_id` = " . TBL_artist . ".`id`
                       AND " . TBL_artist . ".`artist_name` LIKE ?
                   ) AS `count`,
                   'artist' as `type`
            FROM " . TBL_artist_comment . ",
                 " . TBL_artist . ",
                 " . TBL_user . "
            WHERE " . TBL_artist_comment . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_artist_comment . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_artist . ".`artist_name` LIKE ?
              AND " . TBL_user . ".`username` LIKE ?
            ORDER BY " . TBL_artist_comment . ".`created` DESC
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($artist_name, $artist_name, $username));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Get user comments.
  *
  * @param array $opts.
  *          'username'  => Username
  *
  * @return array Comment information.
  */
if (!function_exists('getUserComment')) {
  function getUserComment($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $album_name = !empty($opts['album_name']) ? $opts['album_name'] : '%';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT " . TBL_user_comment . ".`id` as `comment_id`,
                   " . TBL_user_comment . ".`text`,
                   " . TBL_user_comment . ".`created`,
                   " . TBL_user_comment . ".`adder_id` as `user_id`,
                   (SELECT count(" . TBL_user_comment . ".`user_id`)
                     FROM " . TBL_user_comment . "
                     WHERE " . TBL_user_comment . ".`user_id` = " . TBL_user . ".`id`
                       AND " . TBL_user . ".`username` LIKE ?
                   ) AS `count`,
                   (SELECT " . TBL_user . ".`username`
                     FROM " . TBL_user . "
                     WHERE " . TBL_user_comment . ".`adder_id` = " . TBL_user . ".`id`
                   ) AS `username`,
                   'user' as `type`
            FROM " . TBL_user_comment . ",
                 " . TBL_user . "
            WHERE " . TBL_user_comment . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_user . ".`username` LIKE ?
            ORDER BY " . TBL_user_comment . ".`created` DESC
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($username, $username));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Add comment data.
  *
  * @param array $opts.
  *
  * @return string JSON.
  */
if (!function_exists('addComment')) {
  function addComment($opts = array()) {
    if (empty($opts)) {
      header('HTTP/1.1 400 Bad Request');
      return json_encode(array('error' => array('msg' => ERR_BAD_REQUEST)));
    }
    else if (strpos($opts['text'], DASH)) {
      $ci=& get_instance();
      $ci->load->database();

      $data = array();

      // Get user id from session.
      if (!$data['user_id'] = $ci->session->userdata('user_id')) {
        header('HTTP/1.1 401 Unauthorized');
        return json_encode(array('error' => array('msg' => $data)));
      }

      $data['date'] = trim($opts['date']);

      // Add listening data to DB.
      $sql = "INSERT
                INTO " . TBL_listening . " (`user_id`, `album_id`, `date`)
                VALUES (?, ?, ?)";
      $query = $ci->db->query($sql, array($data['user_id'], $data['album_id'], $data['date']));
      if ($ci->db->affected_rows() === 1) {
        $data['listening_id'] = $ci->db->insert_id();
        // Add listening format data to DB.
        if (!empty($_POST['format'])) {
          list($data['format'], $data['format_type']) = explode(':', $_POST['format']);
          addListeningFormat($data);
        }
        header('HTTP/1.1 201 Created');
        return json_encode(array('success' => array('msg' => $data)));
      }
      else {
        header('HTTP/1.1 400 Bad Request');
        return json_encode(array('error' => array('msg' => ERR_GENERAL)));
      }
    }
    else {
      header('HTTP/1.1 404 Not Found');
      return json_encode(array('error' => array('msg' => 'Format error.')));
    }
  }
}

/**
  * Delete comment data.
  *
  * @param array $opts.
  *
  * @return string JSON.
  */
if (!function_exists('deleteComment')) {
  function deleteComment($opts = array()) {
    $data = array();
    if (!$data['comment_id'] = $opts['comment_id']) {
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
    // Delete comment data from DB.
    switch ($opts['type']) {
      case 'album':
        $sql = "DELETE 
                  FROM " . TBL_album_comment . "
                  WHERE " . TBL_album_comment . ".`id` = ?
                    AND " . TBL_album_comment . ".`user_id` = ?";
        $query = $ci->db->query($sql, array($data['comment_id'], $data['user_id']));
        break;
      case 'artist':
        $sql = "DELETE 
                  FROM " . TBL_artist_comment . "
                  WHERE " . TBL_artist_comment . ".`id` = ?
                    AND " . TBL_artist_comment . ".`user_id` = ?";
        $query = $ci->db->query($sql, array($data['comment_id'], $data['user_id']));
        break;
      case 'user':
        $sql = "DELETE 
                  FROM " . TBL_user_comment . "
                  WHERE " . TBL_user_comment . ".`id` = ?
                    AND " . TBL_user_comment . ".`adder_id` = ?";
        $query = $ci->db->query($sql, array($data['comment_id'], $data['user_id']));
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