<?php
class Register extends CI_Controller {

  public function index() {
    // Load helpers
    $this->load->helper(array('user_helper'));
    
    echo registerUser($_POST);
  }
}
?>