<?php
class Genre extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List genres */
  public function get() {
    // Load helpers
    $this->load->helper(array('genre_helper', 'output_helper'));
    
    echo getGenres($_REQUEST);
  }

  /* Add a genre */
  public function add() {
    // Load helpers
    
  }

  /* Update genre information */
  public function update() {
    // Load helpers
    
  }

  /* Delete genre information */
  public function delete() {
    // Load helpers
    
  }
}
?>
