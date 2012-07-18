<?php
class ListArtist extends CI_Controller {
  public function index() {
    // Load helpers
    $this->load->helper(array('music_helper'));
    
    echo getListenedArtists($_REQUEST);
  }
}
?>