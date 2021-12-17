<?php
class Love extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List loves */
  public function get($album_id = 0) {
    // Load helpers
    $this->load->helper(array('love_helper', 'output_helper'));
    if (is_numeric($album_id)) {
      $_REQUEST['album_id'] = $album_id;
      echo getLove($_REQUEST);
    }
    else {
      echo getLoves($_REQUEST);
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
      header('HTTP/1.1 400 Bad Request');
    }
  }

  /* Update love information */
  public function update($album_id = FALSE) {
    // Load helpers
    header('HTTP/1.1 501 Not Implemented');
  }

  /* Delete love information */
  public function delete($album_id = FALSE) {
    if (is_numeric($album_id)) {
      // Load helpers
      $this->load->helper(array('love_helper'));

      deleteLove($album_id);
    }
    else {
      header('HTTP/1.1 400 Bad Request');
    }
  }
}
?>