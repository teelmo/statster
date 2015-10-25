<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Returns top artists for the given user.
  *
  * @param array $opts.
  *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
  *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
  *          'username'        => Username
  *
  * @return string JSON encoded data containing artist information.
  */
if (!function_exists('getListeningCount')) {
  function getListeningCount($opts = array(), $type) {
    $ci=& get_instance();
    $ci->load->database();

    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : '1970-00-00';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] :  $type . '.`id`';
    $sql = "SELECT count(" . $type . ".`id`) as `count`
            FROM " . TBL_album . ",
                 " . TBL_artist . ",
                 " . TBL_listening . ",
                 " . TBL_user . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN " . $ci->db->escape($lower_limit) . "
                                                   AND " . $ci->db->escape($upper_limit) . "
              AND " . TBL_user . ".`username` LIKE " . $ci->db->escape($username) . "
            GROUP BY " . mysql_real_escape_string($group_by);
    $query = $ci->db->query($sql);
    return $query->num_rows();
  }
}

/**
  * Returns top artists for the given user.
  *
  * @param array $opts.
  *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
  *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
  *          'username'        => Username
  *          'artist_name'     => Artist name
  *          'group_by'        => Group by argument
  *          'order_by'        => Order by argument
  *          'limit'           => Limit
  *          'human_readable'  => Output format
  *
  * @return string JSON encoded data containing artist information.
  */
if (!function_exists('getArtists')) {
  function getArtists($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] :  TBL_artist . '.`id`';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, `artist_name` ASC';
    $limit = !empty($opts['limit']) ? $opts['limit'] : '10';
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
                 " . TBL_listening . ",
                 " . TBL_user . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN " . $ci->db->escape($lower_limit) . "
                                                   AND " . $ci->db->escape($upper_limit) . "
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
  *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
  *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
  *          'username'        => Username
  *          'artist_name'     => Artist name
  *          'album_name'      => Album name
  *          'group_by'        => Group by argument
  *          'order_by'        => Order by argument
  *          'limit'           => Limit
  *          'human_readable'  => Output format
  *
  * @return string JSON encoded data containing album information.
  */
if (!function_exists('getAlbums')) {
  function getAlbums($opts = array()) {
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
  *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
  *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
  *          'username'        => Username
  *          'artist_name'     => Artist name
  *          'album_name'      => Album name
  *          'group_by'        => Group by argument
  *          'order_by'        => Order by argument
  *          'limit'           => Limit
  *          'human_readable'  => Output format
  *
  * @return string JSON encoded data containing album information.
  */
if (!function_exists('getListeners')) {
  function getListeners($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : '1970-01-01';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $album_name = !empty($opts['album_name']) ? $opts['album_name'] : '%';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] :  TBL_user . '.`id`';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, `artist_name` ASC';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT year(" . TBL_listening . ".`date`) as `date`,
                   count(" . TBL_user . ".`id`) as `count`,
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
            WHERE " . TBL_user . ". `username` LIKE " . $ci->db->escape($username) . "
              AND " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
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
  * Gets artist's albums which have listenings.
  *
  * @param array $opts.
  *          'username'        => Username
  *          'artist_name'     => Artist name
  *          'limit'           => Limit
  *          'human_readable'  => Output format
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
            GROUP BY " . TBL_album . ".`id`
            ORDER BY count(" . TBL_album . ".`id`) DESC";
    $query = $ci->db->query($sql);
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Gets artist's or album's nationalities.
  *
  * @param array $opts.
  *          'username'        => Username
  *          'artist_name'     => Artist name
  *          'limit'           => Limit
  *          'human_readable'  => Output format
  *
  * @return array Nationality information or boolean FALSE.
  *
  */
if (!function_exists('getAlbumNationalities')) {
  function getAlbumNationalities($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '';
    $album_name = !empty($opts['album_name']) ? $opts['album_name'] : '';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT " . TBL_artist . ".`id` as `artist_id`, " . TBL_album . ".`id` as `album_id`, " . TBL_artist . ".`artist_name`, " . TBL_album . ".`album_name`, " . TBL_album . ".`year`
            FROM " . TBL_artist . ", " . TBL_album . "
            WHERE ".TBL_album.".`artist_id` = " . TBL_artist . ".`id` 
              AND " . TBL_artist . ".`artist_name` = " . $ci->db->escape($artist_name) . "
              AND " . TBL_album . ".`album_name` = " . $ci->db->escape($album_name);
    $query = $ci->db->query($sql);
    return _json_return_helper($query, $human_readable);
  }
}

/**
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