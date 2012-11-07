<?php
class ArtistBio extends CI_Controller {
  public function index() {
    // Load helpers
    $this->load->helper(array('lastfm_helper', 'artist_helper'));
    
    echo getArtistsBio($_REQUEST);
  }
}
?>