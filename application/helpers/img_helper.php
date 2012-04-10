<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('getUserImg')) {
  function getUserImg($opts = array()) {
    $ci=& get_instance();
    $ci->load->helper('file');
    if(empty($opts['size'])) return '';
    $empty_filename = './media/img/user_img/' . $opts['size'] . '/' . 0 . '.jpg';
    if(empty($opts['user_id'])) return $empty_filename;
    $filename = './media/img/user_img/' . $opts['size'] . '/' . $opts['user_id'] . '.jpg';
    return (read_file($filename)) ? $filename : $empty_filename;
  }   
}

if (!function_exists('getArtistImg')) {
  function getArtistImg($opts = array()) {
    $ci=& get_instance();
    $ci->load->helper('file');
    if(empty($opts['size'])) return '';
    $empty_filename = './media/img/artist_img/' . $opts['size'] . '/' . 0 . '.jpg';
    if(empty($opts['artist_id'])) return $empty_filename;
    $filename = './media/img/artist_img/' . $opts['size'] . '/' . $opts['artist_id'] . '.jpg';
    return (read_file($filename)) ? $filename : $empty_filename;
  }   
}

if (!function_exists('getAlbumImg')) {
  function getAlbumImg($opts = array()) {
    $ci=& get_instance();
    $ci->load->helper('file');
    if(empty($opts['size'])) return '';
    $empty_filename = './media/img/album_img/' . $opts['size'] . '/' . 0 . '.jpg';
    if(empty($opts['album_id'])) return $empty_filename;
    $filename = './media/img/album_img/' . $opts['size'] . '/' . $opts['album_id'] . '.jpg';
    return (read_file($filename)) ? $filename : $empty_filename;
  }   
}
?>