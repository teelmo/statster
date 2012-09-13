<?php
class TopGenre extends CI_Controller {
  public function index() {
    // Load helpers
    $this->load->helper(array('tag_helper', 'return_helper'));
    
    echo getTopGenres($_REQUEST);
  }
}
?>