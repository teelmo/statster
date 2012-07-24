<?php
class Logout extends CI_Controller {

  public function index() {
    // Load helpers
    $this->load->helper(array('user_helper'));

    echo logoutUser();

  }
}
?>