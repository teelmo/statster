<?php
class Format extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  public function get() {
    // Load helpers
    $this->load->helper(array('format_helper', 'output_helper'));

    echo getListeningFormat($_REQUEST);
  }
}
?>
