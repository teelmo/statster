<?php
class Nationality extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List nationalities */
  public function get($type = false) {
    // Load helpers
    $this->load->helper(array('nationality_helper', 'output_helper'));
    
    switch ($type) {
      case 'listenings':
        echo getNationalitiesListenings($_REQUEST);
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
    switch ($type) {
      case 'biography':
        // Load helpers
        $this->load->helper(array('nationality_helper', 'lastfm_helper'));

        $_REQUEST += fetchTagBio($_REQUEST);
        echo addNationalityBio($_REQUEST);
        break;
      default:
        break;
    }
  }

  /* Delete nationality information */
  public function delete() {
    // Load helpers
    $this->load->helper(array('nationality_helper'));

    echo deleteNationality($_REQUEST);
    
  }
}
?>
