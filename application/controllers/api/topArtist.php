<?php
class TopArtist extends CI_Controller {
  public function index() {
    // Load helpers
    $this->load->helper(array('music_helper', 'return_helper'));
    
    echo getTopArtists($_REQUEST);
  }
}
?>