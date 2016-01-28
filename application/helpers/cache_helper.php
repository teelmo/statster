<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

$ci=& get_instance();
if (ENVIRONMENT === 'development') {
  if (!strpos($_SERVER['REQUEST_URI'], 'api')) {
    $ci->output->cache(0);
  }
}
else {
  if (!strpos($_SERVER['REQUEST_URI'], 'api')) {
    $ci->output->cache(10);
  }
}
?>