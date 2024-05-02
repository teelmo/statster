<?php
class Lastfm extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  public function getEvents() {
    // Load helpers
    $this->load->helper(array('metadata_helper'));
    
    echo getEvents($_REQUEST);
  }

  public function fetchSimilar() {
    // Load helpers
    $this->load->helper(array('metadata_helper', 'artist_helper'));
    
    echo fetchSimilar($_REQUEST);
  }
}
?>