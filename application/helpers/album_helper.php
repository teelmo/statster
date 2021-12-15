<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Add new album.
  *
  * @param array $opts.
  *          'album_info'   => Album info containing year
  *          'artist_name'  => Artist name
  *          'user_id'      => User ID
  *
  * @return array Album ID or boolean FALSE.
  */
if (!function_exists('addAlbum')) {
  function addAlbum($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $data['album_info'] = isset($opts['album_name']) ? ucwords($opts['album_name']) : '';
    $data['artist_id'] = isset($opts['artist_name']) ? getArtistID($opts) : '';
    $data['artist_name'] = $opts['artist_name'];
    $data['user_id'] = !empty($opts['user_id']) ? $opts['user_id'] : '';
    if (empty($data['artist_id'])) {
      if (!$data['artist_id'] = addArtist($data)) {
        return FALSE;
      }
    }
    preg_match('/(.*)\(([0-9]{4})\)/', $data['album_info'], $matches);
    $data['album_name'] = ucwords(trim(str_replace(' ', '', $matches[1])));
    $data['album_year'] = trim(str_replace(' ', ' ', $matches[2]));

    if (!empty($data['album_name']) && (intval($data['album_year']) > 1900 && intval($data['album_year']) < (CUR_YEAR + 1))) {
      $sql = "INSERT
                INTO " . TBL_album . " (`artist_id`, `user_id`, `album_name`, `year`, `created`)
                VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP())";
      $query = $ci->db->query($sql, array($data['artist_id'], $data['user_id'], $data['album_name'], $data['album_year']));
      if ($ci->db->affected_rows() === 1) {
        $data['album_id'] = $ci->db->insert_id();

        $sql = "INSERT
                  INTO " . TBL_artists . " (`artist_id`, `album_id`, `user_id`, `created`)
                  VALUES (?, ?, ?, CURRENT_TIMESTAMP())";
        $query = $ci->db->query($sql, array($data['artist_id'], $data['album_id'], $data['user_id']));

        // Load helpers.
        $ci->load->helper(array('keyword_helper', 'artist_helper', 'nationality_helper', 'lastfm_helper'));
        // Add decade keyword.
        $data['tag_name'] = (floor((int)$data['album_year'] / 10) * 10) . 's';
        $data['tag_id'] = getKeywordID($data);
        addAlbumKeyword($data);
        // Add nationality information.
        $nationalities = getArtistNationalities(array('artist_id' => $data['artist_id']));
        if (!empty($nationalities)) {
          foreach ($nationalities as $key => $nationality) {
            addAlbumNationality(array('album_id' => $data['album_id'], 'tag_id' => $nationality->tag_id));
          }
        }
        else {
          addAlbumNationality(array('album_id' => $data['album_id'], 'tag_id' => '242'));
        }
        // Add Spotify resource.
        getSpotifyResourceId($data);
        // Get album img and bio.
        fetchAlbumInfo($data, array('bio', 'image'));
        // Return album ID.
        return $data['album_id'];
      }
    }
    return FALSE;
  }
}

/**
  * Gets album's info.
  *
  * @param array $opts.
  *          'album_name'   => Album name
  *          'artist_name'  => Artist name
  *
  * @return array Album information or boolean FALSE.
  */
if (!function_exists('getAlbumInfo')) {
  function getAlbumInfo($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    // Load helpers.
    $ci->load->helper(array('id_helper'));

    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : ((isset($opts['artist_name']) && isset($opts['album_name'])) ? getAlbumID($opts) : FALSE);

    if ($album_id !== FALSE) {
      $sql = "SELECT " . TBL_artist . ".`id` as `artist_id`,
                     " . TBL_album . ".`id` as `album_id`,
                     " . TBL_artist . ".`artist_name`,
                     " . TBL_album . ".`album_name`,
                     " . TBL_album . ".`year`,
                     " . TBL_album . ".`spotify_id`,
                     YEAR(" . TBL_album . ".`created`) as `created`
              FROM " . TBL_artist . ",
                   " . TBL_artists . ",
                   " . TBL_album . "
              WHERE " . TBL_album . ".`id` = " . TBL_artists . ".`album_id`
                AND " . TBL_artist . ".`id` = " . TBL_artists . ".`artist_id`
                AND " . TBL_album . ".`id` = ?
              ORDER BY " . TBL_artist . ".`artist_name` ASC";
      $query = $ci->db->query($sql, array($album_id));

      return ($query->num_rows() > 0) ? $query->result_array() : FALSE;
      // return _json_return_helper($query, $human_readable);
    }
    else {
      return FALSE;
    }
  }
}

/**
  * Gets album's bio.
  *
  * @param array $opts.
  *          'album_id'  => Album ID
  *
  * @return array Album bio
  */
if (!function_exists('getAlbumBio')) {
  function getAlbumBio($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : '';
    $sql = "SELECT " . TBL_album_biography . ".`id` as `biography_id`,
                   " . TBL_album_biography . ".`summary` as `bio_summary`,
                   " . TBL_album_biography . ".`text` as `bio_content`,
                   " . TBL_album_biography . ".`updated` as `bio_updated`,
                   'false' as `update_bio`
            FROM " . TBL_album_biography . "
            WHERE " . TBL_album_biography . ".`album_id` = ?";
    $query = $ci->db->query($sql, array($album_id));
    return ($query->num_rows() > 0) ? $query->result_array()[0] : array('update_bio' => false);
  }
}

/**
  * Add artist's bio.
  *
  * @param array $opts.
  *          'album_id'     => Album ID
  *          'bio_summary'  => Bio summary
  *          'bio_content'  => Bio content
  *
  * @return retun boolean TRUE or FALSE
  */
if (!function_exists('addAlbumBio')) {
  function addAlbumBio($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : '';
    $summary = !empty($opts['bio_summary']) ? $opts['bio_summary'] : '';
    $text = !empty($opts['bio_content']) ? $opts['bio_content'] : '';

    $sql = "SELECT  " . TBL_album_biography . ".`id`
            FROM " . TBL_album_biography . "
            WHERE " . TBL_album_biography . ".`album_id` = ?";
    $query = $ci->db->query($sql, array($album_id));
    if ($query->num_rows() === 1) {
      $sql = "UPDATE " . TBL_album_biography . "
                SET " . TBL_album_biography . ".`summary` = ?,
                    " . TBL_album_biography . ".`text` = ?,
                    " . TBL_album_biography . ".`updated` = NOW()
                WHERE " . TBL_album_biography . ".`album_id` = ?";
      $query = $ci->db->query($sql, array($summary, $text, $album_id));
    }
    else {
      $sql = "INSERT
                INTO " . TBL_album_biography . " (`album_id`, `summary`, `text`)
                VALUES (?, ?, ?)";
      $query = $ci->db->query($sql, array($album_id, $summary, $text));
    }
    return ($ci->db->affected_rows() === 1);
  }
}


/**
   * Gets album's listenings.
   *
   * @param array $opts.
   *          'album_id'  => Album ID
   *          'user_id'   => User ID
   *
   * @return array Listening information.
   *
   */
if (!function_exists('getAlbumListenings')) {
  function getAlbumListenings($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : '%';
    $count_type = !empty($opts['user_id']) ? 'user_count': 'total_count';
    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $sql = "SELECT count(" . TBL_album . ".`id`) as `" . $count_type . "`
            FROM " . TBL_album . ",
                 " . TBL_listening . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` LIKE ?
              AND " . TBL_album . ".`id` LIKE ?";
    $query = $ci->db->query($sql, array($user_id, $album_id));
    return ($query->num_rows() > 0) ? $query->result_array()[0] : array($count_type => 0);
  }
}

/**
  * Returns listeners for given artist or album.
  *
  * @param array $opts.
  *          'album_id'        => Album id
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
if (!function_exists('getAlbumListeners')) {
  function getAlbumListeners($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $album_id = isset($opts['album_id']) ? $opts['album_id'] : '';
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
                 " . TBL_listening . ", 
                 " . TBL_user . "
                 " . $ci->db->escape_str($from) . "
            WHERE " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_user . ".`username` LIKE ?
              AND " . TBL_album . ".`id` = ?
              " . $ci->db->escape_str($where) . "
            GROUP BY " . $ci->db->escape_str($group_by) . "
            ORDER BY " . $ci->db->escape_str($order_by) . "
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $username, $album_id));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
   * Gets album's tags (genres and keywords).
   *
   * @param array $opts.
   *          'album_id'  => Album ID
   *          'user_id'   => User ID
   *
   * @return array Tag information.
   *
   */
if (!function_exists('getAlbumTags')) {
  function getAlbumTags($opts = array()) {
    $tags_array = array();
    $tags_array[] = getAlbumNationalities($opts);
    $tags_array[] = getAlbumGenres($opts);
    $tags_array[] = getAlbumKeywords($opts);

    if (is_array($tags_array)) {
      $data = array();
      foreach ($tags_array as $idx => $tags) {
        foreach ($tags as $idx => $tag) {
          $data['tags'][] = $tag;
        }
      }
      if (isset($opts['sort'])) {
        uasort($data['tags'], '_tagsSortByCount');
      }
      $data['tags'] = array_slice($data['tags'], 0, empty($opts['limit']) ? 8 : $opts['limit']);
      return json_encode($data['tags']);
    }
    return array();
  }
}

/**
   * Gets album's genres.
   *
   * @param array $opts.
   *          'album_id'  => Album ID
   *
   * @return array album's Keyword information.
   *
   */
if (!function_exists('getAlbumGenres')) {
  function getAlbumGenres($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : '%';
    $sql = "SELECT count(" . TBL_genre . ".`id`) as `count`,
                   " . TBL_genre . ".`name`,
                   " . TBL_genre . ".`id` as `tag_id`,
                   'genre' as `type`,
                   GROUP_CONCAT(DISTINCT(" . TBL_genres . ".`user_id`)) `user_ids`
            FROM " . TBL_genre . ",
                 " . TBL_genres . ",
                 " . TBL_album . "
            WHERE " . TBL_album . ".`id` = " . TBL_genres . ".`album_id`
              AND " . TBL_genre . ".`id` = " . TBL_genres . ".`genre_id`
              AND " . TBL_album . ".`id` LIKE ?
            GROUP BY " . TBL_genre . ".`id`
            ORDER BY `count` DESC";
    $query = $ci->db->query($sql, array($album_id));
    return ($query->num_rows() > 0) ? $query->result() : array();
  }
}

/**
   * Gets album's keywords.
   *
   * @param array $opts.
   *          'album_id'  => Album ID
   *
   * @return array album's Keyword information.
   *
   */
if (!function_exists('getAlbumKeywords')) {
  function getAlbumKeywords($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : '%';
    $sql = "SELECT count(" . TBL_keyword . ".`id`) as `count`,
                   " . TBL_keyword . ".`name`,
                   " . TBL_keyword . ".`id` as `tag_id`,
                   'keyword' as `type`,
                   GROUP_CONCAT(DISTINCT(" . TBL_keywords . ".`user_id`)) `user_ids`
            FROM " . TBL_keyword . ",
                 " . TBL_keywords . ",
                 " . TBL_album . "
            WHERE " . TBL_album . ".`id` = " . TBL_keywords . ".`album_id`
              AND " . TBL_keyword . ".`id` = " . TBL_keywords . ".`keyword_id`
              AND " . TBL_album . ".`id` LIKE ?
            GROUP BY " . TBL_keyword . ".`id`
            ORDER BY `count` DESC";
    $query = $ci->db->query($sql, array($album_id));
    return ($query->num_rows() > 0) ? $query->result() : array();
  }
}

/**
   * Gets album's nationalities.
   *
   * @param array $opts.
   *          'album_id'  => Album ID
   *
   * @return array album's Nationality information.
   *
   */
if (!function_exists('getAlbumNationalities')) {
  function getAlbumNationalities($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : '%';
    $sql = "SELECT count(" . TBL_nationality . ".`id`) as `count`,
                   " . TBL_nationality . ".`country` as `name`,
                   " . TBL_nationality . ".`country_code`,
                   " . TBL_nationality . ".`id` as `tag_id`,
                   'nationality' as `type`,
                   GROUP_CONCAT(DISTINCT(" . TBL_nationalities . ".`user_id`)) `user_ids`
            FROM " . TBL_nationality . ",
                 " . TBL_nationalities . ",
                 " . TBL_album . "
            WHERE " . TBL_album . ".`id` = " . TBL_nationalities . ".`album_id`
              AND " . TBL_nationality . ".`id` = " . TBL_nationalities . ".`nationality_id`
              AND " . TBL_album . ".`id` LIKE ?
            GROUP BY " . TBL_nationality . ".`id`
            ORDER BY `count` DESC";
    $query = $ci->db->query($sql, array($album_id));
    return ($query->num_rows() > 0) ? $query->result() : array();
  }
}

/**
   * Edit album info.
   *
   * @param array $opts.
   *          'album_id'  => Album ID
   *          'artist_id'  => Artist ID
   *          'album_name'  => Artist Name
   *          'year'  => Release year
   *          'spotify_id'  => Spotify ID
   *
   * @return boolean TRUE or FALSE.
   *
   */
if (!function_exists('updateAlbum')) {
  function updateAlbum($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    // Load helpers.
    $ci->load->helper(array('id_helper'));

    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : '';
    $album_name = isset($opts['album_name']) ? trim(str_replace(' ', '', $opts['album_name'])) : FALSE;
    $artist_id = isset($opts['artist_name']) ? getArtistID($opts) : '';
    $spotify_id = !empty($opts['spotify_id']) ? $opts['spotify_id'] : '';
    $year = !empty($opts['year']) ? trim(str_replace(' ', ' ', $opts['year'])) : '';

    if ($album_name !== FALSE) {
      $sql = "UPDATE " . TBL_album . "
                SET " . TBL_album . ".`album_name` = ?,
                    " . TBL_album . ".`artist_id` = ?,
                    " . TBL_album . ".`year` = ?,
                    " . TBL_album . ".`spotify_id` = ?
                WHERE " . TBL_album . ".`id` = ?";

      $query = $ci->db->query($sql, array($album_name, $artist_id, $year, $spotify_id, $album_id));
      return ($ci->db->affected_rows() === 1) ? TRUE : FALSE;
    }
    else {
      return FALSE;
    }
  }
}
?>