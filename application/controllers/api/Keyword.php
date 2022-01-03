<?php
class Keyword extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List keywords */
  public function get($type = '') {
    // Load helpers
    $this->load->helper(array('keyword_helper', 'output_helper'));
    
    if ($type === 'all') {
      echo getAllKeywords($_REQUEST);
    }
    else {
      echo getKeywords($_REQUEST);
    }
  }

  /* Add a keyword */
  public function add() {
    // Load helpers
    $this->load->helper(array('keyword_helper', 'output_helper'));
    
    echo addKeyword($_REQUEST);
  }

  /* Update keyword information */
  public function update($type) {
    if ($this->session->userdata('logged_in') === TRUE) {
      switch ($type) {
        case 'biography':
          // Load helpers
          $this->load->helper(array('keyword_helper', 'lastfm_helper'));

          $_REQUEST += fetchTagBio($_REQUEST);
          echo addKeywordBio($_REQUEST);
          break;
        default:
          break;
      }
    }
    else {
      show_404();
    }
  }

  /* Delete keyword information */
  public function delete() {
    if ($this->session->userdata('logged_in') === TRUE) {
      // Load helpers
      $this->load->helper(array('keyword_helper'));

      echo deleteAlbumKeyword($_REQUEST);
    }
    else {
      show_404();
    }
  }
}
?>