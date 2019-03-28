<?php
class Listener extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List users */
  public function get($type = '') {
    // Load helpers
    $this->load->helper(array('music_helper', 'output_helper'));

    switch ($type) {
      case 'cumulative':
        echo getListenersCumulative($_REQUEST);
        break;
      default:
        echo getListeners($_REQUEST);
        break;
    }
  }
}
?>
