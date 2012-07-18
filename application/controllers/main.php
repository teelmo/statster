<?php
class Main extends CI_Controller {

  public function index() {
    // http://codeigniter.com/user_guide/libraries/sessions.html
    /*$userdata = array(
                   'username'  => 'teelmo',
                   'email'     => 'teemo.tebest@gmail.com',
                   'logged_in' => TRUE
               );
    //$this->session->set_userdata($userdata);
    */

    if (!$this->session->userdata('logged_in')) {
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
    else {
      // Load helpers
      $this->load->helper(array('form', 'url'));

      $this->load->view('templates/header');
      $this->load->view('welcome_view');
      $this->load->view('templates/footer');  
    }
  }
}
?>