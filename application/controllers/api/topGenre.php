<?php
class PopularGenre extends CI_Controller {
  public function index() {
    // Load helpers
    $this->load->helper(array('music_helper'));
    
    echo getTopGenres($_REQUEST);
  }
}
?>