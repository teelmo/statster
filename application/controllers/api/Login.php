<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {

  public function index() {
    // Load helpers
    $this->load->helper(array('user_helper', 'img_helper'));
    
    echo loginUser($_POST);
  }
}
?>