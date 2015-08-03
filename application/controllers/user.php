<?php
class User extends CI_Controller {

  public function index() {
    $this->load->view('templates/header');
    $this->load->view('user/user_view');
    $this->load->view('templates/footer');
  }

  public function profile($username) {
    $data['username'] = $username;
    $this->load->view('templates/header');
    $this->load->view('user/profile_view', $data);
    $this->load->view('templates/footer');
  }

  public function edit() {
    if ($this->session->userdata('logged_in') !== TRUE) {
      redirect('/login?redirect=user/edit', 'refresh');
    }

    // Load helpers
    $this->load->helper(array('form'));
      
    $this->load->view('templates/header');
    $this->load->view('user/edit_view');
    $this->load->view('templates/footer');
  }
}
?>