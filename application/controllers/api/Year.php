<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Year extends MY_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List years */
  public function get($type = '') {
    // Load helpers
    $this->load->helper(array('music_helper', 'year_helper', 'output_helper'));
    switch ($type) {
      case 'yearly':
        echo getTopAlbumByYear($_REQUEST);
        break;
      case 'age':
        echo getAlbumAverageAge($_REQUEST);
        break;
      default:
        echo getYears($_REQUEST);
        break;
    }
  }

  /* Add a year */
  public function add() {
    // Load helpers
    header('HTTP/1.1 501 Not Implemented');
  }

  /* Update year information */
  public function update($type) {
    switch ($type) {
      case 'biography':
        // Load helpers
        $this->load->helper(array('year_helper', 'metadata_helper'));

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
    header('HTTP/1.1 501 Not Implemented');
  }
}
?>