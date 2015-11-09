<?php
class Lastfm extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  public function getEvents() {
    // Load helpers
    $this->load->helper(array('lastfm_helper', 'artist_helper'));
    
    echo getEvents($_REQUEST);
  }

  public function getBio() {
    // Load helpers
    $this->load->helper(array('lastfm_helper', 'artist_helper'));
    
    echo getBio($_REQUEST);
  }

  public function getSimilar() {
    // Load helpers
    $this->load->helper(array('lastfm_helper', 'artist_helper'));
    
    echo getSimilar($_REQUEST);
  }
}
?>