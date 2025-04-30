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
?>