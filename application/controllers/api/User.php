<?php
class User extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  public function get() {
    // Load helpers
    $this->load->helper(array('user_helper', 'output_helper'));

    echo getUsers($_REQUEST);
  }

  public function add() {
    // Load helpers
    header('HTTP/1.1 501 Not Implemented');
  }

  /* Update user information */
  public function update($type) {
    // Load helpers
    $this->load->helper(array('user_helper'));

    if ($_REQUEST['user_id'] = $this->session->userdata('user_id')) {
      echo updateIntervals($_REQUEST);
    }
    else {
      header('HTTP/1.1 403 Forbidden');
    }

  }

  /* Delete user information */
  public function delete($user_id) {
    // Load helpers
    header('HTTP/1.1 501 Not Implemented');
  }
}
?>
