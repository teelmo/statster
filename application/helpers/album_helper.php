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
    $data['album_name'] = ucwords(trim(str_replace(' ', ' ', $matches[1])));
    $data['album_year'] = trim(str_replace(' ', ' ', $matches[2]));

    if (!empty($data['album_name']) && (intval($data['album_year']) > 1900 && intval($data['album_year']) < (CUR_YEAR + 1))) {
      $sql = "INSERT
                INTO " . TBL_album . " (`artist_id`, `user_id`, `album_name`, `year`, `created`)
                VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP())";
      $query = $ci->db->query($sql, array($data['artist_id'], $data['user_id'], $data['album_name'], $data['album_year']));
      if ($ci->db->affected_rows() === 1) {
        $data['album_id'] = $ci->db->insert_id();
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

    $album_name = isset($opts['album_name']) ? $opts['album_name'] : FALSE;
    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : FALSE;
    if ($artist_name !== FALSE && $album_name !== FALSE) {
      $sql = "SELECT " . TBL_artist . ".`id` as `artist_id`,
                     " . TBL_album . ".`id` as `album_id`,
                     " . TBL_artist . ".`artist_name`,
                     " . TBL_album . ".`album_name`,
                     " . TBL_album . ".`year`,
                     " . TBL_album . ".`spotify_uri`,
                     YEAR(" . TBL_album . ".`created`) as `created`
              FROM " . TBL_artist . ",
                   " . TBL_album . "
              WHERE ".TBL_album.".`artist_id` = " . TBL_artist . ".`id`
                AND " . TBL_artist . ".`artist_name` = ?
                AND " . TBL_album . ".`album_name` = ?";
      $query = $ci->db->query($sql, array($artist_name, $album_name));
    }
    else {
      $album_id = !empty($opts['album_id']) ? $opts['album_id'] : FALSE;
      $sql = "SELECT " . TBL_artist . ".`id` as `artist_id`,
                     " . TBL_album . ".`id` as `album_id`,
                     " . TBL_artist . ".`artist_name`,
                     " . TBL_album . ".`album_name`,
                     " . TBL_album . ".`year`,
                     " . TBL_album . ".`spotify_uri`,
                     YEAR(" . TBL_album . ".`created`) as `created`
              FROM " . TBL_artist . ",
                   " . TBL_album . "
              WHERE ".TBL_album.".`artist_id` = " . TBL_artist . ".`id` 
                AND " . TBL_album . ".`id` = ?";
      $query = $ci->db->query($sql, array($album_id));
    }
    return ($query->num_rows() > 0) ? $query->result_array()[0] : FALSE;
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
   *          'spotify_uri'  => Spotify URI
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
    $album_name = isset($opts['album_name']) ? $opts['album_name'] : FALSE;
    $artist_id = isset($opts['artist_name']) ? getArtistID($opts) : '';
    $spotify_uri = !empty($opts['spotify_uri']) ? $opts['spotify_uri'] : '';
    $year = !empty($opts['year']) ? $opts['year'] : '';

    if ($album_name !== FALSE) {
      $sql = "UPDATE " . TBL_album . "
                SET " . TBL_album . ".`album_name` = ?,
                    " . TBL_album . ".`artist_id` = ?,
                    " . TBL_album . ".`year` = ?,
                    " . TBL_album . ".`spotify_uri` = ?
                WHERE " . TBL_album . ".`id` = ?";

      $query = $ci->db->query($sql, array($album_name, $artist_id, $year, $spotify_uri, $album_id));
      return ($ci->db->affected_rows() === 1) ? TRUE : FALSE;
    }
    else {
      return FALSE;
    }
  }
}
?>