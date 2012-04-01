<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('getUserID')) {
  function getUserID($opts = array()) {
    return FALSE;
  }   
}

if (!function_exists('getArtistID')) {
  function getArtistID($opts = array()) {
    $artist = isset($opts['artist']) ? $opts['artist'] : '';
    $sql = "SELECT " . TBL_artist . ".`id`
            FROM " . TBL_artist . "
            WHERE " . TBL_artist . ".`artist_name` = " . $this->db->escape($artist) . "
            LIMIT 1";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      echo json_encode($query->result());
    }
    else {
      echo json_encode('');
    }
  }   
}

if (!function_exists('getAlbumID')) {
  function getAlbumID($opts = array()) {
    $artist = isset($opts['artist']) ? $opts['artist'] : '';
    $album = isset($opts['album']) ? $opts['album'] : '';
    $year = isset($opts['year']) ? $opts['year'] : '%';
    $sql = "SELECT " . TBL_album . ".`id`
            FROM " . TBL_album . ", 
                 " . TBL_artist . "
            WHERE " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_album . ".`year` LIKE '$year'
              AND " . TBL_artist . ".`artist_name` = " . $this->db->escape($artist) . "
              AND " . TBL_album . ".`album_name` = " . $this->db->escape($album) . "
            LIMIT 1";
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      echo json_encode($query->result());
    }
    else {
      echo json_encode('');
    }
  }   
}
?>