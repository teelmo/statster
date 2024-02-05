<?php
class Format extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  public function get() {
    // Load helpers
    $this->load->helper(array('format_helper', 'output_helper'));

    if ($_REQUEST['format_type_name']) {
      echo getFormatTypeListenings($_REQUEST);
    }
    else if ($_REQUEST['format_name']) {
      echo getFormatListenings($_REQUEST);
    }
    else {
      echo getListeningFormat($_REQUEST);
    }
  }
}
?>