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

    $album_name = !empty($opts['album_name']) ? $opts['album_name'] : '%';
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : '1970-00-00';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT count(SUBSTRING_INDEX(`format_type`.`name`,' ', 1)) as count, 
                    SUBSTRING_INDEX(`format_type`.`name`,' ', 1) as `group_by_name`, 
                   `format_types`.`listening_format_type_id`,
                   `formats`.`listening_format_id`,
                   `format_type`.`name` AS `format_type_name`,
                   `format_type`.`img` AS `format_type_img`,
                   " . TBL_listening_format . ".`name` AS `format_name`,
                   " . TBL_listening_format . ".`img` AS `format_img`,
                   'format' as `type`
            FROM " . TBL_listening . ",
                 " . TBL_listening_format . ",
                 " . TBL_artist . ",
                 " . TBL_album . ",
                 " . TBL_user . ",
                 " . TBL_listening_formats . " `formats`
                    LEFT JOIN " . TBL_listening_format_types . " `format_types` 
                      ON `formats`.`listening_id` = `format_types`.`listening_id`
                    LEFT JOIN " . TBL_listening_format_type . " `format_type`
                      ON `format_type`.`id` = `format_types`.`listening_format_type_id`
            WHERE `formats`.`listening_id` = " . TBL_listening . ".`id`
              AND " . TBL_listening_format . ".`id` = `formats`.`listening_format_id`
              AND " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_user . ".`id` = " . TBL_listening . ".`user_id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_artist . ".`artist_name` LIKE ?
              AND " . TBL_album . ".`album_name` LIKE ?
              AND " . TBL_user . ".username LIKE ?
            GROUP BY `group_by_name`,
                     `formats`.`listening_format_id`
            ORDER BY `count` DESC
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $artist_name, $album_name, $username));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}