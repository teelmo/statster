<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('getUserImg')) {
  function getUserImg($opts = array()) {
    $ci=& get_instance();
    $ci->load->helper('file');
    if(empty($opts['size'])) {
      return '';
    }
    $empty_filename = './media/img/user_img/' . $opts['size'] . '/' . 0 . '.jpg';
    if(empty($opts['user_id'])) {
      return $empty_filename;
    }
    $filename = './media/img/user_img/' . $opts['size'] . '/' . $opts['user_id'] . '.jpg';
    return (read_file($filename)) ? $filename : $empty_filename;
  }   
}

if (!function_exists('getArtistImg')) {
  function getArtistImg($opts = array()) {
    $ci=& get_instance();
    $ci->load->helper('file');
    if(empty($opts['size'])) {
      return '';
    }
    $empty_filename = './media/img/artist_img/' . $opts['size'] . '/' . 0 . '.jpg';
    if(empty($opts['artist_id'])) {
      return $empty_filename;
    }
    $filename = './media/img/artist_img/' . $opts['size'] . '/' . $opts['artist_id'] . '.jpg';
    return (read_file($filename)) ? $filename : $empty_filename;
  }   
}

if (!function_exists('getAlbumImg')) {
  function getAlbumImg($opts = array()) {
    $ci=& get_instance();
    $ci->load->helper('file');
    if(empty($opts['size'])) {
      return '';
    }
    $empty_filename = './media/img/album_img/' . $opts['size'] . '/' . 0 . '.jpg';
    if(empty($opts['album_id'])) {
      return $empty_filename;
    }
    $filename = './media/img/album_img/' . $opts['size'] . '/' . $opts['album_id'] . '.jpg';
    return (read_file($filename)) ? $filename : $empty_filename;
  }   
}

if (!function_exists('getListeningsFormatImg')) {
  function getListeningsFormatImg($opts = array()) {
    $format_img = getFormatImg($opts);
    $format_type_img = getFormatTypeImg($opts);

    return (!empty($format_type_img) || $format_type_img == 'empty.png') ? :
    /*
    if ($query->num_rows() > 0) {
      $result = $query->result();
      $empty_filename = './media/img/format_img/format_icons/file.png';
      $filename = './media/img/format_img/format_icons/' . $result[0]->img;
      return (read_file($filename)) ? $filename : $empty_filename;
    }
    else {
      return FALSE;
    }*/
  }
}    

if (!function_exists('getFormatImg')) {
  function getFormatImg($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $format_id = isset($opts['format_id']) ? $opts['format_id'] : '';
    $sql = "SELECT " . TBL_listening_format . ".`img`
            FROM " . TBL_listening_format . "
            WHERE  " . TBL_listening_format . ".`id` = " . $ci->db->escape($format_id);
    $query = $ci->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->result();
      $empty_filename = './media/img/format_img/format_icons/file.png';
      $filename = './media/img/format_img/format_icons/' . $result[0]->img;
      return (read_file($filename)) ? $filename : $empty_filename;
    }
    else {
      return FALSE;
    }
  }
}

if (!function_exists('getFormatTypeImg')) {
  function getFormatTypeImg($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $format_type_id = isset($opts['format_type_id']) ? $opts['format_type_id'] : '';
    $sql = "SELECT " . TBL_listening_format_type . ".`img`
            FROM " . TBL_listening_format_type . "
            WHERE  " . TBL_listening_format_type . ".`id` = " . $ci->db->escape($format_type_id);
    $query = $ci->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->result();
      $empty_filename = './media/img/format_img/format_icons/file.png';
      $filename = './media/img/format_img/format_icons/' . $result[0]->img;
      return (read_file($filename)) ? $filename : $empty_filename;
    }
    else {
      return FALSE;
    }
  }
}
?>