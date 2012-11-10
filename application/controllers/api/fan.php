<?php
class Fan extends CI_Controller {
  public function index() {
    exit ('No direct script access allowed');
  }

  /* List fans */
  public function get() {
    // Load helpers
    $this->load->helper(array('favorites_helper', 'return_helper'));
    
    echo getFan($_REQUEST);
  }

  public function add() {
    // Load helpers
    
  }

  /* Update love information */
  public function update() {
    // Load helpers
    
  }

  /* Delete love information */
  public function delete() {
    // Load helpers
    
  }
}
?>