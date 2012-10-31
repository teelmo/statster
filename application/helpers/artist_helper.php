<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * Gets artist's info.
 *
 * @param array $opts.
 *          'artist'  => Artist name
 *
 * @return array Artist information or boolean FALSE.
 */
if (!function_exists('getArtistInfo')) {
  function getArtistInfo($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_name = !empty($opts['artist']) ? $opts['artist'] : '';
    $sql = "SELECT " . TBL_artist . ".`id` as `artist_id`, " . TBL_artist . ".`artist_name`
            FROM " . TBL_artist . "
            WHERE " . TBL_artist . ".`artist_name` = " . $ci->db->escape($artist_name);
    $query = $ci->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->result(0);
      return $result[0];
    }
    else {
      return FALSE;
    }
  }
}

/**
   * Gets artist's albums nationalities.
   *
   * @param array $opts.
   *          'artist_id'  => Artist ID
   *
   * @return array Nationality information or boolean FALSE.
   *
   * @todo Not yet implemented!
   */
if (!function_exists('getArtistNationalities')) {
  function getArtistNationalities($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
  }
}

/**
   * Gets artist's listenings.
   *
   * @param array $opts.
   *          'artist_id'  => Artist ID
   *          'user_id'  => User ID
   *
   * @return array Listening information.
   *
   */
if (!function_exists('getArtistListenings')) {
  function getArtistListenings($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $count_type = empty($opts['user_id']) ? 'total_count' : 'user_count';
    $opts['user_id'] = empty($opts['user_id']) ? '%' : $opts['user_id'];
    $sql = "SELECT count(" . TBL_artist . ".`id`) as `" . $count_type . "`
            FROM " . TBL_artist . ", " . TBL_album . ", " . TBL_listening . "
            WHERE " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id`
              AND " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` LIKE '" . $opts['user_id'] . "'
              AND " . TBL_artist . ".`id` = " . $opts['artist_id'];
    $query = $ci->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->result(0);
      return $result[0];
    }
    else {
      return array($count_type => 0);
    }
  }
}

/**
   * Gets artist's tags (genres and keywords).
   *
   * @param array $opts.
   *          'artist_id'  => Artist ID
   *          'user_id'  => User ID
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
   *          'user_id'  => User ID
   *
   * @return array artist's Keyword information.
   *
   */
if (!function_exists('getArtistGenres')) {
  function getArtistGenres($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $opts['user_id'] = empty($opts['user_id']) ? '%' : $opts['user_id'];
    $sql = "SELECT " . TBL_genre . ".`name`, count(" . TBL_genre . ".`id`) as `count`, 'genre' as `type`
            FROM " . TBL_genre . ", " . TBL_genres . ", " . TBL_artist . ", " . TBL_album . "
            WHERE " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id`
              AND " . TBL_album . ".`id` = " . TBL_genres . ".`album_id`
              AND " . TBL_genre . ".`id` = " . TBL_genres . ".`genre_id`
              AND " . TBL_artist . ".`id` = " . $opts['artist_id'] . "
            GROUP BY " . TBL_genre . ".`id`
            ORDER BY count(" . TBL_genre . ".`id`) DESC";
    $query = $ci->db->query($sql);
    if ($query->num_rows() > 0) {
      return $query->result(0);
    }
    else {
      return array();
    }
  }
}

/**
   * Gets artist's keywords.
   *
   * @param array $opts.
   *          'artist_id'  => Artist ID
   *          'user_id'  => User ID
   *
   * @return array artist's Keyword information.
   *
   */
if (!function_exists('getArtistKeywords')) {
  function getArtistKeywords($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $opts['user_id'] = empty($opts['user_id']) ? '%' : $opts['user_id'];
    $sql = "SELECT " . TBL_keyword . ".`name`, count(" . TBL_keyword . ".`id`) as `count`, 'keyword' as `type`
            FROM " . TBL_keyword . ", " . TBL_keywords . ", " . TBL_artist . ", " . TBL_album . "
            WHERE " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id`
              AND " . TBL_album . ".`id` = " . TBL_keywords . ".`album_id`
              AND " . TBL_keyword . ".`id` = " . TBL_keywords . ".`keyword_id`
              AND " . TBL_artist . ".`id` = " . $opts['artist_id'] . "
            GROUP BY " . TBL_keyword . ".`id`
            ORDER BY count(" . TBL_keyword . ".`id`) DESC";
    $query = $ci->db->query($sql);
    if ($query->num_rows() > 0) {
      return $query->result(0);
    }
    else {
      return array();
    }
  }
}
?>