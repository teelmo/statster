<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Returns listening count for given artist or album.
  *
  * @param array $opts.
  *          'group_by'        => Group By
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

    $group_by = !empty($opts['group_by']) ? $opts['group_by'] :  $type . '.`id`';
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : '1970-00-00';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT count(*) as `count`
            FROM " . TBL_album . ",
                 " . TBL_artist . ",
                 " . TBL_listening . ",
                 " . TBL_user . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN ?
                                                   AND ?
              AND " . TBL_user . ".`username` LIKE ?
            GROUP BY " . $ci->db->escape_str($group_by);

    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $username));
    return $query->num_rows();
  }
}

/**
  * Returns top artists for the given user.
  *
  * @param array $opts.
  *          'artist_name'     => Artist name
  *          'group_by'        => Group by argument
  *          'human_readable'  => Output format
  *          'limit'           => Limit
  *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
  *          'order_by'        => Order by argument
  *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
  *          'username'        => Username
  *
  * @return string JSON encoded data containing artist information.
  */
if (!function_exists('getArtists')) {
  function getArtists($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] :  TBL_artist . '.`id`';
    $limit = !empty($opts['limit']) ? $opts['limit'] : '10';
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, `artist_name` ASC';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT count(*) as `count`,
                   " . TBL_artist . ".`artist_name`, 
                   " . TBL_artist . ".`id` as artist_id,
                   " . TBL_artist . ".`spotify_uri`,
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
              AND " . TBL_listening . ".`date` BETWEEN ?
                                                   AND ?
              AND " . TBL_artist . ".`artist_name` LIKE ?
              AND " . TBL_user . ".`username` LIKE ?
            GROUP BY " . $ci->db->escape_str($group_by) . "
            ORDER BY " . $ci->db->escape_str($order_by) . "
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $artist_name, $username));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Returns top albums for the given user.
  *
  * @param array $opts.
  *          'album_name'      => Album name
  *          'artist_name'     => Artist name
  *          'group_by'        => Group by argument
  *          'human_readable'  => Output format
  *          'limit'           => Limit
  *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
  *          'order_by'        => Order by argument
  *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
  *          'username'        => Username
  *
  * @return string JSON encoded data containing album information.
  */
if (!function_exists('getAlbums')) {
  function getAlbums($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    
    $album_name = !empty($opts['album_name']) ? $opts['album_name'] : '%';
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : TBL_album . '.`id`';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, `artist_name` ASC';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT count(*) as `count`,
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_artist . ".`id` as artist_id,
                   " . TBL_album . ".`album_name`,
                   " . TBL_album . ".`id` as album_id,
                   " . TBL_album . ".`year`,
                   " . TBL_album . ".`spotify_uri`,
                   " . TBL_user . ". `username`,
                   " . TBL_user . ". `id` as user_id,
                  (SELECT count(" . TBL_love . ".`album_id`)
                    FROM " . TBL_love . "
                    WHERE " . TBL_love . ".`album_id` = " . TBL_album . ".`id`
                      AND " . TBL_love . ".`user_id` = " . TBL_user . ".`id`
                  ) AS `love`
            FROM " . TBL_album . ",
                 " . TBL_artist . ",
                 " . TBL_listening . ",
                 " . TBL_user . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN ?
                                                   AND ?
              AND " . TBL_user . ".`username` LIKE ?
              AND " . TBL_artist . ".`artist_name` LIKE ?
              AND " . TBL_album . ".`album_name` LIKE ?
            GROUP BY " . $ci->db->escape_str($group_by) . "
            ORDER BY " . $ci->db->escape_str($order_by) . "
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $username, $artist_name, $album_name));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Returns listeners for given artist or album.
  *
  * @param array $opts.
  *          'album_name'      => Album name
  *          'artist_name'     => Artist name
  *          'group_by'        => Group by argument
  *          'human_readable'  => Output format
  *          'limit'           => Limit
  *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
  *          'order_by'        => Order by argument
  *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
  *          'username'        => Username
  *
  * @return string JSON encoded data containing album information.
  */
if (!function_exists('getListeners')) {
  function getListeners($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $album_name = !empty($opts['album_name']) ? $opts['album_name'] : '%';
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $from = !empty($opts['from']) ? ', ' . $opts['from'] : '';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : TBL_user . '.`id`';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : '1970-01-01';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, `artist_name` ASC';
    $select = !empty($opts['select']) ? ', ' . $opts['select'] : '';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT count(*) as `count`,
                   " . TBL_user . ". `username`,
                   " . TBL_user . ". `id` as user_id,
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_artist . ".`id` as artist_id,
                   " . TBL_album . ".`album_name`,
                   " . TBL_album . ".`id` as album_id,
                   " . TBL_album . ".`year`
                  " . $ci->db->escape_str($select) . "
            FROM " . TBL_album . ", 
                 " . TBL_artist . ", 
                 " . TBL_listening . ", 
                 " . TBL_user . "
                 " . $ci->db->escape_str($from) . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN ? 
                                                   AND ?
              AND " . TBL_user . ". `username` LIKE ?
              AND " . TBL_artist . ".`artist_name` LIKE ?
              AND " . TBL_album . ".`album_name` LIKE ?
              " . $ci->db->escape_str($where) . "
            GROUP BY " . $ci->db->escape_str($group_by) . "
            ORDER BY " . $ci->db->escape_str($order_by) . "
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $username, $artist_name, $album_name));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Gets artist's albums which have listenings.
  *
  * @param array $opts.
  *          'artist_name'     => Artist name
  *          'human_readable'  => Output format
  *          'username'        => Username
  *
  * @return array Album information or boolean FALSE.
  *
  */
if (!function_exists('getArtistAlbums')) {
  function getArtistAlbums($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT count(*) as `count`,
                   " . TBL_artist . ". `artist_name`,
                   " . TBL_album . ". `album_name`,
                   " . TBL_album . ". `year`, 
                   " . TBL_album . ". `spotify_uri`, 
                   " . TBL_artist . ". `id` as `artist_id`,
                   " . TBL_album . ". `id` as `album_id`
            FROM " . TBL_listening . ", " . TBL_artist . ", " . TBL_album . ", " . TBL_user . " 
            WHERE " . TBL_album . ". `id` = " . TBL_listening . ". `album_id`
              AND " . TBL_user . ". `id` = " . TBL_listening . ". `user_id`
              AND " . TBL_artist . ". `id` = " . TBL_album . ". `artist_id`
              AND " . TBL_user . ". `username` LIKE ?
              AND " . TBL_artist . ". `artist_name` LIKE ?
            GROUP BY " . TBL_album . ".`id`
            ORDER BY count(" . TBL_album . ".`id`) DESC";
    $query = $ci->db->query($sql, array($username, $artist_name));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
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