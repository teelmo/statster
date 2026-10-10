<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');


/* Based on original work from the PHP Laravel framework
 * source: https://www.php.net/manual/en/function.str-contains.php
 */
if (!function_exists('str_contains')) {
  function str_contains($haystack, $needle) {
    return $needle !== '' && mb_strpos($haystack, $needle) !== false;
  }
}

/**
  * Decode a JSON-encoded query result and return its first row, or a
  * default value if decoding yields nothing. Controllers previously called
  * json_decode() on the same get*() result twice - once to check it wasn't
  * NULL, once to actually index [0] - running the underlying query twice.
  *
  * @param string $json JSON-encoded result (from a get*() helper).
  * @param mixed $default Value to return if decoding yields NULL.
  *
  * @return mixed First decoded row, or $default.
  */
if (!function_exists('decodeFirstOrDefault')) {
  function decodeFirstOrDefault($json, $default = array()) {
    $decoded = json_decode($json ?? '', true);
    return ($decoded !== NULL) ? $decoded[0] : $default;
  }
}

/**
  * Appends a cache-busting ?v=<mtime> query string to a media asset path,
  * so deploys invalidate the week-long Cache-Control set in .htaccess
  * (browsers - mobile Safari in particular - otherwise keep serving a
  * stale bundle.min.css/js file for up to 7 days after it changes).
  *
  * @param string $path Site-relative asset path, e.g. '/media/css/dist/bundle.min.css'.
  *
  * @return string The same path, with ?v=<mtime> appended when the file exists.
  */
if (!function_exists('versioned_asset')) {
  function versioned_asset($path) {
    $mtime = @filemtime(FCPATH . ltrim($path, '/'));
    return ($mtime !== FALSE) ? $path . '?v=' . $mtime : $path;
  }
}

?>