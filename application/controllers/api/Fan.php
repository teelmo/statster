<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Fan extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List fans */
  public function get($artist_id = 0) {
    // Load helpers
    $this->load->helper(array('fan_helper', 'output_helper'));
    if (is_numeric($artist_id)) {
      $_REQUEST['artist_id'] = $artist_id;
      echo getFan($_REQUEST);
    }
    else {
      echo getFans($_REQUEST);
    }
  }

  /* Add a fan */
  public function add($artist_id = FALSE) {
    if ($this->session->userdata('logged_in') === TRUE) {
      if (is_numeric($artist_id)) {
        // Load helpers
        $this->load->helper(array('fan_helper'));

        echo addFan($artist_id);
      }
      else {
        header('HTTP/1.1 400 Bad Request');
      }
    }
    else {
      show_404();
    }
  }

  /* Update fan information */
  public function update($artist_id = FALSE) {
    header('HTTP/1.1 501 Not Implemented');
    if (is_numeric($artist_id)) {
      // Load helpers

    }
  }

  /* Delete fan information */
  public function delete($artist_id = FALSE) {
    if ($this->session->userdata('logged_in') === TRUE) {
      if (is_numeric($artist_id)) {
        // Load helpers
        $this->load->helper(array('fan_helper'));

        deleteFan($artist_id);      
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