<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Artist extends MY_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List artists */
  public function get($type = FALSE) {
    // Load helpers
    $this->load->helper(array('music_helper', 'output_helper'));

    switch ($type) {
      case 'count':
        echo getListeningCount($_REQUEST, 'artist');
        break;
      case 'unique':
        echo getArtistUnique($_REQUEST);
        break;
      default:
        echo getArtists($_REQUEST);
        break;
    }
  }

  /* Add a artist */
  public function add() {
    if ($this->session->userdata('logged_in') === TRUE) {
      // Load helpers
      $this->load->helper(array('artist_helper', 'spotify_helper'));
      
      $_REQUEST['user_id'] = $this->session->userdata('user_id');
      echo addArtist($_REQUEST);
    }
    else {
      show_404();
    }
  }

  /* Update artist information */
  public function update($type) {
    if ($this->session->userdata('logged_in') === TRUE) {
      switch ($type) {
        case 'biography':
          // Load helpers
          $this->load->helper(array('artist_helper', 'metadata_helper'));

          $_REQUEST += fetchArtistInfo($_REQUEST, array('bio'));
          echo addArtistBio($_REQUEST);
          break;
        default:
          break;
      }
    }
    else {
      show_404();
    }
  }

  /* Delete artist information */
  public function delete() {
    if (in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
      // Load helpers
      $this->load->helper(array('artist_helper'));

      deleteArtist($_REQUEST);
    }
    else {
      show_404();
    }
  }
}
?>