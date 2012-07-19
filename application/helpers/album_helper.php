<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
   * Gets album's nationalities.
   *
   * @param array $opts.
   *          'artist_id'  => Artist ID
   *
   * @return array Nationality information or boolean FALSE.
   *
   * @todo Not yet implemented!
   */
if (!function_exists('getAlbumNationalities')) {
  function getAlbumNationalities($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();


  }
}
?>