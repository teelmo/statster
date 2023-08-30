<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Returns top listening format types for the given user.
  *
  * @param array $opts.
  *          'album_name'      => Album name
  *          'artist_name'     => Artist name
  *          'group_by'        => Group by argument
  *          'human_readable'  => Output format
  *          'limit'           => Limit
  *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
  *          'order_by'        => Order by argument
  *          'tag_id'          => Tag id
  *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
  *          'username'        => Username
  *
  * @return string JSON encoded the data
  */
if (!function_exists('getListeningFormat')) {
  function getListeningFormat($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $ci->load->helper(array('id_helper'));

    $album_id = isset($opts['album_name']) ? getAlbumID($opts) : '%';
    $artist_id = (isset($opts['artist_name']) && !isset($opts['album_name'])) ? getArtistID($opts) : '%';
    $sub_group_by = (isset($opts['sub_group_by']) && $opts['sub_group_by'] === 'album') ? "GROUP BY " . TBL_artists . ".`album_id`" : ((isset($opts['sub_group_by']) && $opts['sub_group_by'] === 'artist') ? "GROUP BY " . TBL_artists . ".`artist_id`" : "GROUP BY " . TBL_artists . ".`id`");
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : '1970-00-00';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT count(*) AS `count`, 
                    SUBSTRING_INDEX(`format_type`.`name`,' ', 1) AS `group_by_name`, 
                   `format_types`.`listening_format_type_id`,
                   `formats`.`listening_format_id`,
                   `format_type`.`name` AS `format_type_name`,
                   `format_type`.`img` AS `format_type_img`,
                   " . TBL_listening_format . ".`name` AS `format_name`,
                   " . TBL_listening_format . ".`img` AS `format_img`,
                   'format' AS `type`
            FROM " . TBL_listening . ",
                 " . TBL_listening_format . ",
                 " . TBL_artist . ",
                 (SELECT " . TBL_artists . ".`artist_id`,
                         " . TBL_artists . ".`album_id`
                  FROM " . TBL_artists . "
                  " . $sub_group_by . ") AS " . TBL_artists . ",
                 " . TBL_album . ",
                 " . TBL_user . ",
                 " . TBL_listening_formats . " `formats`
                    LEFT JOIN " . TBL_listening_format_types . " `format_types` 
                      ON `formats`.`listening_id` = `format_types`.`listening_id`
                    LEFT JOIN " . TBL_listening_format_type . " `format_type`
                      ON `format_type`.`id` = `format_types`.`listening_format_type_id`
            WHERE `formats`.`listening_id` = " . TBL_listening . ".`id`
              AND " . TBL_listening_format . ".`id` = `formats`.`listening_format_id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_artists . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_artist . ".`id` LIKE ?
              AND " . TBL_album . ".`id` LIKE ?
              AND " . TBL_user . ".username LIKE ?
              " . $ci->db->escape_str($where) . "
            GROUP BY `group_by_name`,
                     `formats`.`listening_format_id`
            ORDER BY `count` DESC
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $artist_id, $album_id, $username));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Returns listening count for given artist or album.
  *
  * @param array $opts.
  *          'group_by'        => Group By
  *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
  *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
  *          'username'        => Username
  *          'where'           => Where
  *
  * @return string JSON encoded data containing artist information.
  */
if (!function_exists('getListeningFormatCount')) {
  function getListeningFormatCount($opts = array(), $type = '') {
    $ci=& get_instance();
    $ci->load->database();

    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : $type . '.`id`';
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : '1970-00-00';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT count(*) AS `count`
            FROM " . TBL_album . ",
                 " . TBL_artist . ",
                 " . TBL_artists . ",
                 " . TBL_listening . ",
                 " . TBL_listening_formats . ",
                 " . TBL_user . "
            WHERE " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_listening . ".`id` = " . TBL_listening_formats . ".`listening_id`
              AND " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_artists . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_user . ".`username` LIKE ?
              " . $ci->db->escape_str($where) . "
            GROUP BY " . $ci->db->escape_str($group_by);
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $username));
    return $query->num_rows();
  }
}