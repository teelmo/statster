<?php
class Logout extends CI_Controller {

  public function index() {
    // Load helpers.
    $this->load->helper(array('user_helper'));

    // Redirect user to the first page if logout successful.
    $logout = logoutUser();
    if ($logout == 1) {
      redirect('/', 'refresh');
    }
    else {
      echo $logout;
    }
  }
}
?>
