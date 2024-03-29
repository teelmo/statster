<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

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
    $search = array('#','?','+','/','%252F',' ','&','"','<','>','–','—');
    $replace = array('%2523','%253F','%252B',urlencode('%252F'),urlencode('%252F'),'+','%2526','%2522', '%253C', '%253E', '%E2%80%93', '%E2%80%94');
    return str_replace($search, $replace, $url);
  }
}

/**
  * Convert a string so that it can be used by the system
  *
  * @param string $str.
  *
  * @return string str decoded string.
  *
  */
if (!function_exists('decode')) {
  function decode($str) {
    return html_entity_decode(rawurldecode(urldecode(urldecode($str))));
  }
}
?>