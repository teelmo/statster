<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Listening extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* Get listenings */
  public function get($type = '') {
    // Load helpers
    $this->load->helper(array('listening_helper', 'love_helper', 'output_helper'));

    switch ($type) {
      case 'cumulative':
        $this->load->helper('music_helper');
        echo getListeningsCumulative($_REQUEST);
        break;
      default:
        echo getListenings($_REQUEST);
        break;
    }
  }

  public function add() {
    // Load helpers
    $this->load->helper(array('listening_helper', 'spotify_helper', 'id_helper', 'album_helper', 'artist_helper', 'output_helper'));

    echo addListening($_POST);
  }

  /* Update listening information */
  public function update() {
    // Load helpers
    header('HTTP/1.1 501 Not Implemented');
  }

  /* Delete listening information */
  public function delete($listening_id) {
    // Load helpers
    $this->load->helper(array('listening_helper'));

    echo deleteListening(array('listening_id' => $listening_id));
  }
}
?>