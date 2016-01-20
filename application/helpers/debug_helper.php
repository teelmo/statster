<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * Debug helper.
 * @param array|object $val.
 * 
 * @return preformatted string.
 */
if (!function_exists('pr')) {
  function pr($val) {
    echo '<pre style="margin-top: 40px; margin-bottom:40px;">';
    print_r(var_dump($val));
    echo '</pre>';
  }
}