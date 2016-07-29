<?php
class Year extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List years */
  public function get() {
    // Load helpers
    $this->load->helper(array('year_helper', 'output_helper'));
    echo getYears($_REQUEST);
  }

  /* Add a year */
  public function add() {
    // Load helpers
    
  }

  /* Update year information */
  public function update($type) {
    switch ($type) {
      case 'biography':
        // Load helpers
        $this->load->helper(array('year_helper', 'lastfm_helper'));

        $_REQUEST += fetchTagBio($_REQUEST);
        echo addYearBio($_REQUEST);
        break;
      default:
        break;
    }
  }

  /* Delete year information */
  public function delete() {
    // Load helpers
    
  }
}
?>
