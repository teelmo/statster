<?php
class User extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List users */
  public function get() {
    // Load helpers
    $this->load->helper(array('music_helper', 'output_helper'));
    
    echo getListeners($_REQUEST);
  }

  /* Add a user */
  public function add() {
    // Load helpers
    
  }

  /* Update user information */
  public function update() {
    // Load helpers
    
  }

  /* Delete user information */
  public function delete() {
    // Load helpers
    
  }
}
?>