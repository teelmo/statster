<?php
class Artist extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List artists */
  public function get() {
    // Load helpers
    $this->load->helper(array('music_helper', 'output_helper'));

    echo getArtists($_REQUEST);
  }

  /* Add a artist */
  public function add() {
    // Load helpers
    
  }

  /* Update artist information */
  public function update() {
    // Load helpers
    
  }

  /* Delete artist information */
  public function delete() {
    // Load helpers
    
  }
}
?>