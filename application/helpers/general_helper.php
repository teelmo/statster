<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

$ci=& get_instance();
$ci->load->library('session');

if (!empty($_SESSION['get_username'])) {
  $_GET['u'] = $_SESSION['get_username'];
}
?>

