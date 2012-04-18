<?php
class User extends CI_Controller {

  public function index() {
    $this->load->view('templates/header');
    $this->load->view('user_view');
    $this->load->view('templates/footer');
  }

   public function profile() {
    $this->load->view('templates/header');
    $this->load->view('profile_view');
    $this->load->view('templates/footer');
  }
}
?>