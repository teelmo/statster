<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

$ci=& get_instance();

if (!empty($_SESSION['get_username']) && empty($_GET['u'])) {
  $_GET['u'] = $_SESSION['get_username'];
}

/* Based on original work from the PHP Laravel framework
 * source: https://www.php.net/manual/en/function.str-contains.php
 */
if (!function_exists('str_contains')) {
  function str_contains($haystack, $needle) {
    return $needle !== '' && mb_strpos($haystack, $needle) !== false;
  }
}

if (!function_exists('clear_cache_by_prefix')) {
  function clear_cache_by_prefix($prefixes = []) {
    $ci = &get_instance();

    $cache_path = $ci->config->item('cache_path');
    if ($cache_path === '') {
      $cache_path = APPPATH . 'cache/';
    }

    if (!is_dir($cache_path)) {
      return;
    }

    foreach (scandir($cache_path) as $file) {
      if ($file === '.' || $file === '..' || !is_file($cache_path . $file)) {
        continue;
      }

      // Loop through prefixes and check if the filename starts with any of them
      foreach ($prefixes as $prefix) {
        if (strpos($file, $prefix) === 0) { // Check if file starts with the prefix
          @unlink($cache_path . $file);
          break; // Stop once a match is found
        }
      }
    }
  }
}
?>