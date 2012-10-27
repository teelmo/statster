<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
   * Gets album's info.
   *
   * @param array $opts.
   *          'artist'  => Artist name
   *          'album'  => Album name
   *
   * @return array Album information or boolean FALSE.
   */
if (!function_exists('getAlbumInfo')) {
  function getAlbumInfo($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_name = !empty($opts['artist']) ? $opts['artist'] : '';
    $album_name = !empty($opts['album']) ? $opts['album'] : '';
    $sql = "SELECT " . TBL_artist . ".`id` as `artist_id`, " . TBL_album . ".`id` as `album_id`, " . TBL_artist . ".`artist_name`, " . TBL_album . ".`album_name`, " . TBL_album . ".`year`
            FROM " . TBL_artist . ", " . TBL_album . "
            WHERE ".TBL_album.".`artist_id` = " . TBL_artist . ".`id` 
              AND " . TBL_artist . ".`artist_name` = " . $ci->db->escape($artist_name) . "
              AND " . TBL_album . ".`album_name` = " . $ci->db->escape($album_name);
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
   * Gets album's nationalities.
   *
   * @param array $opts.
   *          'album_id'  => Album ID
   *
   * @return array Nationality information or boolean FALSE.
   *
   * @todo Not yet implemented!
   */
if (!function_exists('getAlbumNationalities')) {
  function getAlbumNationalities($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
  }
}

/**
   * Gets album's listenings.
   *
   * @param array $opts.
   *          'album_id'  => Album ID
   *          'user_id'  => User ID
   *
   * @return array Listening information.
   *
   */
if (!function_exists('getAlbumListenings')) {
  function getAlbumListenings($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $count_type = empty($opts['user_id']) ? 'total_count' : 'user_count';
    $opts['user_id'] = empty($opts['user_id']) ? '%' : $opts['user_id'];
    $sql = "SELECT count(" . TBL_album . ".`id`) as `" . $count_type . "`
            FROM " . TBL_album . ", " . TBL_listening . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` LIKE '" . $opts['user_id'] . "'
              AND " . TBL_album . ".`id` = " . $opts['album_id'];
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
   * Gets album's tags (genres and keywords).
   *
   * @param array $opts.
   *          'album_id'  => Album ID
   *          'user_id'  => User ID
   *
   * @return array Tag information or boolean FALSE.
   *
   */
if (!function_exists('getAlbumTags')) {
  function getAlbumTags($opts = array()) {
    $data = array();
    $tags_array = array();
    $tags_array[] = getAlbumGenres($opts);
    $tags_array[] = getAlbumKeywords($opts);
    foreach ($tags_array as $idx => $tags) {
      foreach ($tags as $idx => $tag) {
        $data['tags'][] = $tag;
      }
    }
    uasort($data, '_tagsSort');
    $data['tags'] = array_slice($data['tags'], 0, empty($opts['limit']) ? 8 : $opts['limit']);
    return $data;
  }
}

/*
 * Helper function for sorting tags
 */
function _tagsSort($a, $b) {
  if ($a['count'] == $b['count']) {
    return 0;
  }
  return ($a['count'] > $b['count']) ? -1 : 1;
}

/**
   * Gets artist's genres.
   *
   * @param array $opts.
   *          'album_id'  => Album ID
   *          'user_id'  => User ID
   *
   * @return array Keyword information or boolean FALSE.
   *
   */
if (!function_exists('getAlbumGenres')) {
  function getAlbumGenres($opts = array()) {
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
      return FALSE;
    }
  }
}

/**
   * Gets album's keywords.
   *
   * @param array $opts.
   *          'album_id'  => Album ID
   *          'user_id'  => User ID
   *
   * @return array Keyword information or boolean FALSE.
   *
   */
if (!function_exists('getAlbumKeywords')) {
  function getAlbumKeywords($opts = array()) {
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
      return FALSE;
    }
  }
}
?>