<?php
class Comment extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List comments */
  public function get($type) {
    // Load helpers
    $this->load->helper(array('comment_helper', 'output_helper'));
    switch ($type) {
      case 'album':
        echo getAlbumComment($_REQUEST);
        break;
      case 'artist':
        echo getArtistComment($_REQUEST);
        break;
      case 'user':
        echo getUserComment($_REQUEST);
        break;
      default:
        echo getComments($_REQUEST);
        break;
    }
  }

  /* Add a comment */
  public function add() {
    // Load helpers
    
  }

  /* Update comment information */
  public function update() {
    // Load helpers
    
  }

  /* Delete comment information */
  public function delete() {
    // Load helpers
    
  }
}
?>
