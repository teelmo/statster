<?php
$data = array();

$data['id'] = isset($_REQUEST['id']) ? $_REQUEST['id'] : FALSE;
$data['size'] = isset($_REQUEST['size']) ? $_REQUEST['size'] : FALSE;
$data['type'] = isset($_REQUEST['type']) ? $_REQUEST['type'] : FALSE;
$data['uri'] = isset($_REQUEST['uri']) ? $_REQUEST['uri'] : FALSE;
define('IMAGE_SIZES', [32, 64, 174, 300]);

if (!function_exists('read_file')) {
  function read_file($file) {
    return @file_get_contents($file);
  }
}

if (!function_exists('site_url')) {
  function site_url() {
    return 'http://' . $_SERVER['HTTP_HOST'] . '/';
  }
}
?>