<?php
class Listener extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List users */
  public function get() {
    // Load helpers
    $this->load->helper(array('music_helper', 'output_helper'));
    
    echo getListeners($_REQUEST);
  }
}
?>
