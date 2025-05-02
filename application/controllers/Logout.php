<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Logout extends MY_Controller {

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
