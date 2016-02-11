<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

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
    $data = array();
    $tags_array = array();
    $tags_array[] = getArtistGenres($opts);
    $tags_array[] = getArtistKeywords($opts);
    foreach ($tags_array as $idx => $tags) {
      foreach ($tags as $idx => $tag) {
        $data['tags'][] = $tag;
      }
    }
    uasort($data, '_tagsSortByCount');
    $data['tags'] = array_slice($data['tags'], 0, empty($opts['limit']) ? 8 : $opts['limit']);
    return $data;
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
?>