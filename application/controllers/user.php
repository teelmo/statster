<?php
class User extends CI_Controller {

  public function index() {
    $this->load->view('templates/header');
    $this->load->view('user/user_view');
    $this->load->view('templates/footer');
  }

  public function edit() {
    if ($this->session->userdata('logged_in') !== TRUE) {
      redirect('/login?redirect=user/edit', 'refresh');
    }
    $this->load->view('templates/header');
    $this->load->view('user/edit_view');
    $this->load->view('templates/footer');
  }
}
?>