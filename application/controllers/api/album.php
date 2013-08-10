<?php
class Album extends CI_Controller {
  public function index() {
    exit ('No direct script access allowed');
  }

  /* List albums */
  public function get() {
    // Load helpers
    $this->load->helper(array('music_helper', 'output_helper'));
    
    echo getAlbums($_REQUEST);
  }

  /* Add a album */
  public function add() {
    // Load helpers
    
  }

  /* Update album information */
  public function update() {
    // Load helpers
    
  }

  /* Delete album information */
  public function delete() {
    // Load helpers
    
  }

}
?>