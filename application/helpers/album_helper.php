<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * Gets album's info.
 *
 * @param array $opts.
 *          'artist_name'  => Artist name
 *          'album_name'   => Album name
 *
 * @return array Album information or boolean FALSE.
 */
if (!function_exists('getAlbumInfo')) {
  function getAlbumInfo($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '';
    $album_name = !empty($opts['album_name']) ? $opts['album_name'] : '';
    $sql = "SELECT " . TBL_artist . ".`id` as `artist_id`,
                   " . TBL_album . ".`id` as `album_id`,
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_album . ".`album_name`,
                   " . TBL_album . ".`year`,
                   YEAR(" . TBL_album . ".`created`) as `created`
            FROM " . TBL_artist . ",
                 " . TBL_album . "
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

    $count_type = !empty($opts['user_id']) ? 'user_count': 'total_count';
    $opts['user_id'] = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $sql = "SELECT count(" . TBL_album . ".`id`) as `" . $count_type . "`
            FROM " . TBL_album . ",
                 " . TBL_listening . "
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
   *          'user_id'   => User ID
   *
   * @return array Tag information.
   *
   */
if (!function_exists('getAlbumTags')) {
  function getAlbumTags($opts = array()) {
    $tags_array = array();
    $tags_array[] = getAlbumGenres($opts);
    $tags_array[] = getAlbumKeywords($opts);
    if (is_array($tags_array)) {
      $data = array();
      foreach ($tags_array as $idx => $tags) {
        foreach ($tags as $idx => $tag) {
          $data['tags'][] = $tag;
        }
      }
      uasort($data, '_tagsSortByCount');
      $data['tags'] = array_slice($data['tags'], 0, empty($opts['limit']) ? 8 : $opts['limit']);
      return $data;
    }
    return array();
  }
}

/**
   * Gets album's genres.
   *
   * @param array $opts.
   *          'album_id'  => Album ID
   *          'user_id'   => User ID
   *
   * @return array album's Keyword information.
   *
   */
if (!function_exists('getAlbumGenres')) {
  function getAlbumGenres($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $opts['user_id'] = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $sql = "SELECT count(" . TBL_genre . ".`id`) as `count`, " . TBL_genre . ".`name`, 'genre' as `type`
            FROM " . TBL_genre . ",
                 " . TBL_genres . ",
                 " . TBL_album . "
            WHERE " . TBL_album . ".`id` = " . TBL_genres . ".`album_id`
              AND " . TBL_genre . ".`id` = " . TBL_genres . ".`genre_id`
              AND " . TBL_album . ".`id` = " . $opts['album_id'] . "
            GROUP BY " . TBL_genre . ".`id`
            ORDER BY `count` DESC";
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
   * Gets album's keywords.
   *
   * @param array $opts.
   *          'album_id'  => Album ID
   *          'user_id'   => User ID
   *
   * @return array album's Keyword information.
   *
   */
if (!function_exists('getAlbumKeywords')) {
  function getAlbumKeywords($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $opts['user_id'] = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $sql = "SELECT count(" . TBL_keyword . ".`id`) as `count`, " . TBL_keyword . ".`name`, 'keyword' as `type`
            FROM " . TBL_keyword . ",
                 " . TBL_keywords . ",
                 " . TBL_album . "
            WHERE " . TBL_album . ".`id` = " . TBL_keywords . ".`album_id`
              AND " . TBL_keyword . ".`id` = " . TBL_keywords . ".`keyword_id`
              AND " . TBL_album . ".`id` = " . $opts['album_id'] . "
            GROUP BY " . TBL_keyword . ".`id`
            ORDER BY `count` DESC";
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