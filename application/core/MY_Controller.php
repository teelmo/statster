<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

// application/core/MY_Controller.php
class MY_Controller extends CI_Controller {
  public function __construct() {
    parent::__construct();
    if (!empty($this->session->get_username) && empty($_GET['u'])) {
      $_GET['u'] = $this->session->get_username;
    }
  }
}

require_once APPPATH . 'core/MY_ReadOnly_Controller.php';