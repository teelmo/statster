<?php
class Nationality extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List nationalities */
  public function get() {
    // Load helpers
    $this->load->helper(array('tag_helper', 'output_helper'));
    
    echo getNationalities($_REQUEST);
  }

  /* Add a nationality */
  public function add() {
    // Load helpers
    
  }

  /* Update nationality information */
  public function update() {
    // Load helpers
    
  }

  /* Delete nationality information */
  public function delete() {
    // Load helpers
    
  }
}
?>