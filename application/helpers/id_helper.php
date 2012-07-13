<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('getArtistID')) {
  function getArtistID($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $artist = isset($opts['artist']) ? $opts['artist'] : '';
    $sql = "SELECT " . TBL_artist . ".`id`
            FROM " . TBL_artist . "
            WHERE " . TBL_artist . ".`artist_name` = " . $ci->db->escape($artist) . "
            LIMIT 1";
    $query = $ci->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->result();
      return $result[0]->id;
    }
    else {
      return FALSE;
    }
  }
}

if (!function_exists('getAlbumID')) {
  function getAlbumID($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist = isset($opts['artist']) ? $opts['artist'] : '';
    $album = isset($opts['album']) ? $opts['album'] : '';
    $year = isset($opts['year']) ? $opts['year'] : '%';
    $sql = "SELECT " . TBL_album . ".`id`
            FROM " . TBL_album . ", 
                 " . TBL_artist . "
            WHERE " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_album . ".`year` LIKE '$year'
              AND " . TBL_artist . ".`artist_name` = " . $ci->db->escape($artist) . "
              AND " . TBL_album . ".`album_name` = " . $ci->db->escape($album) . "
            LIMIT 1";
    $query = $ci->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->result();
      return $result[0]->id;
    }
    else {
      return FALSE;
    }
  }
}

if (!function_exists('getUserID')) {
  function getUserID($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $username = isset($opts['username']) ? $opts['username'] : '';
    $sql = "SELECT " . TBL_user . ".`id`
            FROM " . TBL_user . "
            WHERE " . TBL_user . ".`username` = " . $ci->db->escape($username) . "
            LIMIT 1";
    $query = $ci->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->result();
      return $result[0]->id;
    }
    else {
      return FALSE;
    }
  }
}

if (!function_exists('getFormatID')) {
  function getFormatID($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $format = isset($opts['format']) ? $opts['format'] : '';
    $sql = "SELECT " . TBL_listening_format . ".`id`
            FROM " . TBL_listening_format . "
            WHERE " . TBL_listening_format . ".`name` = " . $ci->db->escape($format) . "
            LIMIT 1";
    $query = $ci->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->result();
      return $result[0]->id;
    }
    else {
      return FALSE;
    }
  }   
}

if (!function_exists('getFormatTypeID')) {
  function getFormatTypeID($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $formatType = isset($opts['format_type']) ? $opts['format_type'] : '';
    $sql = "SELECT " . TBL_listening_format_type . ".`id`
            FROM " . TBL_listening_format_type . "
            WHERE " . TBL_listening_format_type . ".`name` = " . $ci->db->escape($formatType) . "
            LIMIT 1";
    $query = $ci->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->result();
      return $result[0]->id;
    }
    else {
      return FALSE;
    }
  }   
}
?>