<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * Tells if the given album is loved by the given user.
 *
 * @param array $opts.
 *          'user_id'   => User ID
 *          'album_id'  => Artist ID
 *
 * @return string JSON encoded data containing album information.
 */
if (!function_exists('getLove')) {
  function getLove($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    // Load helpers
    $ci->load->helper(array('return_helper'));

    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : '';
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT " . TBL_love . ".`id`, 'love' as `type`
            FROM " . TBL_love . "
            WHERE " . TBL_love . ".`user_id` LIKE " . $ci->db->escape($user_id) . "
              AND " . TBL_love . ".`album_id` = " . $ci->db->escape($album_id);
    $query = $ci->db->query($sql);
    return _json_return_helper($query, $human_readable);
  }
}

/**
   * Tells if the given artist is faned by the given user.
   *
   * @param array $opts.
   *          'user_id'   => User ID
   *          'artist_id' => Artist ID
   *
   * @return int or boolean FALSE.
   */
if (!function_exists('getFan')) {
  function getFan($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    // Load helpers
    $ci->load->helper(array('return_helper'));

    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : '%';
    $artist_id = !empty($opts['artist_id']) ? $opts['artist_id'] : '';
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $sql = "SELECT " . TBL_fan . ".`id`, 'fan' as `type`
            FROM " . TBL_fan . "
            WHERE " . TBL_fan . ".`user_id` LIKE " . $ci->db->escape($user_id) . "
              AND " . TBL_fan . ".`artist_id` = " . $ci->db->escape($artist_id);
    $query = $ci->db->query($sql);
    return _json_return_helper($query, $human_readable);
  }
}
?>