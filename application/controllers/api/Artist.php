<?php
class Artist extends CI_Controller {

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
    // Load helpers
    header('HTTP/1.1 501 Not Implemented');
  }

  /* Update artist information */
  public function update($type) {
    switch ($type) {
      case 'biography':
        // Load helpers
        $this->load->helper(array('artist_helper', 'lastfm_helper'));

        $_REQUEST += fetchArtistInfo($_REQUEST, array('bio', 'image'));
        echo addArtistBio($_REQUEST);
        break;
      default:
        break;
    }
  }

  /* Delete artist information */
  public function delete() {
    // Load helpers
    $this->load->helper(array('album_helper'));

    deleteAlbum($_REQUEST);
  }
}
?>