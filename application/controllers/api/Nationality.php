<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Nationality extends MY_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List nationalities */
  public function get($type = '') {
    // Load helpers
    $this->load->helper(array('nationality_helper', 'output_helper'));
    
    switch ($type) {
      case 'all':
        echo getAllNationalities($_REQUEST);
        break;
      case 'artist':
        echo getTopArtistByNationality($_REQUEST);
        break;
      default:
        echo getNationalities($_REQUEST);
        break;
    }
  }

  /* Add a nationality */
  public function add() {
    // Load helpers
    header('HTTP/1.1 501 Not Implemented');
  }

  /* Update nationality information */
  public function update($type) {
    if ($this->session->userdata('logged_in') === TRUE) {
      switch ($type) {
        case 'biography':
          // Load helpers
          $this->load->helper(array('nationality_helper', 'metadata_helper'));

          $_REQUEST += fetchTagBio($_REQUEST);
          echo addNationalityBio($_REQUEST);
          break;
        default:
          break;
      }
    }
    else {
      show_404();
    }
  }

  /* Delete nationality information */
  public function delete() {
    if ($this->session->userdata('logged_in') === TRUE) {
      // Load helpers
      $this->load->helper(array('nationality_helper'));

      echo deleteAlbumNationality($_REQUEST);
    }
    else {
      show_404();
    }
  }
}
?>