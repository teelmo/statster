<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

// application/core/MY_Exceptions.php
class MY_Controller extends CI_Controller {
  public function __construct() {
    parent::__construct();
    if (!empty($this->session->get_username) && empty($_GET['u'])) {
      $_GET['u'] = $this->session->get_username;
    }
  }
}