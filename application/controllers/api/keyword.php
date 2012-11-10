<?php
class Genre extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List keywords */
  public function get() {
    // Load helpers
    $this->load->helper(array('tag_helper', 'return_helper'));
    
    echo getKeywords($_REQUEST);
  }

  /* Add a keyword */
  public function add() {
    // Load helpers
    
  }

  /* Update keyword information */
  public function update() {
    // Load helpers
    
  }

  /* Delete keyword information */
  public function delete() {
    // Load helpers
    
  }
}
?>