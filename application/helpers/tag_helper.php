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
    $tag_name = !empty($opts['tag_name']) ? $opts['tag_name'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : TBL_genre . '.`id`';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT count(" . TBL_genre . ".`id`) as `count`, 
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
              AND " . TBL_genre . ".`name` LIKE " . $ci->db->escape($tag_name) . "
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
 * Returns top keywords for the given user.
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
 * @return string JSON encoded the data.
 */
if (!function_exists('getKeywords')) {
  function getKeywords($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    
    $select = !empty($opts['select']) ? ', ' . $opts['select'] : '';
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $album_name = !empty($opts['album_name']) ? $opts['album_name'] : '%';
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $tag_name = !empty($opts['tag_name']) ? $opts['tag_name'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : TBL_keyword . '.`id`';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT count(" . TBL_keyword . ".`id`) as `count`, 
                   " .  TBL_keyword . ".`name`,
                   'keyword' as `type`
                   " . $select . "
            FROM " . TBL_album . ", 
                 " . TBL_artist . ", 
                 " . TBL_listening . ", 
                 " . TBL_user . ", 
                 " . TBL_keyword . ", 
                 " . TBL_keywords . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_album . ".`id` = " . TBL_keywords . ".`album_id`
              AND " . TBL_keyword . ".`id` = " . TBL_keywords . ".`keyword_id`
              AND " . TBL_listening . ".`date` BETWEEN " . $ci->db->escape($lower_limit) . " AND " . $ci->db->escape($upper_limit) . "
              AND " . TBL_artist . ".`artist_name` LIKE " . $ci->db->escape($artist_name) . "
              AND " . TBL_album . ".`album_name` LIKE " . $ci->db->escape($album_name) . "
              AND " . TBL_keyword . ".`name` LIKE " . $ci->db->escape($tag_name) . "
              AND " . TBL_user . ".`username` LIKE " . $ci->db->escape($username) . "
              " . $where . "
              GROUP BY " . mysql_real_escape_string($group_by) . "
              ORDER BY " . mysql_real_escape_string($order_by) . " 
              LIMIT " . mysql_real_escape_string($limit);
              // echo $sql;
    $query = $ci->db->query($sql);
    return _json_return_helper($query, $human_readable);
  }
}

/**
 * Returns top nationalities for the given user.
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
 * @return string JSON encoded data containing album information.
 */
if (!function_exists('getNationalities')) {
  function getNationalities($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    
    $select = !empty($opts['select']) ? ', ' . $opts['select'] : '';
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $album_name = !empty($opts['album_name']) ? $opts['album_name'] : '%';
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $tag_name = !empty($opts['tag_name']) ? $opts['tag_name'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : TBL_nationality . '.`id`';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT count(" . TBL_nationality . ".`id`) as `count`, 
                   " .  TBL_nationality . ".`country`,
                   'nationality' as `type`
                   " . $select . "
            FROM " . TBL_album . ", 
                 " . TBL_artist . ", 
                 " . TBL_listening . ", 
                 " . TBL_user . ", 
                 " . TBL_nationality . ", 
                 " . TBL_nationalities . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_album . ".`id` = " . TBL_nationalities . ".`album_id`
              AND " . TBL_nationality . ".`id` = " . TBL_nationalities . ".`nationality_id`
              AND " . TBL_listening . ".`date` BETWEEN " . $ci->db->escape($lower_limit) . " AND " . $ci->db->escape($upper_limit) . "
              AND " . TBL_artist . ".`artist_name` LIKE " . $ci->db->escape($artist_name) . "
              AND " . TBL_album . ".`album_name` LIKE " . $ci->db->escape($album_name) . "
              AND " . TBL_nationality . ".`country` LIKE " . $ci->db->escape($tag_name) . "
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
 * Returns top music for given tag name.
 *
 * @param array $opts.
 *          'tag_name'        => Tag name
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

    $tag_name = !empty($opts['tag_name']) ? $opts['tag_name'] : '%';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : TBL_listening . '.`album_id`, ' . TBL_genres . '.`user_id`';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, ' . TBL_album . '.`album_name` ASC';

    $sql = "SELECT DISTINCT count(" . TBL_listening . ".`album_id`) as 'count',
                            " . TBL_artist . ".`artist_name`,
                            " . TBL_artist . ".`id` as `artist_id`,
                            " . TBL_album . ".`album_name`,
                            " . TBL_album . ".`id` as `album_id`,
                            " . TBL_album . ".`year`
            FROM " . TBL_genres . "
            INNER JOIN " . TBL_album . " ON " . TBL_genres . ".`album_id` = " . TBL_album . ".`id`
            INNER JOIN " . TBL_genre . " ON " . TBL_genres . ".`genre_id` = " . TBL_genre . ".`id`
            INNER JOIN " . TBL_listening . " ON " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
            INNER JOIN " . TBL_artist . " ON " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id`
            WHERE " . TBL_genre . ".`name` LIKE " . $ci->db->escape($tag_name) . "
            GROUP BY " . mysql_real_escape_string($group_by) . "
            ORDER BY " . mysql_real_escape_string($order_by) . " 
            LIMIT " . mysql_real_escape_string($limit);
    $query = $ci->db->query($sql);
    return _json_return_helper($query, $human_readable);
  }
}