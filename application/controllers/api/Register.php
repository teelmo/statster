<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Register extends MY_Controller {

  public function index() {
    // Load helpers
    $this->load->helper(array('user_helper'));
    
    echo registerUser($_POST);
  }
}
?>