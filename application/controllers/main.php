<?php
class Main extends CI_Controller {

  public function index() {
    // Load helpers
    $this->load->helper(array('form', 'url'));

    $this->load->view('templates/header');
    $this->load->view('main_view');
    $this->load->view('templates/footer');
  }
}
?>