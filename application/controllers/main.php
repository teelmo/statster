<?php
class Main extends CI_Controller {

  public function index() {
    // Load helpers
    $this->load->helper(array('form', 'url'));

    $data = array();
    $data['cur_date'] = date('Y-m-d');
    $data['request'] = 'main';
    $data['interval'] = 12;
    
    $this->load->view('templates/header');
    $this->load->view('main_view', $data);
    $this->load->view('templates/footer', $data);
  }
}
?>