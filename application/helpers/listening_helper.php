<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Returns recently listened albums for the given user.
  *
  * @param array $opts.
  *          'username'        => Username
  *          'artist_name'     => Artist name
  *          'album_name'      => Album name
  *          'date'            => Listening date in yyyy/mm/dd format
  *          'limit'           => Limit
  *          'human_readable'  => Output format
  *
  * @return string JSON.
  */
if (!function_exists('getListenings')) {
  function getListenings($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $album_name = !empty($opts['album_name']) ? $opts['album_name'] : '%';
    $date = !empty($opts['date']) ? $opts['date'] : '%';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT " . TBL_listening . ". `id` as `listening_id`,
                   " . TBL_artist . ". `artist_name`,
                   " . TBL_album . ". `album_name`,
                   " . TBL_album . ". `year`, 
                   " . TBL_user . ". `username`,
                   " . TBL_listening . ". `date`, 
                   " . TBL_listening . ". `created`,
                   " . TBL_artist . ". `id` as `artist_id`,
                   " . TBL_album . ". `id` as `album_id`,
                   " . TBL_user . ". `id` as `user_id`
            FROM " . TBL_album . ", " . TBL_artist . ", " . TBL_listening . ", " . TBL_user . "
            WHERE " . TBL_album . ". `id` = " . TBL_listening . ". `album_id`
              AND " . TBL_user . ". `id` = " . TBL_listening . ". `user_id`
              AND " . TBL_artist . ". `id` = " . TBL_album . ". `artist_id`
              AND " . TBL_user . ". `username` LIKE " . $ci->db->escape($username) . "
              AND " . TBL_artist . ". `artist_name` LIKE " . $ci->db->escape($artist_name) . "
              AND " . TBL_album . ". `album_name` LIKE " . $ci->db->escape($album_name) . "
              AND " . TBL_listening . ". `date` LIKE " . $ci->db->escape($date) . "
            ORDER BY " . TBL_listening . ". `date` DESC, 
                     " . TBL_listening . ". `id` DESC
            LIMIT " . mysql_real_escape_string($limit);
    $query = $ci->db->query($sql);
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
    if (strpos($opts['text'], DASH)) {
      $ci=& get_instance();
      $ci->load->database();

      $data = array();
      
      // Get user id from session.
      if (!$data['user_id'] = $ci->session->userdata('user_id')) {
        header('HTTP/1.1 401 Unauthorized');
        return json_encode(array('error' => array('msg' => $data)));
      }
      list($data['artist'], $data['album']) = explode(DASH, $opts['text']);
      $data['artist'] = trim($data['artist']);
      $data['album'] = trim($data['album']);
      // Check that album exists.
      if (!$data['album_id'] = getAlbumID($data)) {
        header('HTTP/1.1 404 Not Found');
        return json_encode(array('error' => array('msg' => $data)));
      }
      $data['date'] = trim($opts['date']);

      // Add listening data to DB.
      $sql = "INSERT
                INTO " . TBL_listening . " (`user_id`, `album_id`, `date`)
                VALUES ({$data['user_id']}, {$data['album_id']}, '{$data['date']}')";
      $query = $ci->db->query($sql);
      if ($ci->db->affected_rows() == 1) {
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
              WHERE " . TBL_listening . ".`id` = {$data['listening_id']}
                AND " . TBL_listening . ".`user_id` = {$data['user_id']}";
    $query = $ci->db->query($sql);
    echo($ci->db->affected_rows());
    if ($ci->db->affected_rows() == 1) {
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
              VALUES (" . $ci->db->escape($listening_id) . ", " . $ci->db->escape($format_id) . ", " . $ci->db->escape($user_id) . ")";
    $query = $ci->db->query($sql);
    if ($ci->db->affected_rows() == 1) {
      return $ci->db->insert_id();
    }
    else {
      return FALSE;
    }
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
              VALUES (" . $ci->db->escape($listening_id) . ", " . $ci->db->escape($format_type_id) . ", " . $ci->db->escape($user_id) . ")";
    $query = $ci->db->query($sql);
    if ($ci->db->affected_rows() == 1) {
      return $ci->db->insert_id();
    }
    else {
      return FALSE;
    }
  }
}
?>