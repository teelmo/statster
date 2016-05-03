<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Add new album.
  *
  * @param array $opts.
  *          'artist_name'  => Artist name
  *          'user_id'      => User ID
  *
  * @return array Artist ID or boolean FALSE.
  */
if (!function_exists('addArtist')) {
  function addArtist($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    // Load helpers.
    $data['artist_name'] = !empty($opts['artist_name']) ? ucwords($opts['artist_name']) : '';
    $data['user_id'] = !empty($opts['user_id']) ? $opts['user_id'] : '';

    if (!empty($data['artist_name'])) {
      $sql = "INSERT
                INTO " . TBL_artist . " (`user_id`, `artist_name`, `created`)
                VALUES (?, ?, CURRENT_TIMESTAMP())";
      $query = $ci->db->query($sql, array($data['user_id'], $data['artist_name']));
      if ($ci->db->affected_rows() === 1) {
        return $ci->db->insert_id();
      }
    }
    return FALSE;
  }
}

/**
  * Gets artist's info.
  *
  * @param array $opts.
  *          'artist_name'  => Artist name
  *
  * @return array Artist information or boolean FALSE.
  */
if (!function_exists('getArtistInfo')) {
  function getArtistInfo($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $sql = "SELECT " . TBL_artist . ".`id` as `artist_id`,
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_artist . ".`spotify_uri`,
                   YEAR(" . TBL_artist . ".`created`) as `created`
            FROM " . TBL_artist . "
            WHERE " . TBL_artist . ".`artist_name` LIKE ?";
    $query = $ci->db->query($sql, array($artist_name));
    return ($query->num_rows() > 0) ? ${!${false}=$query->result_array()}[0] : FALSE;
  }
}

/**
  * Gets artist's bio.
  *
  * @param array $opts.
  *          'artist_id'  => Artist ID
  *
  * @return array Artist bio
  */
if (!function_exists('getArtistBio')) {
  function getArtistBio($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_id = !empty($opts['artist_id']) ? $opts['artist_id'] : '';
    $sql = "SELECT " . TBL_artist_biography . ".`id` as `biography_id`,
                   " . TBL_artist_biography . ".`summary` as `bio_summary`, 
                   " . TBL_artist_biography . ".`text` as `bio_content`, 
                   " . TBL_artist_biography . ".`updated` as `bio_updated`,
                   'false' as `update_bio`
            FROM " . TBL_artist_biography . "
            WHERE " . TBL_artist_biography . ".`artist_id` = ?";
    $query = $ci->db->query($sql, array($artist_id));
    return ($query->num_rows() > 0) ? ${!${false}=$query->result_array()}[0] : array('update_bio' => false);
  }
}

/**
  * Add artist's bio.
  *
  * @param array $opts.
  *          'artist_id'    => Artist ID
  *          'bio_summary'  => Bio summary
  *          'bio_content'  => Bio content
  *
  * @return retun boolean TRUE or FALSE
  */
if (!function_exists('addArtistBio')) {
  function addArtistBio($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_id = !empty($opts['artist_id']) ? $opts['artist_id'] : '';
    $summary = !empty($opts['bio_summary']) ? $opts['bio_summary'] : '';
    $text = !empty($opts['bio_content']) ? $opts['bio_content'] : '';

    $sql = "SELECT  " . TBL_artist_biography . ".`id`
            FROM " . TBL_artist_biography . "
            WHERE " . TBL_artist_biography . ".`artist_id` = ?";
    $query = $ci->db->query($sql, array($artist_id));
    if ($query->num_rows() === 1) {
      $sql = "UPDATE " . TBL_artist_biography . "
                SET " . TBL_artist_biography . ".`summary` = ?,
                    " . TBL_artist_biography . ".`text` = ?
                WHERE " . TBL_artist_biography . ".`artist_id` = ?";
      $query = $ci->db->query($sql, array($summary, $text, $artist_id));
    }
    else {
      $sql = "INSERT
                INTO " . TBL_artist_biography . " (`artist_id`, `summary`, `text`)
                VALUES (?, ?, ?)";
      $query = $ci->db->query($sql, array($artist_id, $summary, $text));
    }
    return ($ci->db->affected_rows() === 1);
  }
}

/**
   * Gets artist's listenings.
   *
   * @param array $opts.
   *          'artist_id'  => Artist ID
   *          'user_id'    => User ID
   *
   * @return array Listening information.
   *
   */
if (!function_exists('getArtistListenings')) {
  function getArtistListenings($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_id = !empty($opts['artist_id']) ? $opts['artist_id'] : '%';
    $count_type = !empty($opts['user_id']) ? 'user_count': 'total_count';
    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $sql = "SELECT count(" . TBL_artist . ".`id`) as `" . $count_type . "`
            FROM " . TBL_artist . ",
                 " . TBL_album . ",
                 " . TBL_listening . "
            WHERE " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id`
              AND " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` LIKE ?
              AND " . TBL_artist . ".`id` LIKE ?";
    $query = $ci->db->query($sql, array($user_id, $artist_id));
    return ($query->num_rows() > 0) ? ${!${false}=$query->result_array()}[0] : array($count_type => 0);
  }
}

/**
   * Gets artist's tags (genres and keywords).
   *
   * @param array $opts.
   *          'artist_id'  => Artist ID
   *          'user_id'    => User ID
   *
   * @return array Tag information or boolean FALSE.
   *
   */
if (!function_exists('getArtistTags')) {
  function getArtistTags($opts = array()) {
    $tags_array = array();
    $tags_array[] = getArtistNationalities($opts);
    $tags_array[] = getArtistGenres($opts);
    $tags_array[] = getArtistKeywords($opts);

    if (is_array($tags_array)) {
      foreach ($tags_array as $idx => $tags) {
        foreach ($tags as $idx => $tag) {
          $data['tags'][] = $tag;
        }
      }
      if (isset($opts['sort'])) {
        uasort($data['tags'], '_tagsSortByCount');
      }
      uasort($data, '_tagsSortByCount');
      $data['tags'] = array_slice($data['tags'], 0, empty($opts['limit']) ? 8 : $opts['limit']);
      return json_encode($data['tags']);
    }
    return array();
  }
}

/**
   * Gets artist's genres.
   *
   * @param array $opts.
   *          'artist_id'  => Artist ID
   *
   * @return array artist's Keyword information.
   *
   */
if (!function_exists('getArtistGenres')) {
  function getArtistGenres($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_id = !empty($opts['artist_id']) ? $opts['artist_id'] : '%';
    $sql = "SELECT count(" . TBL_genre . ".`id`) as `count`,
                   " . TBL_genre . ".`name`,
                   " . TBL_genre . ".`id` as `tag_id`,
                   'genre' as `type`
            FROM " . TBL_genre . ",
                 " . TBL_genres . ",
                 " . TBL_artist . ",
                 " . TBL_album . "
            WHERE " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id`
              AND " . TBL_album . ".`id` = " . TBL_genres . ".`album_id`
              AND " . TBL_genre . ".`id` = " . TBL_genres . ".`genre_id`
              AND " . TBL_artist . ".`id` LIKE ?
            GROUP BY " . TBL_genre . ".`id`
            ORDER BY `count` DESC";
    $query = $ci->db->query($sql, array($artist_id));
    return ($query->num_rows() > 0) ? $query->result() : array();
  }
}

/**
   * Gets artist's keywords.
   *
   * @param array $opts.
   *          'artist_id'  => Artist ID
   *
   * @return array artist's Keyword information.
   *
   */
if (!function_exists('getArtistKeywords')) {
  function getArtistKeywords($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_id = !empty($opts['artist_id']) ? $opts['artist_id'] : '%';
    $sql = "SELECT count(" . TBL_keyword . ".`id`) as `count`,
                   " . TBL_keyword . ".`name`,
                   " . TBL_keyword . ".`id` as `tag_id`,
                   'keyword' as `type`
            FROM " . TBL_keyword . ",
                 " . TBL_keywords . ",
                 " . TBL_artist . ",
                 " . TBL_album . "
            WHERE " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id`
              AND " . TBL_album . ".`id` = " . TBL_keywords . ".`album_id`
              AND " . TBL_keyword . ".`id` = " . TBL_keywords . ".`keyword_id`
              AND " . TBL_artist . ".`id` = ?
            GROUP BY " . TBL_keyword . ".`id`
            ORDER BY `count` DESC";
    $query = $ci->db->query($sql, array($artist_id));
    return ($query->num_rows() > 0) ? $query->result() : array();
  }
}

/**
   * Gets artist's nationalities.
   *
   * @param array $opts.
   *          'artist_id'  => Artist ID
   *
   * @return array artist's Nationality information.
   *
   */
if (!function_exists('getArtistNationalities')) {
  function getArtistNationalities($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_id = !empty($opts['artist_id']) ? $opts['artist_id'] : '%';
    $sql = "SELECT count(" . TBL_nationality . ".`id`) as `count`,
                   " . TBL_nationality . ".`country` as `name`,
                   " . TBL_nationality . ".`country_code`,
                   " . TBL_nationality . ".`id` as `tag_id`,
                   'nationality' as `type`
            FROM " . TBL_nationality . ",
                 " . TBL_nationalities . ",
                 " . TBL_artist . ",
                 " . TBL_album . "
            WHERE " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id`
              AND " . TBL_album . ".`id` = " . TBL_nationalities . ".`album_id`
              AND " . TBL_nationality . ".`id` = " . TBL_nationalities . ".`nationality_id`
              AND " . TBL_artist . ".`id` = ?
            GROUP BY " . TBL_nationality . ".`id`
            ORDER BY `count` DESC";
    $query = $ci->db->query($sql, array($artist_id));
    return ($query->num_rows() > 0) ? $query->result() : array();
  }
}
?>