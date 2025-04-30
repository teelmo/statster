<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class AssociatedArtist extends CI_Controller {

  public function index() {
    // Load helpers
    $this->load->helper(array('artist_helper', 'output_helper'));
    
    echo getAssociatedArtists($_REQUEST);
  }
}
?>