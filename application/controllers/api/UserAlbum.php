<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class UserAlbum extends MY_ReadOnly_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* Gets which format(s) an album is owned in */
  public function get($album_id = FALSE) {
    if (is_numeric($album_id)) {
      // Load helpers
      $this->load->helper(array('user_album_helper', 'output_helper'));

      $_REQUEST['album_id'] = $album_id;
      $_REQUEST['user_id'] = $this->session->userdata('user_id');
      echo getOwnedAlbum($_REQUEST);
    }
    else {
      header('HTTP/1.1 400 Bad Request');
    }
  }

  /* Marks an album as owned in a format */
  public function add($album_id = FALSE) {
    if ($this->session->userdata('logged_in') === TRUE) {
      if (is_numeric($album_id) && !empty($_POST['format_id']) && is_numeric($_POST['format_id'])) {
        // Load helpers
        $this->load->helper(array('user_album_helper'));

        echo addOwnedAlbum($album_id, $_POST['format_id']);
      }
      else {
        header('HTTP/1.1 400 Bad Request');
      }
    }
    else {
      show_404();
    }
  }

  /* Unmarks an album as owned in a format */
  public function delete($album_id = FALSE) {
    if ($this->session->userdata('logged_in') === TRUE) {
      if (is_numeric($album_id) && !empty($_POST['format_id']) && is_numeric($_POST['format_id'])) {
        // Load helpers
        $this->load->helper(array('user_album_helper'));

        deleteOwnedAlbum($album_id, $_POST['format_id']);
      }
      else {
        header('HTTP/1.1 400 Bad Request');
      }
    }
    else {
      show_404();
    }
  }
}
?>