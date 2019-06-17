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
    header('HTTP/1.1 501 Not Implemented');
  }

  /* Update genre information */
  public function update($type) {
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

  /* Delete genre information */
  public function delete() {
    // Load helpers
    $this->load->helper(array('genre_helper'));

    echo deleteGenre($_REQUEST);
  }
}
?>
