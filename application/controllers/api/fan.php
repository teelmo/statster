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

  /* Add a fan */
  public function add() {
    // Load helpers
    $this->load->helper(array('favorites_helper', 'id_helper'));

    echo addFan($_POST);    
  }

  /* Update fan information */
  public function update() {
    // Load helpers
    
  }

  /* Delete fan information */
  public function delete() {
    // Load helpers
    
  }
}
?>