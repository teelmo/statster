<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class RecommentedTopAlbum extends MY_Controller {

  public function index() {
    // Load helpers
    $this->load->helper(array('music_helper', 'output_helper'));

    echo getAlbums($_REQUEST);
  }
}
?>