<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Listener extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List listeners */
  public function get($type = '') {
    // Load helpers
    $this->load->helper(array('music_helper', 'output_helper'));

    switch ($type) {
      default:
        echo getListeners($_REQUEST);
        break;
    }
  }
}
?>