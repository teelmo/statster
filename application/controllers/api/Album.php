<?php
class Album extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List albums */
  public function get() {
    // Load helpers
    $this->load->helper(array('music_helper', 'output_helper'));
    
    echo getAlbums($_REQUEST);
  }

  /* Add a album */
  public function add() {
    // Load helpers
    
  }

  /* Update artist information */
  public function update($type) {
    // Load helpers
    switch ($type) {
      case 'biography':
        $this->load->helper(array('album_helper'));
        
        $_REQUEST += fetchAlbumBio($_REQUEST);
        echo addAlbumBio($_REQUEST);
        break;
      default:
        break;
    }
  }

  /* Delete album information */
  public function delete() {
    // Load helpers
    
  }

}
?>
