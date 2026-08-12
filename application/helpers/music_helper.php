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
  *          'where'           => Where
  *
  * @return string JSON encoded data containing artist information.
  */
if (!function_exists('getListeningCount')) {
  function getListeningCount($opts = array(), $type = '') {
    $ci=& get_instance();
    $ci->load->database();

    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : $type . '.`id`';
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : '1970-00-00';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    // STRAIGHT_JOIN + driving from listening first - see getArtists() below,
    // same join-order issue (worse here since callers often add a
    // non-sargable MONTH()/DAY()/WEEKDAY() filter on top), same fix.
    $sql = "SELECT STRAIGHT_JOIN count(*) AS `count`
            FROM " . TBL_listening . "
            JOIN " . TBL_album . " ON " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
            JOIN " . TBL_user . " ON " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
            JOIN " . TBL_artists . " ON " . TBL_artists . ".`album_id` = " . TBL_album . ".`id`
            JOIN " . TBL_artist . " ON " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
            WHERE " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_user . ".`username` LIKE ?
              " . $ci->db->escape_str($where) . "
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
  *          'no_content'  => Output format
  *          'limit'           => Limit
  *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
  *          'order_by'        => Order by argument
  *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
  *          'username'        => Username
  *          'where'           => Where
  *
  * @return string JSON encoded data containing artist information.
  */
if (!function_exists('getArtists')) {
  function getArtists($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : '%';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] :  TBL_artist . '.`id`, ' . TBL_user . '.`id`';
    $having = !empty($opts['having']) ? 'HAVING ' . $opts['having'] : '';
    $limit = !empty($opts['limit']) ? $opts['limit'] : '10';
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, ' . TBL_artist . '.`artist_name` ASC';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    // A LIKE '%' clause matches every row, but MySQL's optimizer still has to
    // weigh it as a filter when picking a join order - on this query that
    // was enough to make it drive from artist (a full table scan) instead of
    // the far more selective listening.date range. Omitting the clause
    // entirely when there's no real filter avoids that, with no change in
    // which rows match.
    $params = array($lower_limit, $upper_limit);
    $artist_name_sql = '';
    if ($artist_name !== '%') {
      $artist_name_sql = "AND " . TBL_artist . ".`artist_name` LIKE ?";
      $params[] = $artist_name;
    }
    $username_sql = '';
    if ($username !== '%') {
      $username_sql = "AND " . TBL_user . ".`username` LIKE ?";
      $params[] = $username;
    }
    // STRAIGHT_JOIN + driving from listening first: the optimizer otherwise
    // picks artist as the driving table (a full ~4400-row scan) instead of
    // the far more selective listening.date range, measured ~4x slower.
    $sql = "SELECT STRAIGHT_JOIN count(*) AS `count`,
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_artist . ".`id` AS `artist_id`,
                   " . TBL_artist . ".`spotify_id`,
                   " . TBL_user . ".`username`,
                   " . TBL_user . ".`id` AS `user_id`,
                  (SELECT count(" . TBL_fan . ".`artist_id`)
                    FROM " . TBL_fan . "
                    WHERE " . TBL_fan . ".`artist_id` = " . TBL_artist . ".`id`
                      AND " . TBL_fan . ".`user_id` = " . TBL_user . ".`id`
                  ) AS `fan`
            FROM " . TBL_listening . "
            JOIN " . TBL_album . " ON " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
            JOIN " . TBL_user . " ON " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
            JOIN " . TBL_artists . " ON " . TBL_artists . ".`album_id` = " . TBL_album . ".`id`
            JOIN " . TBL_artist . " ON " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
            WHERE " . TBL_listening . ".`date` BETWEEN ? AND ?
              " . $artist_name_sql . "
              " . $username_sql . "
              " . $ci->db->escape_str($where) . "
            GROUP BY " . $ci->db->escape_str($group_by) . "
            " . $ci->db->escape_str($having) . "
            ORDER BY " . $ci->db->escape_str($order_by) . "
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, $params);

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}

/**
  * Returns top albums for the given user.
  *
  * @param array $opts.
  *          'album_name'      => Album name
  *          'artist_name'     => Artist name
  *          'group_by'        => Group by argument
  *          'having'          => Custom having argument
  *          'no_content'      => Output format
  *          'limit'           => Limit
  *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
  *          'order_by'        => Order by argument
  *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
  *          'username'        => Username
  *          'where'           => Custom where argument
  *
  * @return string JSON encoded data containing album information.
  */
if (!function_exists('getAlbums')) {
  function getAlbums($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    
    $album_name = isset($opts['album_name']) ? $opts['album_name'] : '%';
    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : '%';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : TBL_album . '.`id`';
    $having = !empty($opts['having']) ? 'HAVING ' . $opts['having'] : '';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, `artist_name` ASC';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    // STRAIGHT_JOIN + driving from listening first - see getArtists() above,
    // same join-order issue, same fix (measured ~3.5x faster).
    $sql = "SELECT STRAIGHT_JOIN count(*) AS `count`,
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_artist . ".`id` AS `artist_id`,
                   " . TBL_album . ".`album_name`,
                   " . TBL_album . ".`id` AS `album_id`,
                   " . TBL_album . ".`year`,
                   " . TBL_album . ".`spotify_id`,
                   " . TBL_user . ".`username` AS `username`,
                   " . TBL_listening . ".`date` AS `date`,
                   " . TBL_user . ".`id` AS `user_id`,
                  (SELECT count(" . TBL_love . ".`album_id`)
                    FROM " . TBL_love . "
                    WHERE " . TBL_love . ".`album_id` = " . TBL_album . ".`id`
                      AND " . TBL_love . ".`user_id` = " . TBL_user . ".`id`
                  ) AS `love`
            FROM " . TBL_listening . "
            JOIN " . TBL_album . " ON " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
            JOIN " . TBL_user . " ON " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
            JOIN (SELECT " . TBL_artists . ".`artist_id`,
                         " . TBL_artists . ".`album_id`
                  FROM " . TBL_artists . "
                  GROUP BY " . TBL_artists . ".`album_id`) AS " . TBL_artists . " ON " . TBL_artists . ".`album_id` = " . TBL_album . ".`id`
            JOIN " . TBL_artist . " ON " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
            WHERE " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_user . ".`username` LIKE ?
              AND " . TBL_artist . ".`artist_name` LIKE ?
              AND " . TBL_album . ".`album_name` LIKE ?
              " . $ci->db->escape_str($where) . "
            GROUP BY " . $ci->db->escape_str($group_by) . "
            " . $ci->db->escape_str($having) . "
            ORDER BY " . $ci->db->escape_str($order_by) . "
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $username, $artist_name, $album_name));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}

/**
  * Returns listeners for given artist or album.
  *
  * @param array $opts.
  *          'album_name'      => Album name
  *          'artist_name'     => Artist name
  *          'group_by'        => Group by argument
  *          'no_content'      => Output format
  *          'limit'           => Limit
  *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
  *          'order_by'        => Order by argument
  *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
  *          'username'        => Username
  *          'where'           => Custom where argument
  *
  * @return string JSON encoded data containing album information.
  */
if (!function_exists('getListeners')) {
  function getListeners($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $ci->load->helper(array('id_helper'));

    $album_id = isset($opts['album_name']) ? getAlbumID($opts) : '%';
    $artist_id = (isset($opts['artist_name']) && !isset($opts['album_name'])) ? getArtistID($opts) : '%';
    $sub_group_by = (isset($opts['sub_group_by']) && $opts['sub_group_by'] === 'album') ? "GROUP BY " . TBL_artists . ".`album_id`" : ((isset($opts['sub_group_by']) && $opts['sub_group_by'] === 'artist') ? "GROUP BY " . TBL_artists . ".`artist_id`" : "GROUP BY " . TBL_artists . ".`id`");
    $from = !empty($opts['from']) ? ', ' . $opts['from'] : '';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : TBL_user . '.`id`';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : '1970-00-00';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC';
    $select = !empty($opts['select']) ? ', ' . $opts['select'] : '';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT count(*) AS `count`,
                   " . TBL_user . ".`username` AS `username`,
                   " . TBL_user . ".`id` AS `user_id`,
                   " . TBL_album . ".`album_name` AS `album_name`,
                   " . TBL_album . ".`id` AS `album_id`,
                   " . TBL_album . ".`year` AS `year`,
                   " . TBL_listening . ".`date` AS `date`
                  " . $ci->db->escape_str($select) . "
            FROM " . TBL_album . ", 
                 " . TBL_artist . ", 
                 (SELECT " . TBL_artists . ".`artist_id`,
                         " . TBL_artists . ".`album_id`
                  FROM " . TBL_artists . "
                  " . $sub_group_by . ") AS " . TBL_artists . ",
                 " . TBL_listening . ", 
                 " . TBL_user . "
                 " . $ci->db->escape_str($from) . "
            WHERE " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_artists . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_user . ".`username` LIKE ?
              AND " . TBL_artist . ".`id` LIKE ?
              AND " . TBL_album . ".`id` LIKE ?
              " . $ci->db->escape_str($where) . "
            GROUP BY " . $ci->db->escape_str($group_by) . "
            ORDER BY " . $ci->db->escape_str($order_by) . "
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $username, $artist_id, $album_id));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}

/**
  * Returns cumulative listeners for given artist or album.
  *
  * @param array $opts.
  *          'album_name'      => Album name
  *          'artist_name'     => Artist name
  *          'no_content'      => Output format
  *          'username'        => Username
  *
  * @return string JSON encoded data containing album information.
  */
if (!function_exists('getListeningsCumulative')) {
  function getListeningsCumulative($opts = array()) {
    $ci = &get_instance();
    $ci->load->database();

    $ci->load->helper(array('id_helper'));

    $album_id = isset($opts['album_name']) ? getAlbumID($opts) : '%';
    $artist_id = (isset($opts['artist_name']) && !isset($opts['album_name'])) ? getArtistID($opts) : '%';
    $user_id = !empty($opts['username']) ? getUserID($opts) : '%';

    if ($album_id !== '%' || $artist_id !== '%') {
      $sql = "SELECT `line_date`,
                     SUM(`month_count`) OVER (ORDER BY `line_date` ASC) AS `cumulative_count`
              FROM (
                SELECT DATE_FORMAT(" . TBL_listening . ".`date`, '%Y%m') AS `line_date`,
                       COUNT(*) AS `month_count`
                FROM " . TBL_listening . ",
                     " . TBL_album . ",
                     " . TBL_artist . "
                WHERE " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
                  AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
                  AND " . TBL_listening . ".`album_id` LIKE ?
                  AND " . TBL_artist . ".`id` LIKE ?
                  AND " . TBL_listening . ".`user_id` LIKE ?
                  AND MONTH(" . TBL_listening . ".`date`) <> 0
                GROUP BY `line_date`
              ) AS `monthly`
              ORDER BY `line_date` ASC";
      $query = $ci->db->query($sql, array($album_id, $artist_id, $user_id));
    } elseif (isset($username) && $username !== '%') {
      $sql = "SELECT `line_date`,
                     SUM(`month_count`) OVER (ORDER BY `line_date` ASC) AS `cumulative_count`
              FROM (
                SELECT DATE_FORMAT(" . TBL_listening . ".`date`, '%Y%m') AS `line_date`,
                       COUNT(*) AS `month_count`
                FROM " . TBL_listening . "
                WHERE " . TBL_listening . ".`user_id` = ?
                  AND MONTH(" . TBL_listening . ".`date`) <> 0
                GROUP BY `line_date`
              ) AS `monthly`
              ORDER BY `line_date` ASC";
      $query = $ci->db->query($sql, array($user_id));
    } else {
      $sql = "SELECT `line_date`,
                     SUM(`month_count`) OVER (ORDER BY `line_date` ASC) AS `cumulative_count`
              FROM (
                SELECT DATE_FORMAT(" . TBL_listening . ".`date`, '%Y%m') AS `line_date`,
                       COUNT(*) AS `month_count`
                FROM " . TBL_listening . "
                WHERE MONTH(" . TBL_listening . ".`date`) <> 0
                GROUP BY `line_date`
              ) AS `monthly`
              ORDER BY `line_date` ASC";
      $query = $ci->db->query($sql);
    }

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    $result = _json_return_helper($query, $no_content);

    return $result;
  }
}

/**
  * Gets artist's albums with listening count.
  *
  * @param array $opts.
  *          'artist_name'     => Artist name
  *          'no_content'      => Output format
  *          'order_by'        => Order by argument
  *          'username'        => Username
  *
  * @return array Album information or boolean FALSE.
  *
  */
if (!function_exists('getArtistAlbums')) {
  function getArtistAlbums($opts = array()) {
    $ci = &get_instance();
    $ci->load->database();

    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : '%';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, `albums`.`year` DESC';
    $username = !empty($opts['username']) ? $opts['username'] : '%';

    $sql = "SELECT " . TBL_artist . ".`artist_name`,
                   " . TBL_artist . ".`id` AS `artist_id`,
                   `albums`.`album_name`,
                   `albums`.`year`, 
                   `albums`.`spotify_id`, 
                   `albums`.`id` AS `album_id`,
                   COALESCE(t.`count`, 0) AS `count`
            FROM " . TBL_artist . ",
                 " . TBL_artists . ",
                 " . TBL_album . " `albums`
            LEFT JOIN (
                SELECT count(*) AS `count`, 
                       " . TBL_album . ".`id` AS `album_id`
                FROM " . TBL_album . ",
                     " . TBL_listening . ",
                     " . TBL_user . "
                WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
                  AND " . TBL_user . ".`id` = " . TBL_listening . ".`user_id`
                  AND " . TBL_user . ".`username` LIKE ?
                GROUP BY " . TBL_album . ".`id`)
             `t` ON `albums`.`id` = `t`.`album_id`
            WHERE " . TBL_artists . ".`album_id` = `albums`.`id`
              AND " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_artist . ".`artist_name` LIKE ?
            ORDER BY " . $ci->db->escape_str($order_by);

    $query = $ci->db->query($sql, array($username, $artist_name));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    $result = _json_return_helper($query, $no_content);

    return $result;
  }
}

/**
  * Gets album's artists.
  *
  * @param array $opts.
  *          'album_id'        => Album ID
  *          'no_content'      => Output format
  *
  * @return string JSON encoded data containing artists' information.
  *
  */
if (!function_exists('getAlbumArtists')) {
  function getAlbumArtists($opts = array()) {
    $ci = &get_instance();
    $ci->load->database();

    $album_id = isset($opts['album_id']) ? $opts['album_id'] : FALSE;
    if ($album_id === FALSE) {
      return FALSE;
    }

    $sql = "SELECT " . TBL_artist . ".`id`,
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_artist . ".`spotify_id`,
                   " . TBL_artist . ".`created`,
                   " . TBL_artist . ".`user_id`
            FROM " . TBL_artist . ",
                 " . TBL_artists . "
            WHERE " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_artists . ".`album_id` = ?
            ORDER BY " . TBL_artist . ".`artist_name` ASC";

    $query = $ci->db->query($sql, array($album_id));
    $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE;

    return $result;
  }
}

/**
  * Get multiple albums' artists in a single query, instead of calling
  * getAlbumArtists() once per album in a loop.
  *
  * @param array $album_ids Album IDs.
  *
  * @return array Map of album_id => array of artist rows (empty array for
  *               an album with no matching rows).
  */
if (!function_exists('getAlbumsArtists')) {
  function getAlbumsArtists($album_ids = array()) {
    $ci = &get_instance();
    $ci->load->database();

    $album_ids = array_unique(array_filter($album_ids));
    if (empty($album_ids)) {
      return array();
    }

    $placeholders = implode(',', array_fill(0, count($album_ids), '?'));
    $sql = "SELECT " . TBL_artists . ".`album_id`,
                   " . TBL_artist . ".`id`,
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_artist . ".`spotify_id`,
                   " . TBL_artist . ".`created`,
                   " . TBL_artist . ".`user_id`
            FROM " . TBL_artist . ",
                 " . TBL_artists . "
            WHERE " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_artists . ".`album_id` IN (" . $placeholders . ")
            ORDER BY " . TBL_artist . ".`artist_name` ASC";

    $query = $ci->db->query($sql, array_values($album_ids));

    $result = array();
    foreach ($query->result_array() as $row) {
      $result[$row['album_id']][] = $row;
    }

    return $result;
  }
}

/**
  * Get logged in user's similarity with current profile.
  *
  * @param array $opts.
  *          'user_id'     => User ID
  *          'profile_id'  => Profile ID
  *
  * @return array user's similarity information.
  *
  */
if (!function_exists('getUserSimilarity')) {
  function getUserSimilarity($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    function _filter_artist_name($data) {
      return $data->artist_name;
    }

    $profile_artists = json_decode(getArtists(array(
      'lower_limit' => '1970-00-00',
      'limit' => 200,
      'username' => $opts['username']
    )));

    $user_artists = json_decode(getArtists(array(
      'lower_limit' => '1970-00-00',
      'limit' => 200,
      'username' => $ci->session->userdata('username')
    )));

    if ($profile_artists && $user_artists) {
      $profile_top = array_map('_filter_artist_name', $profile_artists);
      $user_top = array_map('_filter_artist_name', $user_artists);
      $similar_artists = array_intersect($profile_top, $user_top);
      $similarity_value = 4 * count($similar_artists) / (count($profile_top) + count($user_top));
      if ($similarity_value > 0.75) { // Super.
        $similarity_text = 'Super';
      }
      else if ($similarity_value > 0.6) { // High.
        $similarity_text = 'High';
      }
      else if ($similarity_value > 0.4) { // Moderate.
        $similarity_text = 'Moderate';
      }
      else if ($similarity_value > 0.25) { // Low.
        $similarity_text = 'Low';
      }
      else if ($similarity_value > 0.15) { // Very low.
        $similarity_text = 'Very low';
      }
      else { // Not existing.
        $similarity_text = 'Marginal';
      }
      function _artist_anchors($artist_name) {
        return anchor(array('artist', url_title($artist_name)), $artist_name);
      }

      return array(
        'artists' => array_map('_artist_anchors', array_slice($similar_artists, 0, 3)),
        'text' => $similarity_text,
        'value' => $similarity_value * 100
      );
    }
    else {
      return array(
        'artists' => [],
        'text' => 'Zero',
        'value' => 0
      );
    }
  }
}

/**
  * Get average age of album listened
  *
  * @param array $opts.
  *          'username' => Username
  *          'year'     => Album year
  *
  * @return array average age.
  *
  */
if (!function_exists('getAlbumAverageAge')) {
  function getAlbumAverageAge($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : '1970-00-00';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : '';
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT YEAR(" . TBL_listening . ".`date`) AS `bar_date`,
                   ROUND(AVG(YEAR(" . TBL_listening . ".`date`) - " . TBL_album . ".`year`), 1) AS `count`,
                   ROUND(AVG(" . TBL_album . ".`year`), 1) AS `average_year`
            FROM " . TBL_listening . ",
                 " . TBL_album . ",
                 " . TBL_user . "
            WHERE " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_user . ".`username` LIKE ?
            $group_by
            HAVING `average_year` IS NOT NULL
            ORDER BY YEAR(" . TBL_listening . ".`date`) ASC";
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $username));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}

/**
  * Get average listenings per year
  *
  * @param array $opts.
  *          'username' => Username
  *          'year'     => Album year
  *
  * @return array average age.
  *
  */
if (!function_exists('getListeningsPerYear')) {
  function getListeningsPerYear($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $ci->load->helper(array('id_helper'));

    $album_id = isset($opts['album_name']) ? getAlbumID($opts) : '%';
    $artist_id = (isset($opts['artist_name']) && !isset($opts['album_name'])) ? getArtistID($opts) : '%';
    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $sub_group_by = (isset($opts['sub_group_by']) && $opts['sub_group_by'] === 'album') ? "GROUP BY " . TBL_artists . ".`album_id`" : ((isset($opts['sub_group_by']) && $opts['sub_group_by'] === 'artist') ? "GROUP BY " . TBL_artists . ".`artist_id`" : "GROUP BY " . TBL_artists . ".`id`");
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] :  TBL_artist . '.`id`';
    // "Today" as a bound param (PHP, now browser-tz-aware) rather than
    // MySQL's own NOW()/CURRENT_DATE(), which runs in the DB server's
    // own timezone independent of the browser.
    $today = date('Y-m-d');
    $sql = "SELECT (
      (
        SELECT count(*) AS `count`
        FROM " . TBL_album . ",
             " . TBL_artist . ",
            (SELECT " . TBL_artists . ".`artist_id`,
                    " . TBL_artists . ".`album_id`
                FROM " . TBL_artists . "
                " . $sub_group_by . ") AS " . TBL_artists . ",
             " . TBL_listening . " 
        WHERE " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
          AND " . TBL_artists . ".`album_id` = " . TBL_album . ".`id`
          AND " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
          AND " . TBL_listening . ".`user_id` LIKE ?
          AND " . TBL_artist . ".`id` LIKE ?
          AND " . TBL_album . ".`id` LIKE ?
        GROUP BY " . $ci->db->escape_str($group_by) . "
        LIMIT 1
      )
      / 
      (
       SELECT DATEDIFF(?, (
          SELECT " . TBL_listening . ".`date`
          FROM " . TBL_album . ",
               " . TBL_artist . ",
              (SELECT " . TBL_artists . ".`artist_id`,
                      " . TBL_artists . ".`album_id`
                  FROM " . TBL_artists . "
                  " . $sub_group_by . ") AS " . TBL_artists . ",
               " . TBL_listening . "
          WHERE " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
            AND " . TBL_artists . ".`album_id` = " . TBL_album . ".`id`
            AND " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
            AND " . TBL_listening . ".`user_id` LIKE ?
            AND " . TBL_artist . ".`id` LIKE ?
            AND " . TBL_album . ".`id` LIKE ?
            AND YEAR(" . TBL_listening . ".`date`) <> YEAR(?)
          ORDER BY " . TBL_listening . ".`date` ASC
          LIMIT 1)
        ) / 365
      )
    ) AS `count`";
    $query = $ci->db->query($sql, array($user_id, $artist_id, $album_id, $today, $user_id, $artist_id, $album_id, $today));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}

/**
  * Helper function for sorting tags
  */
if (!function_exists('_tagsSortByCount')) {
  function _tagsSortByCount($a, $b) {
    if ($a->count == $b->count) {
      return 0;
    }
    return ($a->count > $b->count) ? -1 : 1;
  }
}
?>