<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * Returns top keywords for the given user.
 *
 * @param array $opts.
 *          'album'           => Album name
 *          'artist'          => Artist name
 *          'group_by'        => Group by argument
 *          'human_readable'  => Output format
 *          'limit'           => Limit
 *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
 *          'order_by'        => Order by argument
 *          'tag_id'          => Tag id
 *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
 *          'username'        => Username
 *
 * @return string JSON encoded the data.
 */
if (!function_exists('getKeywords')) {
  function getKeywords($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    
    $album_name = !empty($opts['album_name']) ? $opts['album_name'] : '%';
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : TBL_keyword . '.`id`';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC';
    $select = !empty($opts['select']) ? ', ' . $opts['select'] : '';
    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '%';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT count(*) as `count`,
                   " . TBL_keyword . ".`name`,
                   'keyword' as `type`
                   " . $ci->db->escape_str($select) . "
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
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_artist . ".`artist_name` LIKE ?
              AND " . TBL_album . ".`album_name` LIKE ?
              AND " . TBL_keywords . ".`keyword_id` LIKE ?
              AND " . TBL_user . ".`username` LIKE ?
              " . $ci->db->escape_str($where) . "
              GROUP BY " . $ci->db->escape_str($group_by) . "
              ORDER BY " . $ci->db->escape_str($order_by) . "
              LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $artist_name, $album_name, $tag_id, $username));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
   * Gets keyword's listenings.
   *
   * @param array $opts.
   *          'tag_id'      => Keyword ID
   *          'user_id'     => User ID
   *
   * @return array Listening information.
   *
   */
if (!function_exists('getKeywordListenings')) {
  function getKeywordListenings($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $count_type = empty($opts['user_id']) ? 'total_count' : 'user_count';
    $tag_id = empty($opts['tag_id']) ? '%' : $opts['tag_id'];
    $user_id = empty($opts['user_id']) ? '%' : $opts['user_id'];
    $sql = "SELECT count(*) as `" . $count_type . "`
            FROM " . TBL_album . ",
                 " . TBL_listening . ",
                 (SELECT " . TBL_keywords . ".`keyword_id`,
                         " . TBL_keywords . ".`album_id`
                  FROM " . TBL_keywords . "
                  GROUP BY " . TBL_keywords . ".`keyword_id`, " . TBL_keywords . ".`album_id`) as " . TBL_keywords . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_keywords . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`user_id` LIKE ?
              AND " . TBL_keywords . ".`keyword_id` = ?";
    $query = $ci->db->query($sql, array($user_id, $tag_id));
    return (($query->num_rows() > 0)) ? $query->result(0)[0] : array($count_type => 0);
  }
}

/**
 * Returns top music for given keyword.
 *
 * @param array $opts.
 *          'group_by'        => Group by argument
 *          'human_readable'  => Output format
 *          'limit'           => Limit
 *          'order_by'        => Order by argument
 *          'tag_id'             => Keyword id
 *
 * @return string JSON encoded data containing album information.
 *
 **/
if (!function_exists('getMusicByKeyword')) {
  function getMusicByKeyword($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : '`album_id`';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, ' . TBL_album . '.`album_name` ASC';
    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '%';
    $username = !empty($opts['username']) ? $opts['username'] : '%';

    $sql = "SELECT count(*) as 'count',
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_artist . ".`id` as `artist_id`,
                   " . TBL_album . ".`album_name`,
                   " . TBL_album . ".`id` as `album_id`,
                   " . TBL_album . ".`year`
            FROM " . TBL_artist . ",
                 " . TBL_album . ",
                 " . TBL_listening . ",
                 " . TBL_user . ",
                 (SELECT " . TBL_keywords . ".`keyword_id`,
                         " . TBL_keywords . ".`album_id`
                  FROM " . TBL_keywords . "
                  GROUP BY " . TBL_keywords . ".`keyword_id`, " . TBL_keywords . ".`album_id`) as " . TBL_keywords . "
            WHERE " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id` 
              AND " . TBL_keywords . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_user . ".`username` LIKE ?
              AND " . TBL_keywords . ".`keyword_id` LIKE ?
            GROUP BY " . $ci->db->escape_str($group_by) . "
            ORDER BY " . $ci->db->escape_str($order_by) . " 
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($username, $tag_id));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}