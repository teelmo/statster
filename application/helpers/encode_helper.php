<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
   * Convert elements so that they can be used in urls
   *
   * @param string $url.
   *
   * @return string url encoded string.
   *
   */
if (!function_exists('url_title')) {
  function url_title($url) {
    $search = array('#','?','+','/',' ','&','"');
    $replace = array('%2523','%253F','%252B','%252F','+','%2526','%2522');
    return str_replace($search, $replace, $url);
  }
}
?>