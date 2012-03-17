<?php
class User extends CI_Controller {

  public function index() {
    $this->load->view('templates/header');
    //$this->load->view('user_view');
    $this->load->view('templates/footer');
  }
}
?>