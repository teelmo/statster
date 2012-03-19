<?php
class Album extends CI_Controller {

  public function index() {
    // Load helpers
    $this->load->helper(array('url'));

    $this->load->view('templates/header');
    $this->load->view('albums_view');
    $this->load->view('templates/footer');
  }

}
?>