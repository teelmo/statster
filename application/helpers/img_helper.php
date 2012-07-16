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
    if($format_type_img['empty'] == FALSE) {
      return $format_type_img;
    }
    elseif($format_img['empty'] == FALSE) {
      return $format_img;
    }
    else {
      return './media/img/format_img/format_icons/empty.png';
    }
  }
}    

if (!function_exists('getFormatImg')) {
  function getFormatImg($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $format_id = isset($opts['listening_format_id']) ? $opts['listening_format_id'] : '';
    $sql = "SELECT " . TBL_listening_format . ".`img`, " . TBL_listening_format . ".`name`
            FROM " . TBL_listening_format . "
            WHERE  " . TBL_listening_format . ".`id` = " . $ci->db->escape($format_id);
    $query = $ci->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->result();
      $filename = './media/img/format_img/format_icons/' . $result[0]->img;
      return (read_file($filename)) ? array('filename' => $filename, 
                                            'name' => $result[0]->name, 
                                            'empty' => FALSE) : 
                                      array('filename' => './media/img/format_img/format_icons/file.png', 
                                            'empty' => TRUE);
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

    $format_type_id = isset($opts['listening_format_type_id']) ? $opts['listening_format_type_id'] : '';
    $sql = "SELECT " . TBL_listening_format_type . ".`img`, " . TBL_listening_format_type . ".`name`
            FROM " . TBL_listening_format_type . "
            WHERE  " . TBL_listening_format_type . ".`id` = " . $ci->db->escape($format_type_id);
    $query = $ci->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->result();
      $filename = './media/img/format_img/format_icons/' . $result[0]->img;
      return (read_file($filename)) ? array('filename' => $filename, 
                                            'name' => $result[0]->name, 
                                            'empty' => FALSE) : 
                                      array('filename' => './media/img/format_img/format_icons/file.png', 
                                            'empty' => TRUE);
    }
    else {
      return FALSE;
    }
  }
}
?>