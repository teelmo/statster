<?php
class Year extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List years */
  public function get() {
    // Load helpers
    $this->load->helper(array('year_helper', 'output_helper'));
    echo getYears($_REQUEST);
  }

  /* Add a year */
  public function add() {
    // Load helpers
    
  }

  /* Update year information */
  public function update() {
    // Load helpers
    
  }

  /* Delete year information */
  public function delete() {
    // Load helpers
    
  }
}
?>
