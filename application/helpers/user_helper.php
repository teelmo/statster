<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
   * Handles user login form posts.
   *
   * @param array $opts.
   *          'artist'  => Artist name
   *
   * @return int artist ID or boolean FALSE.
   *
   * @todo Not yet implemented!
   */
if (!function_exists('loginUser')) {
  function loginUser($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();


  }
}
?>