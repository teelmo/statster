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
    $data['redirect'] = empty($_GET['redirect']) ? '/' : $_GET['redirect'];
    $data['js_include'] = array('login');

    $this->load->view('site_templates/header');
    $this->load->view('login_view', $data);
    $this->load->view('site_templates/footer', $data);  
  }
}
?>
