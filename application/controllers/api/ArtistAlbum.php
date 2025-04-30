<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class ArtistAlbum extends CI_Controller {

  public function index() {
    // Load helpers
    $this->load->helper(array('music_helper', 'output_helper'));
    
    echo getArtistAlbums($_REQUEST);
  }
}
?>