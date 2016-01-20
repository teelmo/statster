<?php
class Love extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List loves */
  public function get($album_id = 0) {
    if (is_numeric($album_id)) {
      // Load helpers
      $this->load->helper(array('love_helper', 'output_helper'));
      
      $_REQUEST['album_id'] = $album_id;
      echo getLove($_REQUEST);
    }
    else {
      header("HTTP/1.1 400 Bad Request");
    }
  }

  /* Add a love */
  public function add($album_id = FALSE) {
    if (is_numeric($album_id)) {
      // Load helpers
      $this->load->helper(array('love_helper'));

      echo addLove($album_id);
    }
    else {
      header("HTTP/1.1 400 Bad Request");
    }
  }

  /* Update love information */
  public function update($album_id = FALSE) {
    header("HTTP/1.1 501 Not Implemented");
    if (is_numeric($album_id)) {
      // Load helpers
    }
  }

  /* Delete love information */
  public function delete($album_id = FALSE) {
    if (is_numeric($album_id)) {
      // Load helpers
      $this->load->helper(array('love_helper'));

      deleteLove($album_id);
    }
    else {
      header("HTTP/1.1 400 Bad Request");
    }
  }
}
?>
