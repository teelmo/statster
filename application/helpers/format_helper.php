<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Returns top listening format types for the album or artist.
  *
  * @param array $opts.
  *          'album_name'      => Album name
  *          'artist_name'     => Artist name
  *          'group_by'        => Group by argument
  *          'no_content'      => Output format
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

    // Narrow the artist/album group down before the GROUP BY when possible,
    // instead of grouping the whole artists table and filtering afterwards -
    // same fix as getListenings() in listening_helper.php (see
    // [[project_recent_listenings_query_fix]]).
    $sub_where = array();
    $sub_params = array();
    if ($artist_id !== '%') {
      $sub_where[] = TBL_artists . '.`artist_id` = ?';
      $sub_params[] = $artist_id;
    }
    if ($album_id !== '%') {
      $sub_where[] = TBL_artists . '.`album_id` = ?';
      $sub_params[] = $album_id;
    }
    $sub_where_sql = !empty($sub_where) ? 'WHERE ' . implode(' AND ', $sub_where) : '';

    $sql = "SELECT count(*) AS `count`,
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
                  " . $sub_where_sql . "
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
            GROUP BY `format_type`.`name`,
                     `formats`.`listening_format_id`
            ORDER BY `count` DESC
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array_merge($sub_params, array($lower_limit, $upper_limit, $artist_id, $album_id, $username)));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}

/**
  * Returns recently listened albums for the given format.
  *
  * @param array $opts.
  *          'format_name'        => Artist name
  *          'date'               => Listening date in yyyy-mm-dd format
  *          'from'               => Extra from
  *          'no_content'         => Output format
  *          'limit'              => Limit
  *          'username'           => Username
  *
  * @return string JSON.
  */
if (!function_exists('getFormatListenings')) {
  function getFormatListenings($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $ci->load->helper(array('id_helper'));

    $format_id = (isset($opts['format_name'])) ? getFormatID($opts) : '%';
    $date = !empty($opts['date']) ? $opts['date'] : '%';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : '1970-00-00';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    // There's no artist/album filter here to push into an artists derived
    // subquery (unlike getListenings()/getListeningFormat()) - this always
    // wants every format+album combo across a user's whole (possibly
    // date-bounded) history, aggregated and ranked by count, so that part
    // can't be avoided. But the old version still joined a `GROUP BY
    // artists.id` derived table (grouping the whole artists table, 10k+
    // rows) into the aggregation itself, uselessly, since the join is
    // 1-per-album and never changes the count/ranking. Aggregate first
    // (listening/album/user/format tables only), then decorate just the
    // resulting LIMIT rows with an artist name - same restructuring as
    // getListenings()'s fast path (see [[project_recent_listenings_query_fix]]).
    $sql = "SELECT `format_listening`.`count`,
                   `format_listening`.`listening_id`,
                   " . TBL_artist . ".`artist_name`,
                   `format_listening`.`album_name`,
                   `format_listening`.`year`,
                   `format_listening`.`spotify_id`,
                   `format_listening`.`username`,
                   `format_listening`.`date`,
                   `format_listening`.`created`,
                   " . TBL_artist . ".`id` AS `artist_id`,
                   `format_listening`.`album_id`,
                   `format_listening`.`user_id`
            FROM (
                SELECT count(*) AS `count`,
                       " . TBL_listening . ".`id` AS `listening_id`,
                       " . TBL_album . ".`album_name`,
                       " . TBL_album . ".`year`,
                       " . TBL_album . ".`spotify_id`,
                       " . TBL_user . ".`username`,
                       " . TBL_listening . ".`date`,
                       " . TBL_listening . ".`created`,
                       " . TBL_album . ".`id` AS `album_id`,
                       " . TBL_user . ".`id` AS `user_id`
                FROM " . TBL_album . ",
                     " . TBL_listening_format . ",
                     " . TBL_listening_formats . ",
                     " . TBL_listening . ",
                     " . TBL_user . "
                WHERE " . TBL_listening . ".`album_id` =  " . TBL_album . ".`id`
                  AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
                  AND " . TBL_listening_formats . ".`listening_format_id` = " . TBL_listening_format . ".`id`
                  AND " . TBL_listening_formats . ".`listening_id` = " . TBL_listening . ".`id`
                  AND " . TBL_user . ".`username` LIKE ?
                  AND " . TBL_listening . ".`date` BETWEEN ? AND ?
                  AND " . TBL_listening_format . ".`id` LIKE ?
                  AND " . TBL_listening . ".`date` LIKE ?
                GROUP BY " . TBL_listening_format . ".`name`,
                         " . TBL_album . ".`id`
                ORDER BY `count` DESC, " . TBL_album . ".`album_name` ASC
                LIMIT " . $ci->db->escape_str($limit) . "
            ) AS `format_listening`
            JOIN " . TBL_artists . " ON " . TBL_artists . ".`album_id` = `format_listening`.`album_id`
            JOIN " . TBL_artist . " ON " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
            GROUP BY `format_listening`.`album_id`
            ORDER BY `format_listening`.`count` DESC, `format_listening`.`album_name` ASC";
    $query = $ci->db->query($sql, array($username, $lower_limit, $upper_limit, $format_id, $date));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}

/**
  * Returns recently listened albums for the format type.
  *
  * @param array $opts.
  *          'format_name'        => Artist name
  *          'date'               => Listening date in yyyy-mm-dd format
  *          'from'               => Extra from
  *          'no_content'     => Output format
  *          'limit'              => Limit
  *          'username'           => Username
  *
  * @return string JSON.
  */
if (!function_exists('getFormatTypeListenings')) {
  function getFormatTypeListenings($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $ci->load->helper(array('id_helper'));
    $format_type_id = (isset($opts['format_type_name'])) ? getFormatTypeID($opts) : '%';
    $date = !empty($opts['date']) ? $opts['date'] : '%';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : '1970-00-00';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    // Same restructuring as getFormatListenings() above - aggregate first,
    // decorate the resulting LIMIT rows with an artist name after (see
    // [[project_recent_listenings_query_fix]]).
    $sql = "SELECT `format_listening`.`count`,
                   `format_listening`.`listening_id`,
                   " . TBL_artist . ".`artist_name`,
                   `format_listening`.`album_name`,
                   `format_listening`.`year`,
                   `format_listening`.`spotify_id`,
                   `format_listening`.`username`,
                   `format_listening`.`date`,
                   `format_listening`.`created`,
                   " . TBL_artist . ".`id` AS `artist_id`,
                   `format_listening`.`album_id`,
                   `format_listening`.`user_id`
            FROM (
                SELECT count(*) AS `count`,
                       " . TBL_listening . ".`id` AS `listening_id`,
                       " . TBL_album . ".`album_name`,
                       " . TBL_album . ".`year`,
                       " . TBL_album . ".`spotify_id`,
                       " . TBL_user . ".`username`,
                       " . TBL_listening . ".`date`,
                       " . TBL_listening . ".`created`,
                       " . TBL_album . ".`id` AS `album_id`,
                       " . TBL_user . ".`id` AS `user_id`
                FROM " . TBL_album . ",
                     " . TBL_listening_format_type . ",
                     " . TBL_listening_format_types . ",
                     " . TBL_listening . ",
                     " . TBL_user . "
                WHERE " . TBL_listening . ".`album_id` =  " . TBL_album . ".`id`
                  AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
                  AND " . TBL_listening_format_types . ".`listening_format_type_id` = " . TBL_listening_format_type . ".`id`
                  AND " . TBL_listening_format_types . ".`listening_id` = " . TBL_listening . ".`id`
                  AND " . TBL_user . ".`username` LIKE ?
                  AND " . TBL_listening . ".`date` BETWEEN ? AND ?
                  AND " . TBL_listening_format_type . ".`id` LIKE ?
                  AND " . TBL_listening . ".`date` LIKE ?
                GROUP BY " . TBL_listening_format_type . ".`name`,
                         " . TBL_album . ".`id`
                ORDER BY `count` DESC, " . TBL_album . ".`album_name` ASC
                LIMIT " . $ci->db->escape_str($limit) . "
            ) AS `format_listening`
            JOIN " . TBL_artists . " ON " . TBL_artists . ".`album_id` = `format_listening`.`album_id`
            JOIN " . TBL_artist . " ON " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
            GROUP BY `format_listening`.`album_id`
            ORDER BY `format_listening`.`count` DESC, `format_listening`.`album_name` ASC";
    $query = $ci->db->query($sql, array($username, $lower_limit, $upper_limit, $format_type_id, $date));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}

/**
  * Returns listening count for given format.
  *
  * @param array $opts.
  *          'format_name'      => Format name
  *          'lower_limit'      => Lower date limit in yyyy-mm-dd format
  *          'upper_limit'      => Upper date limit in yyyy-mm-dd format
  *          'username'         => Username
  *          'where'            => Where
  *
  * @return string JSON encoded data containing the information.
  */
if (!function_exists('getListeningFormatCount')) {
  function getListeningFormatCount($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $ci->load->helper(array('id_helper'));
    $format_id = (isset($opts['format_name'])) ? getFormatID($opts) : '%';
    $group_by = (isset($opts['group_by'])) ? $opts['group_by'] : TBL_listening_formats . ".`id`";
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
              AND " . TBL_listening_formats . ".`listening_format_id` LIKE ?
              AND " . TBL_user . ".`username` LIKE ?
              " . $ci->db->escape_str($where) . "
            GROUP BY " . $ci->db->escape_str($group_by) . "";
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $format_id, $username));
    return $query->num_rows();
  }
}

/**
  * Returns listening count for given format type.
  *
  * @param array $opts.
  *          'format_type_name' => Format type name
  *          'lower_limit'      => Lower date limit in yyyy-mm-dd format
  *          'lower_limit'      => Lower date limit in yyyy-mm-dd format
  *          'upper_limit'      => Upper date limit in yyyy-mm-dd format
  *          'username'         => Username
  *          'where'            => Where
  *
  * @return string JSON encoded data containing the information.
  */
if (!function_exists('getListeningFormatTypeCount')) {
  function getListeningFormatTypeCount($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $ci->load->helper(array('id_helper'));
    $format_type_id = (isset($opts['format_type_name'])) ? getFormatTypeID($opts) : '%';
    $group_by = (isset($opts['group_by'])) ? $opts['group_by'] : TBL_listening_format_types . ".`id`";
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : '1970-00-00';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT count(*) AS `count`
            FROM " . TBL_album . ",
                 " . TBL_artist . ",
                 " . TBL_artists . ",
                 " . TBL_listening . ",
                 " . TBL_listening_format_types . ",
                 " . TBL_user . "
            WHERE " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_listening . ".`id` = " . TBL_listening_format_types . ".`listening_id`
              AND " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_artists . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_listening_format_types . ".`listening_format_type_id` LIKE ?
              AND " . TBL_user . ".`username` LIKE ?
              " . $ci->db->escape_str($where) . "
            GROUP BY " . $ci->db->escape_str($group_by) . "";
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $format_type_id, $username));
    return $query->num_rows();
  }
}