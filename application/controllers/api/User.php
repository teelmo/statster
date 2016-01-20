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
  }

  /* Update user information */
  public function update() {
    // Load helpers
    
  }

  /* Delete suer information */
  public function delete($listening_id) {
    // Load helpers
  }
}
?>
