<?php
class Tag extends CI_Controller {

  public function index() {
    $this->load->view('templates/header');
    $this->load->view('tag/tag_view');
    $this->load->view('templates/footer');
  }

}
?>