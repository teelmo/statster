<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * Tells if the given album is loved by the given user.
 *
 * @param array $opts.
 *          'user_id'   => User ID
 *          'album_id'  => Artist ID
 *
 * @return string JSON encoded data containing album information.
 */
if (!function_exists('getAlbumLove')) {
  function getAlbumLove($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    // Load helpers
    $ci->load->helper(array('return_helper'));

    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : '';
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
   * Tells if the given artist is faned by the given user.
   *
   * @param array $opts.
   *          'user_id'   => User ID
   *          'artist_id' => Artist ID
   *
   * @return int or boolean FALSE.
   */
if (!function_exists('getArtistFan')) {
  function getArtistFan($opts = array()) {
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
    $ci->load->helper(array('id_helper'));

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
    if($ci->db->affected_rows() == 1) {
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
    if($ci->db->affected_rows() == 1) {
      return $ci->db->insert_id();
    }
    else {
      return FALSE;
    }
  }
}

/**
   * Returns listened artists for the given user.
   *
   * @param array $opts.
   *          'username'        => Username
   *          'order_by'        => Order by argument
   *          'limit'           => Limit
   *          'human_readable'  => Output format
   *
   * @return string JSON encoded data containing artist information.
   */
if (!function_exists('getListenedArtists')) {
  function getListenedArtists($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : TBL_artist . '`artist_name` ASC';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT DISTINCT " . TBL_artist . ".`id`, 
                            " . TBL_artist . ".`artist_name`
          FROM " . TBL_album . ", 
               " . TBL_artist . ", 
               " . TBL_listening . ", 
               " . TBL_user . "
          WHERE " . TBL_listening . ".`user_id` = " . TBL_user . ".`id` 
            AND " . TBL_listening . ".`album_id` = " . TBL_album . ".`id` 
            AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
            AND " . TBL_user . ".`username` LIKE " . $ci->db->escape($username) . "
          ORDER BY " . mysql_real_escape_string($order_by) . "
          LIMIT " . mysql_real_escape_string($limit);
    $query = $ci->db->query($sql);
    return _json_return_helper($query, $human_readable);
  }
}

/**
   * Returns listened albums for the given user.
   *
   * @param array $opts.
   *          'username'        => Username
   *          'order_by'        => Order by argument
   *          'limit'           => Limit
   *          'human_readable'  => Output
   *
   * @return string JSON encoded data containing album information.
   */
if (!function_exists('getListenedAlbums')) {
  function getListenedAlbums($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : TBL_album . '.`album_name` ASC';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT DISTINCT " . TBL_artist . ".`artist_name`, 
                   " . TBL_artist . ".`id` as artist_id, 
                   " . TBL_album . ".`album_name`, 
                   " . TBL_album . ".`id` as album_id, 
                   " . TBL_album . ".`year`
            FROM " . TBL_artist . ",
                 " . TBL_album . ",
                 " . TBL_listening . ", 
                 " . TBL_user . "
            WHERE " . TBL_listening . ".`user_id` = " . TBL_user. ".`id` 
              AND " . TBL_listening . ".`album_id` = " . TBL_album . ".`id` 
              AND " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id` 
              AND " . TBL_user . ".`username` LIKE " . $ci->db->escape($username) . "
            ORDER BY " . mysql_real_escape_string($order_by) . "
            LIMIT " . mysql_real_escape_string($limit);
    $query = $ci->db->query($sql);
    return _json_return_helper($query, $human_readable);
  }
}

/**
   * Returns top artists for the given user.
   *
   * @param array $opts.
   *          'lower_limit'     => Lower date limit in yyyy/mm/dd format
   *          'upper_limit'     => Upper date limit in yyyy/mm/dd format
   *          'username'        => Username
   *          'artist_name'          => Artist name
   *          'group_by'        => Group by argument
   *          'order_by'        => Order by argument
   *          'limit'           => Limit
   *          'human_readable'  => Output format
   *
   * @return string JSON encoded data containing artist information.
   */
if (!function_exists('getTopArtists')) {
  function getTopArtists($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date("Y-m-d", time() - (31 * 24 * 60 * 60));
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date("Y-m-d");
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] :  TBL_artist . '.`id`';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, `artist_name` ASC';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT count(" . TBL_artist . ".`id`) as `count`, 
                   " . TBL_artist . ".`artist_name`, 
                   " . TBL_artist . ".`id` as artist_id, 
                   " . TBL_user . ". `username`, 
                   " . TBL_user . ". `id` as user_id, 
              (SELECT count(" . TBL_fan . ".`artist_id`)
                FROM " . TBL_fan . "
                WHERE " . TBL_fan . ".`artist_id` = " . TBL_artist . ".`id` 
                  AND " . TBL_fan . ".`user_id` = " . TBL_user . ".`id`
              ) AS `love`
            FROM " . TBL_album . ", 
                 " . TBL_artist . ", 
                 " . TBL_listening . " , 
                 " . TBL_user . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`date` BETWEEN " . $ci->db->escape($lower_limit) . " 
                                                   AND " . $ci->db->escape($upper_limit) . "
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_user . ".`username` LIKE " . $ci->db->escape($username) . "
              AND " . TBL_artist . ".`artist_name` LIKE " . $ci->db->escape($artist_name) . "
              GROUP BY " . mysql_real_escape_string($group_by) . "
              ORDER BY " . mysql_real_escape_string($order_by) . "
              LIMIT " . mysql_real_escape_string($limit);
    $query = $ci->db->query($sql);
    return _json_return_helper($query, $human_readable);
  }
}

/**
   * Returns top albums for the given user.
   *
   * @param array $opts.
   *          'lower_limit'     => Lower date limit in yyyy/mm/dd format
   *          'upper_limit'     => Upper date limit in yyyy/mm/dd format
   *          'username'        => Username
   *          'artist_name'          => Artist name
   *          'album_name'           => Album name
   *          'group_by'        => Group by argument
   *          'order_by'        => Order by argument
   *          'limit'           => Limit
   *          'human_readable'  => Output format
   *
   * @return string JSON encoded data containing album information.
   */
if (!function_exists('getTopAlbums')) {
  function getTopAlbums($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date("Y-m-d", time() - (31 * 24 * 60 * 60));
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date("Y-m-d");
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $album_name = !empty($opts['album_name']) ? $opts['album_name'] : '%';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] :  TBL_album . '.`id`';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, `artist_name` ASC';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT count(" . TBL_album . ".`id`) as `count`, 
                   " . TBL_artist . ".`artist_name`, 
                   " . TBL_artist . ".`id` as artist_id, 
                   " . TBL_album . ".`album_name`, 
                   " . TBL_album . ".`id` as album_id, 
                   " . TBL_album . ".`year`,
                   " . TBL_user . ". `username`, 
                   " . TBL_user . ". `id` as user_id, 
                  (SELECT count(" . TBL_love . ".`album_id`)
                    FROM " . TBL_love . "
                    WHERE " . TBL_love . ".`album_id` = " . TBL_album . ".`id` 
                      AND " . TBL_love . ".`user_id` = " . TBL_user . ".`id`
                  ) AS `love`
            FROM " . TBL_album . ", 
                 " . TBL_artist . ", 
                 " . TBL_listening . " , 
                 " . TBL_user . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`date` BETWEEN " . $ci->db->escape($lower_limit) . " 
                                                   AND " . $ci->db->escape($upper_limit) . "
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_user . ".`username` LIKE " . $ci->db->escape($username) . "
              AND " . TBL_artist . ".`artist_name` LIKE " . $ci->db->escape($artist_name) . "
              AND " . TBL_album . ".`album_name` LIKE " . $ci->db->escape($album_name) . "
              GROUP BY " . mysql_real_escape_string($group_by) . "
              ORDER BY " . mysql_real_escape_string($order_by) . "
              LIMIT " . mysql_real_escape_string($limit);
    $query = $ci->db->query($sql);
    return _json_return_helper($query, $human_readable);
  }
}

/**
   * Returns top albums for the given user.
   *
   * @param array $opts.
   *          'lower_limit'     => Lower date limit in yyyy/mm/dd format
   *          'upper_limit'     => Upper date limit in yyyy/mm/dd format
   *          'username'        => Username
   *          'artist_name'          => Artist name
   *          'album_name'           => Album name
   *          'group_by'        => Group by argument
   *          'order_by'        => Order by argument
   *          'limit'           => Limit
   *          'human_readable'  => Output format
   *
   * @return string JSON encoded data containing album information.
   */
if (!function_exists('getTopListeners')) {
  function getTopListeners($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : '1970-01-01';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date("Y-m-d");
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $album_name = !empty($opts['album_name']) ? $opts['album_name'] : '%';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] :  TBL_user . '.`id`';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, `artist_name` ASC';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT count(" . TBL_user . ".`id`) as `count`, 
                   " . TBL_user . ". `username`, 
                   " . TBL_user . ". `id` as user_id,
                   " . TBL_artist . ".`artist_name`, 
                   " . TBL_artist . ".`id` as artist_id, 
                   " . TBL_album . ".`album_name`, 
                   " . TBL_album . ".`id` as album_id, 
                   " . TBL_album . ".`year`
            FROM " . TBL_album . ", 
                 " . TBL_artist . ", 
                 " . TBL_listening . " , 
                 " . TBL_user . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`date` BETWEEN " . $ci->db->escape($lower_limit) . " 
                                                   AND " . $ci->db->escape($upper_limit) . "
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_artist . ".`artist_name` LIKE " . $ci->db->escape($artist_name) . "
              AND " . TBL_album . ".`album_name` LIKE " . $ci->db->escape($album_name) . "
              GROUP BY " . mysql_real_escape_string($group_by) . "
              ORDER BY " . mysql_real_escape_string($order_by) . "
              LIMIT " . mysql_real_escape_string($limit);
    $query = $ci->db->query($sql);
    return _json_return_helper($query, $human_readable);
  }
}

/**
   * Returns recently listened albums for the given user.
   *
   * @param array $opts.
   *          'username'        => Username
   *          'artist_name'          => Artist name
   *          'album_name'           => Album name
   *          'date'            => Listening date in yyyy/mm/dd format
   *          'limit'           => Limit
   *          'human_readable'  => Output format
   *
   * @return string JSON encoded data containing album information.
   */
if (!function_exists('getRecentlyListened')) {
  function getRecentlyListened($opts = array()) {
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
   * Gets artist's albums which have listenings
   *
   * @param array $opts.
   *          'artist_id'  => Artist ID
   *
   * @return array Album information or boolean FALSE.
   *
   */
if (!function_exists('getArtistAlbums')) {
  function getArtistAlbums($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $album_name = !empty($opts['album_name']) ? $opts['album_name'] : '%';
    $date = !empty($opts['date']) ? $opts['date'] : '%';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT count(" . TBL_album . ".`id`) as `count`,
                   " . TBL_artist . ". `artist_name`,
                   " . TBL_album . ". `album_name`,
                   " . TBL_album . ". `year`, 
                   " . TBL_artist . ". `id` as `artist_id`,
                   " . TBL_album . ". `id` as `album_id`
            FROM " . TBL_listening . ", " . TBL_artist . ", " . TBL_album . ", " . TBL_user . " 
            WHERE " . TBL_album . ". `id` = " . TBL_listening . ". `album_id`
              AND " . TBL_user . ". `id` = " . TBL_listening . ". `user_id`
              AND " . TBL_artist . ". `id` = " . TBL_album . ". `artist_id`
              AND " . TBL_user . ". `username` LIKE " . $ci->db->escape($username) . "
              AND " . TBL_artist . ". `artist_name` LIKE " . $ci->db->escape($artist_name) . "
              AND " . TBL_album . ". `album_name` LIKE " . $ci->db->escape($album_name) . "
              AND " . TBL_listening . ". `date` LIKE " . $ci->db->escape($date) . "
            GROUP BY " . TBL_album . ".`id`
            ORDER BY count(" . TBL_album . ".`id`) DESC";
    $query = $ci->db->query($sql);
    return _json_return_helper($query, $human_readable);
  }
}

/*
 * Helper function for sorting tags
 */
if (!function_exists('_tagsSortByCount')) {
  function _tagsSortByCount($a, $b) {
    if ($a['count'] == $b['count']) {
      return 0;
    }
    return ($a['count'] > $b['count']) ? -1 : 1;
  }
}
?>