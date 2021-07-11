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
  function getListeningCount($opts = array(), $type = '') {
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
                 " . TBL_listening . ",
                 " . TBL_user . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
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

    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : '%';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] :  TBL_artist . '.`id`';
    $having = !empty($opts['having']) ? 'HAVING ' . $opts['having'] : '';
    $limit = !empty($opts['limit']) ? $opts['limit'] : '10';
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, `artist_name` ASC';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT count(*) AS `count`,
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
            FROM " . TBL_album . ",
                 " . TBL_artist . ",
                 " . TBL_listening . ",
                 " . TBL_user . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_artist . ".`artist_name` LIKE ?
              AND " . TBL_user . ".`username` LIKE ?
              " . $ci->db->escape_str($where) . "
            GROUP BY " . $ci->db->escape_str($group_by) . ",
                     " . TBL_user . ".`id`
            " . $ci->db->escape_str($having) . "
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
  *          'having'          => Custom having argument
  *          'human_readable'  => Output format
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
    $sql = "SELECT count(*) AS `count`,
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
            FROM " . TBL_album . ",
                 " . TBL_artist . ",
                 " . TBL_listening . ",
                 " . TBL_user . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_user . ".`username` LIKE ?
              AND " . TBL_artist . ".`artist_name` LIKE ?
              AND " . TBL_album . ".`album_name` LIKE ?
              " . $ci->db->escape_str($where) . "
            GROUP BY " . $ci->db->escape_str($group_by) . "
            " . $ci->db->escape_str($having) . "
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
  *          'where'           => Custom where argument
  *
  * @return string JSON encoded data containing album information.
  */
if (!function_exists('getListeners')) {
  function getListeners($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $album_name = isset($opts['album_name']) ? $opts['album_name'] : '%';
    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : '%';
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
                   " . TBL_artist . ".`artist_name` AS `artist_name`,
                   " . TBL_artist . ".`id` AS `artist_id`,
                   " . TBL_album . ".`album_name` AS `album_name`,
                   " . TBL_album . ".`id` AS `album_id`,
                   " . TBL_album . ".`year` AS `year`,
                   " . TBL_listening . ".`date` AS `date`
                  " . $ci->db->escape_str($select) . "
            FROM " . TBL_album . ", 
                 " . TBL_artist . ", 
                 " . TBL_listening . ", 
                 " . TBL_user . "
                 " . $ci->db->escape_str($from) . "
            WHERE " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_user . ".`username` LIKE ?
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
  * Returns cumulative listeners for given artist or album.
  *
  * @param array $opts.
  *          'album_name'      => Album name
  *          'artist_name'     => Artist name
  *          'username'        => Username
  *
  * @return string JSON encoded data containing album information.
  */
if (!function_exists('getListeningsCumulative')) {
  function getListeningsCumulative($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $album_name = isset($opts['album_name']) ? $opts['album_name'] : '%';
    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : '%';
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT DATE_FORMAT(`date`, '%Y%m') AS `line_date`,
                   (SELECT COUNT(*) 
                    FROM " . TBL_listening . "
                    WHERE `date` <= MAX(`a`.`date`)) AS `cumulative_count`
            FROM " . TBL_listening . " AS `a`,
                 " . TBL_user . ",
                 " . TBL_album . ", 
                 " . TBL_artist . "
            WHERE `a`.`album_id` = " . TBL_album . ".`id`
              AND `a`.`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_user . ".`username` LIKE ?
              AND " . TBL_artist . ".`artist_name` LIKE ?
              AND " . TBL_album . ".`album_name` LIKE ?
              AND MONTH(`a`.`date`) <> 0
            GROUP BY `line_date`
            ORDER BY `line_date` ASC";
    $query = $ci->db->query($sql, array($username, $artist_name, $album_name));

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
  *          'order_by'        => Order by argument
  *          'username'        => Username
  *
  * @return array Album information or boolean FALSE.
  *
  */
if (!function_exists('getArtistAlbums')) {
  function getArtistAlbums($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : '%';
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, `albums`.`year` DESC';
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT " . TBL_artist . ".`artist_name`,
                   `albums`.`album_name`,
                   `albums`.`year`, 
                   `albums`.`spotify_id`, 
                   " . TBL_artist . ".`id` AS `artist_id`,
                   `albums`.`id` AS `album_id`,
                   COALESCE(t.`count`, 0) AS `count`
            FROM " . TBL_artist . ",
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
            WHERE " . TBL_artist . ".`id` = `albums`.`artist_id`
              AND " . TBL_artist . ".`artist_name` LIKE ?
            ORDER BY " . $ci->db->escape_str($order_by);

    $query = $ci->db->query($sql, array($username, $artist_name));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
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
      else if ($similarity_value > 0.15) { // Very low
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