<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
   * Gets artist's albums nationalities.
   *
   * @param array $opts.
   *          'album_id'  => Album ID
   *
   * @return array Nationality information or boolean FALSE.
   *
   * @todo Not yet implemented!
   */
if (!function_exists('getArtistNationalities')) {
  function getArtistNationalities($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();


  }
}
?>