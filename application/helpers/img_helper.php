<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
   * Get path to user's profile image.
   *
   * @param array $opts.
   *          'size'     => Desired image size
   *          'user_id'  => User ID
   *
   * @return string Absolute path to image file.
   */
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
    return (read_file($filename)) ? site_url() . $filename : site_url() . $empty_filename;
  }   
}

/**
   * Get path to artist's art.
   *
   * @param array $opts.
   *          'size'        => Desired image size
   *          'artist_id'   => Artist ID
   *
   * @return string Absolute path to image file.
   */
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
    return (read_file($filename)) ? site_url() . $filename : site_url() . $empty_filename;
  }   
}

/**
   * Get path to album's art.
   *
   * @param array $opts.
   *          'size'      => Desired image size
   *          'album_id'  => Artist ID
   *
   * @return string Absolute path to image file.
   */
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
    return (read_file($filename)) ? site_url() . $filename : site_url() . $empty_filename;
  }   
}

/**
   * Collection function for getting the listening's
   * format or format type information 
   *
   * @param array $opts.
   *          'listening_id'  => Listening ID
   *
   * @return string Absolute path to image file.
   */
if (!function_exists('getListeningsFormatImg')) {
  function getListeningsFormatImg($opts = array()) {
    $format_type_img = getFormatTypeImg($opts);
    $format_img = getFormatImg($opts);
    if ($format_type_img != FALSE) {
      return $format_type_img;
    }
    elseif ($format_img != FALSE) {
      return $format_img;
    }
    else {
      return array('filename' => site_url() . './media/img/format_img/format_icons/empty.png', 'name' => '');
    }
  }
}    

/**
   * Get listening's format image.
   *
   * @param array $opts.
   *          'listening_id'  => Listening ID
   *
   * @return string Absolute path to image file.
   */
if (!function_exists('getFormatImg')) {
  function getFormatImg($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $listening_id = isset($opts['listening_id']) ? $opts['listening_id'] : '';
    $sql = "SELECT " . TBL_listening_format . ".`img`, " . TBL_listening_format . ".`name`
            FROM " . TBL_listening_format . ", " . TBL_listening_formats . ", " . TBL_listening . "
            WHERE " . TBL_listening_format . ".`id` = " . TBL_listening_formats . ".`listening_format_id`
              AND " . TBL_listening . ".`id` = " . TBL_listening_formats . ".`listening_id`
              AND " . TBL_listening . ".`id` = " . $ci->db->escape($listening_id);
    $query = $ci->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->result();
      $filename = './media/img/format_img/format_icons/' . $result[0]->img;

      return (read_file($filename)) ? array('filename' => site_url() . $filename, 
                                            'name' => $result[0]->name, 
                                            'empty' => FALSE) : FALSE;
                                    
    }
    else {
      return FALSE;
    }
  }
}

/**
   * Get listening's format type image.
   *
   * @param array $opts.
   *          'listening_id'  => Listening ID
   *
   * @return string Absolute path to image file.
   */
if (!function_exists('getFormatTypeImg')) {
  function getFormatTypeImg($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $listening_id = isset($opts['listening_id']) ? $opts['listening_id'] : '';
    $sql = "SELECT " . TBL_listening_format_type . ".`img`, " . TBL_listening_format_type . ".`name`
            FROM " . TBL_listening_format_type . ", " . TBL_listening_format_types . ", " . TBL_listening . "
            WHERE " . TBL_listening_format_type . ".`id` = " . TBL_listening_format_types . ".`listening_format_type_id`
              AND " . TBL_listening . ".`id` = " . TBL_listening_format_types . ".`listening_id`
              AND " . TBL_listening . ".`id` = " . $ci->db->escape($listening_id);
    $query = $ci->db->query($sql);
    if ($query->num_rows() > 0) {
      $result = $query->result();
      $filename = './media/img/format_img/format_icons/' . $result[0]->img;
      return (read_file($filename)) ? array('filename' => site_url() . $filename, 
                                            'name' => $result[0]->name, 
                                            'empty' => FALSE) : FALSE;
    }
    else {
      return FALSE;
    }
  }
}
?>