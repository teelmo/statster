<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * Returns top genres for the given user.
 *
 * @param array $opts.
 *          'lower_limit'     => Lower date limit in yyyy/mm/dd format
 *          'upper_limit'     => Upper date limit in yyyy/mm/dd format
 *          'username'        => Username
 *          'artist'          => Artist name
 *          'album'           => Album name
 *          'group_by'        => Group by argument
 *          'order_by'        => Order by argument
 *          'limit'           => Limit
 *          'human_readable'  => Output format
 *
 * @return string JSON encoded data containing album information.
 */
if (!function_exists('getGenres')) {
  function getGenres($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date("Y-m-d", time() - (31 * 24 * 60 * 60));
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date("Y-m-d");
    $artist = !empty($opts['artist']) ? $opts['artist'] : '%';
    $album = !empty($opts['album']) ? $opts['album'] : '%';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT count(" . TBL_genre . ".`id`) as `count`, 
                   " .  TBL_genre . ".`name`,
                   'genre' as `type`
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
              AND " . TBL_listening . ".`date` BETWEEN " . $ci->db->escape($lower_limit) . " 
                                                       AND " . $ci->db->escape($upper_limit) . "
              AND " . TBL_artist . ".`artist_name` LIKE " . $ci->db->escape($artist) . "
              AND " . TBL_album . ".`album_name` LIKE " . $ci->db->escape($album) . "
              GROUP BY " . TBL_genre . ".`id`
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
 *          'lower_limit'     => Lower date limit in yyyy/mm/dd format
 *          'upper_limit'     => Upper date limit in yyyy/mm/dd format
 *          'username'        => Username
 *          'artist'          => Artist name
 *          'album'           => Album name
 *          'group_by'        => Group by argument
 *          'order_by'        => Order by argument
 *          'limit'           => Limit
 *          'human_readable'  => Output format
 *
 * @return string JSON encoded data containing album information.
 */
if (!function_exists('getKeywords')) {
  function getKeywords($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date("Y-m-d", time() - (31 * 24 * 60 * 60));
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date("Y-m-d");
    $artist = !empty($opts['artist']) ? $opts['artist'] : '%';
    $album = !empty($opts['album']) ? $opts['album'] : '%';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT count(" . TBL_keyword . ".`id`) as `count`, 
                   " . TBL_keyword . ".`name`,
                   'keyword' as `type`
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
              AND " . TBL_listening . ".`date` BETWEEN " . $ci->db->escape($lower_limit) . " 
                                                       AND " . $ci->db->escape($upper_limit) . "
              AND " . TBL_artist . ".`artist_name` LIKE " . $ci->db->escape($artist) . "
              AND " . TBL_album . ".`album_name` LIKE " . $ci->db->escape($album) . "
              GROUP BY " . TBL_keyword . ".`id`
              ORDER BY " . mysql_real_escape_string($order_by) . " 
              LIMIT " . mysql_real_escape_string($limit);
    $query = $ci->db->query($sql);
    return _json_return_helper($query, $human_readable);
  }
}
?>