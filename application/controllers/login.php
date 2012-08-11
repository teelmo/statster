<?php
class Login extends CI_Controller {

  public function index() {
    // Check that user has not already logged in.
    if ($this->session->userdata('logged_in') === TRUE) {
      $redirect = empty($_GET['redirect']) ? '/' : $_GET['redirect'];
      redirect($redirect, 'refresh');
    }

    // Load helpers
    $this->load->helper(array('form'));

    $data = array();
    $data['request'] = 'login';

    $this->load->view('templates/header');
    $this->load->view('login_view');
    $this->load->view('templates/footer', $data);  
  }
}
?>