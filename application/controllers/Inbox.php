<?php
class Inbox extends CI_Controller {

  public function index() {
    if ($this->session->userdata('logged_in') !== TRUE) {
      redirect('/login?redirect=inbox', 'refresh');
    }
    // Load helpers.
    $this->load->helper(array('form'));

    $this->load->view('site_templates/header');
    $this->load->view('inbox/inbox_view');
    $this->load->view('site_templates/footer', $data);
  }
}
?>
