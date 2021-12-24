<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Get unique artists
  *
  * @param array $opts.
  *          'artist_name'  => Artist name
  *          'user_id'      => User ID
  *
  * @return array Artist ID or boolean FALSE.
  */
if (!function_exists('getArtistsUnique')) {
  function getArtistsUnique($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : TBL_artist . '.`artist_name` ASC';
    $username = !empty($opts['username']) ? $opts['username'] : '%';

    $sql = "SELECT " . TBL_artist . ".`id` AS `artist_id`,
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_artist . ".`spotify_id`, 
                   COALESCE(t.`count`, 0) AS `count`
            FROM " . TBL_artist . "
            LEFT JOIN (
                SELECT count(*) AS `count`, 
                       " . TBL_artist . ".`id` AS `artist_id`
                FROM " . TBL_album . ",
                     " . TBL_artist . ",
                     " . TBL_listening . "
                WHERE " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
                  AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
                GROUP BY " . TBL_artist . ".`id`)
             `t` ON " . TBL_artist . ".`id` = `t`.`artist_id`
            GROUP BY " . TBL_artist . ".`id`
            ORDER BY " . $ci->db->escape_str($order_by);
    $query = $ci->db->query($sql, array($username));

    return ($query->num_rows() > 0) ? $query->result_array() : array();
  }
}

/**
  * Add new artist.
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

    $data['artist_name'] = isset($opts['artist_name']) ? ucwords($opts['artist_name']) : FALSE;
    $data['user_id'] = !empty($opts['user_id']) ? $opts['user_id'] : '';

    if ($data['artist_name'] !== FALSE) {
      $sql = "INSERT
                INTO " . TBL_artist . " (`user_id`, `artist_name`, `created`)
                VALUES (?, ?, CURRENT_TIMESTAMP())";
      $query = $ci->db->query($sql, array($data['user_id'], $data['artist_name']));
      if ($ci->db->affected_rows() === 1) {
        header('HTTP/1.1 201 Created');
        $data['artist_id'] = $ci->db->insert_id();
        $ci->load->helper(array('artist_helper', 'lastfm_helper'));
        // Add Spotify resource.
        getSpotifyResourceId($data);
        // Get album img and bio.
        fetchArtistInfo($data, array('bio', 'image'));
        // Return album ID.
        return $data['artist_id'];
      }
    }
    return FALSE;
  }
}

/**
  * Delete artist.
  *
  * @param array $opts.
  *
  * @return string JSON.
  */
if (!function_exists('deleteArtist')) {
  function deleteArtist($opts = array()) {
    $data = array();
    if (!$data['artist_id'] = $opts['artist_id']) {
      header('HTTP/1.1 400 Bad Request');
      return json_encode(array('error' => array('msg' => ERR_BAD_REQUEST)));
    }
    $ci=& get_instance();
    $ci->load->database();
    
    // Get user id from session.
    if (!$data['user_id'] = $ci->session->userdata('user_id')) {
      header('HTTP/1.1 401 Unauthorized');
      return json_encode(array('error' => array('msg' => $data)));
    }
    if (in_array($ci->session->userdata['user_id'], ADMIN_USERS)) {
      // Delete artist data from DB.
      $sql = "DELETE 
                FROM " . TBL_artist . "
                WHERE " . TBL_artist . ".`id` = ?";
      $query = $ci->db->query($sql, array($data['artist_id']));

      if ($ci->db->affected_rows() === 1) {
        header('HTTP/1.1 200 OK');
        return json_encode(array());
      }
      else {
        header('HTTP/1.1 401 Unauthorized');
        return json_encode(array('error' => array('msg' => $data, 'affected' => $ci->db->affected_rows())));
      }
    }
    else {
      show_403();
    }
  }
}

/**
  * Gets artist's info.
  *
  * @param array $opts.
  *          'artist_name'  => Artist name
  *          OR
  *          'artist_id'    => Arist ID
  *
  * @return array Artist information or boolean FALSE.
  */
if (!function_exists('getArtistInfo')) {
  function getArtistInfo($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    // Load helpers.
    $ci->load->helper(array('id_helper'));

    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : FALSE;
    $artist_id = !empty($opts['artist_id']) ? $opts['artist_id'] : FALSE;

    if ($artist_name === FALSE) {
      $sql = "SELECT " . TBL_artist . ".`id` as `artist_id`,
                     " . TBL_artist . ".`artist_name`,
                     " . TBL_artist . ".`spotify_id`,
                     YEAR(" . TBL_artist . ".`created`) as `created`
              FROM " . TBL_artist . "
              WHERE " . TBL_artist . ".`id` = ?";
      $query = $ci->db->query($sql, array($artist_id));
    }
    else {
      $sql = "SELECT " . TBL_artist . ".`id` as `artist_id`,
                     " . TBL_artist . ".`artist_name`,
                     " . TBL_artist . ".`spotify_id`,
                     YEAR(" . TBL_artist . ".`created`) as `created`
              FROM " . TBL_artist . "
              WHERE " . TBL_artist . ".`artist_name` LIKE ?";
      $query = $ci->db->query($sql, array($artist_name));
      if ($query->num_rows() === 0) {
        $sql = "SELECT " . TBL_artist . ".`id` as `artist_id`,
                       " . TBL_artist . ".`artist_name`,
                       " . TBL_artist . ".`spotify_id`,
                       YEAR(" . TBL_artist . ".`created`) as `created`
                FROM " . TBL_artist . "
                WHERE " . TBL_artist . ".`artist_name` LIKE ? COLLATE utf8_swedish_ci";
        $query = $ci->db->query($sql, array($artist_name));
      }
    }
    return ($query->num_rows() > 0) ? $query->result_array()[0] : FALSE;
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
    return ($query->num_rows() > 0) ? $query->result_array()[0] : array('update_bio' => false);
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
                    " . TBL_artist_biography . ".`text` = ?,
                    " . TBL_artist_biography . ".`updated` = NOW()
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
                  (SELECT " . TBL_artists . ".`artist_id`,
                         " . TBL_artists . ".`album_id`
                  FROM " . TBL_artists . ") AS " . TBL_artists . ",
                 " . TBL_album . ",
                 " . TBL_listening . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_album . ".`id` = " . TBL_artists . ".`album_id`
              AND " . TBL_artists . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_listening . ".`user_id` LIKE ?
              AND " . TBL_artist . ".`id` LIKE ?";
    $query = $ci->db->query($sql, array($user_id, $artist_id));
    return ($query->num_rows() > 0) ? $query->result_array()[0] : array($count_type => 0);
  }
}

/**
   * Gets artist's tags (genres, keywords and nationalities).
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
      $data = array();
      foreach ($tags_array as $idx => $tags) {
        foreach ($tags as $idx => $tag) {
          $data['tags'][] = $tag;
        }
      }
      if (isset($opts['sort'])) {
        uasort($data['tags'], '_tagsSortByCount');
      }
      uasort($data, '_tagsSortByCount');
      $data['tags'] = ($data) ? array_slice($data['tags'], 0, empty($opts['limit']) ? 8 : $opts['limit']) : [];
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
                   'genre' as `type`,
                   GROUP_CONCAT(DISTINCT(" . TBL_genres . ".`user_id`)) `user_ids`
            FROM " . TBL_genre . ",
                 " . TBL_genres . ",
                 " . TBL_artist . ",
                 " . TBL_artists . ",
                 " . TBL_album . "
            WHERE " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_artists . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_genres . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_genres . ".`genre_id` = " . TBL_genre . ".`id`
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
                   'keyword' as `type`,
                   GROUP_CONCAT(DISTINCT(" . TBL_keywords . ".`user_id`)) `user_ids`
            FROM " . TBL_keyword . ",
                 " . TBL_keywords . ",
                 " . TBL_artist . ",
                 " . TBL_artists . ",
                 " . TBL_album . "
            WHERE " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_artists . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_keywords . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_keywords . ".`keyword_id` = " . TBL_keyword . ".`id`
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
                   'nationality' as `type`,
                   GROUP_CONCAT(DISTINCT(" . TBL_nationalities . ".`user_id`)) `user_ids`
            FROM " . TBL_nationality . ",
                 " . TBL_nationalities . ",
                 " . TBL_artist . ",
                 " . TBL_artists . ",
                 " . TBL_album . "
            WHERE " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_artists . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_nationalities . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_nationalities . ".`nationality_id` = " . TBL_nationality . ".`id`
              AND " . TBL_artist . ".`id` = ?
            GROUP BY " . TBL_nationality . ".`id`
            ORDER BY `count` DESC";
    $query = $ci->db->query($sql, array($artist_id));
    return ($query->num_rows() > 0) ? $query->result() : array();
  }
}

/**
   * Edit artist info.
   *
   * @param array $opts.
   *          'artist_id'  => Artist ID
   *          'artist_name'  => Artist Name
   *          'spotify_id'  => Spotify ID
   *
   * @return boolean TRUE or FALSE.
   *
   */
if (!function_exists('updateArtist')) {
  function updateArtist($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_id = !empty($opts['artist_id']) ? $opts['artist_id'] : '';
    $artist_name = isset($opts['artist_name']) ? trim(str_replace(' ', '', $opts['artist_name'])) : FALSE;
    $spotify_id = !empty($opts['spotify_id']) ? $opts['spotify_id'] : '';

    if ($artist_name !== FALSE) {
      $sql = "UPDATE " . TBL_artist . "
                SET " . TBL_artist . ".`artist_name` = ?,
                    " . TBL_artist . ".`spotify_id` = ?
                WHERE " . TBL_artist . ".`id` = ?";

      $query = $ci->db->query($sql, array($artist_name, $spotify_id, $artist_id));
      return ($ci->db->affected_rows() === 1) ? TRUE : FALSE;
    }
    else {
      return FALSE;
    }
  }
}
?>