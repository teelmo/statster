<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * Returns top genres for the given user.
 *
 * @param array $opts.
 *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
 *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
 *          'username'        => Username
 *          'artist'          => Artist name
 *          'album'           => Album name
 *          'tag_name'        => Tag name
 *          'group_by'        => Group by argument
 *          'order_by'        => Order by argument
 *          'limit'           => Limit
 *          'human_readable'  => Output format
 *
 * @return string JSON encoded the data
 */
if (!function_exists('getGenres')) {
  function getGenres($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $select = !empty($opts['select']) ? ', ' . $opts['select'] : '';
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $album_name = !empty($opts['album_name']) ? $opts['album_name'] : '%';
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : TBL_genre . '.`id`';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT count(*) as `count`, 
                   " .  TBL_genre . ".`name`,
                   'genre' as `type`
                   " . $select . "
            FROM " . TBL_album . ", 
                 " . TBL_artist . ", 
                 " . TBL_listening . ", 
                 " . TBL_user . ", 
                 " . TBL_genre . ", 
                 " . TBL_genres . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_album . ".`id` = " . TBL_genres . ".`album_id`
              AND " . TBL_genre . ".`id` = " . TBL_genres . ".`genre_id`
              AND " . TBL_listening . ".`date` BETWEEN " . $ci->db->escape($lower_limit) . " AND " . $ci->db->escape($upper_limit) . "
              AND " . TBL_artist . ".`artist_name` LIKE " . $ci->db->escape($artist_name) . "
              AND " . TBL_album . ".`album_name` LIKE " . $ci->db->escape($album_name) . "
              AND " . TBL_genres . ".`genre_id` LIKE " . $ci->db->escape($tag_id) . "
              AND " . TBL_user . ".`username` LIKE " . $ci->db->escape($username) . "
              " . $where . "
              GROUP BY " . mysql_real_escape_string($group_by) . "
              ORDER BY " . mysql_real_escape_string($order_by) . " 
              LIMIT " . mysql_real_escape_string($limit);
    $query = $ci->db->query($sql);
    return _json_return_helper($query, $human_readable);
  }
}

/**
   * Gets genres's listenings.
   *
   * @param array $opts.
   *          'tag_id'   => Genre ID
   *          'user_id'  => User ID
   *
   * @return array Listening information.
   *
   */
if (!function_exists('getGenreListenings')) {
  function getGenreListenings($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $count_type = empty($opts['user_id']) ? 'total_count' : 'user_count';
    $opts['user_id'] = empty($opts['user_id']) ? '%' : $opts['user_id'];
    $sql = "SELECT count(*) as `" . $count_type . "`
            FROM " . TBL_album . ",
                 " . TBL_listening . ",
                 (SELECT " . TBL_genres . ".`genre_id`,
                         " . TBL_genres . ".`album_id`
                  FROM " . TBL_genres . "
                  GROUP BY " . TBL_genres . ".`genre_id`, " . TBL_genres . ".`album_id`) as " . TBL_genres . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` LIKE '" . $opts['user_id'] . "'
              AND " . TBL_genres . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_genres . ".`genre_id` = " . $opts['tag_id'];
    $query = $ci->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->result(0);
      return $result[0];
    }
    else {
      return array($count_type => 0);
    }
  }
}

/**
 * Returns top music for given genre.
 *
 * @param array $opts.
 *          'tag_id'          => Tag id
 *          'group_by'        => Group by argument
 *          'order_by'        => Order by argument
 *          'limit'           => Limit
 *          'human_readable'  => Output format
 *
 * @return string JSON encoded data containing album information.
 *
 **/
if (!function_exists('getMusicByGenre')) {
  function getMusicByGenre($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : '`album_id`';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, ' . TBL_album . '.`album_name` ASC';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;

    $sql = "SELECT count(*) as 'count',
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_artist . ".`id` as `artist_id`,
                   " . TBL_album . ".`album_name`,
                   " . TBL_album . ".`id` as `album_id`,
                   " . TBL_album . ".`year`
            FROM " . TBL_artist . ",
                 " . TBL_album . ",
                 " . TBL_listening . ",
                 (SELECT " . TBL_genres . ".`genre_id`,
                         " . TBL_genres . ".`album_id`
                  FROM " . TBL_genres . "
                  GROUP BY " . TBL_genres . ".`genre_id`, " . TBL_genres . ".`album_id`) as " . TBL_genres . "
            WHERE " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id` 
              AND " . TBL_genres . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_genres . ".`genre_id` = " . $tag_id . "
            GROUP BY " . mysql_real_escape_string($group_by) . "
            ORDER BY " . mysql_real_escape_string($order_by) . " 
            LIMIT " . mysql_real_escape_string($limit);
    $query = $ci->db->query($sql);
    return _json_return_helper($query, $human_readable);
  }
}