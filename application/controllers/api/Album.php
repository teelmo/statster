<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Album extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List albums */
  public function get($type = FALSE) {
    // Load helpers
    $this->load->helper(array('music_helper', 'output_helper'));
    
    switch ($type) {
      case 'count':
        echo getListeningCount($_REQUEST, 'album');
        break;
      default:
        echo getAlbums($_REQUEST);
        break;
    }
  }

  /* Add a album */
  public function add() {
    // Load helpers
    header('HTTP/1.1 501 Not Implemented');
  }

  /* Update album information */
  public function update($type) {
    if ($this->session->userdata('logged_in') === TRUE) {
      // Load helpers
      switch ($type) {
        case 'biography':
          $this->load->helper(array('album_helper', 'metadata_helper'));
          
          $_REQUEST += fetchAlbumInfo($_REQUEST, array('bio'));
          echo addAlbumBio($_REQUEST);
          break;
        default:
          break;
      }
    }
    else {
      show_404();
    }
  }

  /* Delete album information */
  public function delete() {
    if (in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
      // Load helpers
      $this->load->helper(array('album_helper'));
      
      deleteAlbum($_REQUEST);
    }
    else {
      show_404();
    }
  }


  /* Delete album information */
  public function transfer() {
    if (in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
      // Load helpers
      $this->load->helper(array('album_helper'));

      transferAlbumData($_REQUEST);
    }
    else {
      show_404();
    }
  }
}
?>