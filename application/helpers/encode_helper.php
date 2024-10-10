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
    $search = array(
      '#', #1
      '?', #2
      '+', #3
      '/', #4
      '%252F', #5
      ' ', #6
      '&', #7
      '"', #8
      '<', #9
      '>', #10
      '–', #11
      '—', #12
      '\\' #13
    );
    $replace = array(
      '%2523', #1
      '%253F', #2
      '%252B', #3
      urlencode('%252F'), #4
      urlencode('%252F'), #5
      '+', #6
      '%2526', #7
      '%2522', #8
      '%253C', #9
      '%253E', #10
      '%E2%80%93', #11
      '%E2%80%94', #12
      '%255C' #13
    );
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