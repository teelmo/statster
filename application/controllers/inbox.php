<?php
class Inbox extends CI_Controller {

  public function index() {
    $this->load->view('templates/header');
    $this->load->view('inbox/inbox_view');
    $this->load->view('templates/footer');
  }
}
?>