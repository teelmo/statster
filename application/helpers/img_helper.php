<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('getUserImg')) {
  function getUserImg($opts = array()) {
    if(empty($opts['size'])) return FALSE;
    if(empty($opts['user_id'])) return '/media/img/user_img/' . $opts['size'] . '/' . 0 . '.jpg';  
    return '/media/img/user_img/' . $opts['size'] . '/' . $opts['user_id'] . '.jpg';  
  }   
}

if (!function_exists('getArtistImg')) {
  function getArtistImg($opts = array()) {
    if(empty($opts['size'])) return FALSE;
    if(empty($opts['artist_id'])) return '/media/img/artist_img/' . $opts['size'] . '/' . 0 . '.jpg';  
    return '/media/img/artist_img/' . $opts['size'] . '/' . $opts['artist_id'] . '.jpg';  
  }   
}

if (!function_exists('getAlbumImg')) {
  function getAlbumImg($opts = array()) {
    if(empty($opts['size'])) return FALSE;
    if(empty($opts['album_id'])) return '/media/img/album_img/' . $opts['size'] . '/' . 0 . '.jpg';  
    return '/media/img/album_img/' . $opts['size'] . '/' . $opts['album_id'] . '.jpg';  
  }   
}
?>