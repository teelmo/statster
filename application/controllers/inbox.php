<?php
class Inbox extends CI_Controller {

  public function index() {
    if ($this->session->userdata('logged_in') !== TRUE) {
      redirect('/login?redirect=inbox', 'refresh');
    }
    $this->load->view('templates/header');
    $this->load->view('inbox/inbox_view');
    $this->load->view('templates/footer');
  }
}
?>