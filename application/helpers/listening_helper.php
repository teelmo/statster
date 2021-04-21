<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Returns recently listened albums for the given user.
  *
  * @param array $opts.
  *          'album_name'      => Album name
  *          'artist_name'     => Artist name
  *          'date'            => Listening date in yyyy-mm-dd format
  *          'human_readable'  => Output format
  *          'limit'           => Limit
  *          'username'        => Username
  *
  * @return string JSON.
  */
if (!function_exists('getListenings')) {
  function getListenings($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $album_name = isset($opts['album_name']) ? $opts['album_name'] : '%';
    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : '%';
    $date = !empty($opts['date']) ? $opts['date'] : '%';
    $from = !empty($opts['from']) ? ', ' . $opts['from'] : '';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT " . TBL_listening . ".`id` as `listening_id`,
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_album . ".`album_name`,
                   " . TBL_album . ".`year`,
                   " . TBL_album . ".`spotify_id`,
                   " . TBL_user . ".`username`,
                   " . TBL_listening . ".`date`,
                   " . TBL_listening . ".`created`,
                   " . TBL_artist . ".`id` as `artist_id`,
                   " . TBL_album . ".`id` as `album_id`,
                   " . TBL_user . ".`id` as `user_id`
            FROM " . TBL_album . ",
                 " . TBL_artist . ",
                 " . TBL_listening . ",
                 " . TBL_user . "
                 " . $ci->db->escape_str($from) . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_user . ".`id` = " . TBL_listening . ".`user_id`
              AND " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id`
              AND " . TBL_user . ".`username` LIKE ?
              AND " . TBL_artist . ".`artist_name` LIKE ?
              AND " . TBL_album . ".`album_name` LIKE ?
              AND " . TBL_listening . ".`date` LIKE ?
              " . $ci->db->escape_str($where) . "
            ORDER BY " . TBL_listening . ".`date` DESC,
                     " . TBL_listening . ".`id` DESC
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($username, $artist_name, $album_name, $date));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Add listening data.
  *
  * @param array $opts.
  *
  * @return string JSON.
  */
if (!function_exists('addListening')) {
  function addListening($opts = array()) {
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
      list($data['artist_name'], $data['album_name']) = explode(DASH, $opts['text'], 2);
      $data['artist_name'] = trim($data['artist_name']);
      $data['album_name'] = trim($data['album_name']);
      // Check that album exists.
      if (!$data['album_id'] = getAlbumID($data)) {
        // Try to add if it doesn't.
        if (!$data['album_id'] = addAlbum($data)) {
          header('HTTP/1.1 404 Not Found');
          return json_encode(array('error' => array('msg' => 'Format error.')));
        }
      }
      else {
        // Get Spotify information only if existing album.
        if (empty($data['spotify_id'])) {
          $data['spotify_id'] = getSpotifyResourceId($data);
        }
      }

      $data['date'] = trim($opts['date']);
      $data['created'] = trim($opts['created']);

      // Add listening data to DB.
      $sql = "INSERT
                INTO " . TBL_listening . " (`user_id`, `album_id`, `date`, `created`)
                VALUES (?, ?, ?, ?)";
      $query = $ci->db->query($sql, array($data['user_id'], $data['album_id'], $data['date'], $data['created']));
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
  * Delete listening data.
  *
  * @param array $opts.
  *
  * @return string JSON.
  */
if (!function_exists('deleteListening')) {
  function deleteListening($opts = array()) {
    $data = array();
    if (!$data['listening_id'] = $opts['listening_id']) {
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
    // Delete listening data from DB.
    $sql = "DELETE 
              FROM " . TBL_listening . "
              WHERE " . TBL_listening . ".`id` = ?
                AND " . TBL_listening . ".`user_id` = ?";
    $query = $ci->db->query($sql, array($data['listening_id'], $data['user_id']));

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

/**
  * Get listening formats.
  *
  * @param array $opts.
  *          'human_readable'  => Output format
  *
  * @return string JSON.
  */
if (!function_exists('getListeningFormats')) {
  function getListeningFormats($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $sql = "SELECT " . TBL_listening_format . ".`id` as `format_id`,
                   " . TBL_listening_format . ".`name` as `format_name`,
                   " . TBL_listening_format . ".`img` as `format_img`
            FROM " . TBL_listening_format . "
            WHERE 1
            ORDER BY " . TBL_listening_format . ".`id` ASC";
    $query = $ci->db->query($sql, array());

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Get listening format types.
  *
  * @param array $opts.
  *          'format_id'       => format ID
  *          'human_readable'  => Output format
  */
if (!function_exists('getListeningFormatTypes')) {
  function getListeningFormatTypes($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $format_id = !empty($opts['format_id']) ? $opts['format_id'] : '%';
    $sql = "SELECT " . TBL_listening_format_type . ".`id` as `format_type_id`,
                   " . TBL_listening_format . ".`name` as `format_name`,
                   " . TBL_listening_format_type . ".`name` as `format_type_name`,
                   " . TBL_listening_format_type . ".`img` as `format_type_img`
            FROM " . TBL_listening_format . ",
                 " . TBL_listening_format_type . "
            WHERE " . TBL_listening_format . ".`id` = " . TBL_listening_format_type . ".`listening_format_id`
              AND " . TBL_listening_format_type . ".`listening_format_id` = ?
            ORDER BY " . TBL_listening_format_type . ".`name` ASC";
    $query = $ci->db->query($sql, array($format_id));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Collection function for adding format and
  * format type information to a listening.
  *
  * @param array $opts.
  *          'format'      => Format name
  *          'format_type' => Format type name
  *
  * @return boolean TRUE.
  */
if (!function_exists('addListeningFormat')) {
  function addListeningFormat($data = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $data['format_id'] = getFormatID($data);
    $data['format_type_id'] = getFormatTypeID($data);
    (!empty($data['format_id'])) ? addListeningFormats($data) : FALSE;
    (!empty($data['format_type_id'])) ?  addListeningFormatTypes($data) : FALSE;

    return TRUE;
  }
}

/**
 * Attaches the given format to the given listening.
 *
 * @param array $opts.
 *          'format_id'     => Format ID
 *          'listening_id'  => Listening ID
 *          'user_id'       => User ID
 *
 * @return int Insert ID or boolean FALSE.
 */
if (!function_exists('addListeningFormats')) {
  function addListeningFormats($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $format_id = !empty($opts['format_id']) ? $opts['format_id'] : '';
    $listening_id = $opts['listening_id'];
    $user_id = $opts['user_id'];

    $sql = "INSERT
              INTO " . TBL_listening_formats . " (`listening_id`, `listening_format_id`, `user_id`)
              VALUES (?, ?, ?)";
    $query = $ci->db->query($sql, array($listening_id, $format_id, $user_id));
    return ($ci->db->affected_rows() === 1) ? $ci->db->insert_id() : FALSE;
  }
}

/**
  * Attaches the given format type to the given listening.
  *
  * @param array $opts.
  *          'format_type_id'  => Format type ID
  *          'listening_id'    => Listening ID
  *          'user_id'         => User ID
  *
  * @return int Insert ID or boolean FALSE.
  */
if (!function_exists('addListeningFormatTypes')) {
  function addListeningFormatTypes($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $format_type_id = !empty($opts['format_type_id']) ? $opts['format_type_id'] : '';
    $listening_id = $opts['listening_id'];
    $user_id = $opts['user_id'];
    $sql = "INSERT
              INTO " . TBL_listening_format_types . " (`listening_id`, `listening_format_type_id`, `user_id`)
              VALUES (?, ?, ?)";
    $query = $ci->db->query($sql, array($listening_id, $format_type_id, $user_id));
    return ($ci->db->affected_rows() === 1) ? $ci->db->insert_id() : FALSE;
  }
}
?>