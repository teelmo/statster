<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('getAlbumLove')) {
  function getAlbumLove($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $user_id = isset($opts['user_id']) ? $opts['user_id'] : '';
    $album_id = isset($opts['album_id']) ? $opts['album_id'] : '';
    $sql = "SELECT " . TBL_love . ".`id`
            FROM " . TBL_love . "
            WHERE " . TBL_love . ".`user_id` = " . $ci->db->escape($user_id) . "
              AND " . TBL_love . ".`album_id` = " . $ci->db->escape($album_id);
    $query = $ci->db->query($sql);
    if ($query->num_rows() > 0) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }
}

if (!function_exists('getArtistFan')) {
  function getArtistFan($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $user_id = isset($opts['artist_id']) ? $opts['artist_id'] : '';
    $artist_id = isset($opts['artist_id']) ? $opts['artist_id'] : '';
    $sql = "SELECT " . TBL_fan . ".`id`
            FROM " . TBL_fan . "
            WHERE " . TBL_fan . ".`user_id` = " . $ci->db->escape($user_id) . "
              AND " . TBL_fan . ".`artist_id` = " . $ci->db->escape($artist_id);
    $query = $ci->db->query($sql);
    if ($query->num_rows() > 0) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }
}

?>