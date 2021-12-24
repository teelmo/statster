<?php
class Genre extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List genres */
  public function get($type = '') {
    // Load helpers
    $this->load->helper(array('genre_helper', 'output_helper'));
    
    if ($type === 'all') {
      echo getAllGenres($_REQUEST);
    }
    else {
      echo getGenres($_REQUEST);
    }
  }

  /* Add a genre */
  public function add() {
    // Load helpers
    $this->load->helper(array('genre_helper', 'output_helper'));

    echo addGenre($_REQUEST);
  }

  /* Update genre information */
  public function update($type) {
    if ($this->session->userdata('logged_in') === TRUE) {
      switch ($type) {
        case 'biography':
          // Load helpers
          $this->load->helper(array('genre_helper', 'lastfm_helper'));

          $_REQUEST += fetchTagBio($_REQUEST);
          echo addGenreBio($_REQUEST);
          break;
        default:
          break;
      }
    }
    else {
      show_403();
    }
  }

  /* Delete genre information */
  public function delete() {
    if ($this->session->userdata('logged_in') === TRUE) {
      // Load helpers
      $this->load->helper(array('genre_helper'));

      echo deleteAlbumGenre($_REQUEST);
    }
    else {
      show_403();
    }
  }
}
?>