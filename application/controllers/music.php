<?php
class Music extends CI_Controller {

  public function index() {
    $this->load->view('templates/header');
    //$this->load->view('music_view');
    $this->load->view('templates/footer');
  }
}
?>