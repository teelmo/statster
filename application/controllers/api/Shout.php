<?php
class Shout extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List shouts */
  public function get($type) {
    // Load helpers
    $this->load->helper(array('shout_helper', 'output_helper'));

    switch ($type) {
      case 'album':
        echo getAlbumShout($_REQUEST);
        break;
      case 'artist':
        echo getArtistShout($_REQUEST);
        break;
      case 'user':
        echo getUserShout($_REQUEST);
        break;
      default:
        echo getShouts($_REQUEST);
        break;
    }
  }

  /* Add a shout */
  public function add() {
    // Load helpers
    $this->load->helper(array('shout_helper'));

    echo addShout($_POST);
  }

  /* Update shout information */
  public function update() {
    // Load helpers
    
  }

  /* Delete shout information */
  public function delete($type, $shout_id) {
    // Load helpers
    $this->load->helper(array('shout_helper'));

    switch ($type) {
      case 'album':
        echo deleteComment(array('shout_id' => $shout_id, 'type' => $type));
        break;
      case 'artist':
        echo deleteComment(array('shout_id' => $shout_id, 'type' => $type));
        break;
      case 'user':
        echo deleteComment(array('shout_id' => $shout_id, 'type' => $type));
        break;
      default:
        header("HTTP/1.1 400 Bad Request");
        break;
    }
  }
}
?>
