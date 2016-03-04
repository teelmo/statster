<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Get album comment.
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
                   ) AS `count`
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
  * Get artist comment.
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
                   ) AS `count`
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
  * Get user comment.
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
                   " . TBL_user_comment . ".`album_id`,
                   " . TBL_user_comment . ".`created`,
                   (SELECT count(" . TBL_user_comment . ".`album_id`)
                     FROM " . TBL_album_comment . "
                     WHERE " . TBL_user_comment . ".`user_id = " . TBL_user . ".`id`
                       AND " . TBL_user . ".`username` LIKE ?
                   ) AS `count`
            FROM " . TBL_user_comment . ",
                 " . TBL_user . "
            WHERE " . TBL_user_comment . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_user_comment . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_user . ".`username` LIKE ?
            ORDER BY " . TBL_user_comment . ".`created` DESC
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($username, $username));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}