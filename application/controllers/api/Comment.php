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
    $this->load->helper(array('comment_helper', 'id_helper'));

    switch ($type) {
      case 'album':
        echo addComment($_POST);
        break;
      case 'artist':
        echo addComment($_POST);
        break;
      case 'user':
        echo addComment($_POST);
        break;
      default:
        header("HTTP/1.1 400 Bad Request");
        break;
    }
    
  }

  /* Update comment information */
  public function update() {
    // Load helpers
    
  }

  /* Delete comment information */
  public function delete($type, $comment_id) {
    // Load helpers
    $this->load->helper(array('comment_helper'));

    switch ($type) {
      case 'album':
        echo deleleComment(array('comment_id' => $comment_id, 'type' => $type));
        break;
      case 'artist':
        echo deleteComment(array('comment_id' => $comment_id, 'type' => $type));
        break;
      case 'user':
        echo deleteComment(array('comment_id' => $comment_id, 'type' => $type));
        break;
      default:
        header("HTTP/1.1 400 Bad Request");
        break;
    }
  }
}
?>
