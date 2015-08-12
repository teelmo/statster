<?php
class Main extends CI_Controller {

  public function index() {
    if ($this->session->userdata('logged_in') === TRUE) {
      // Load helpers
      $this->load->helper(array('form'));

      $data = array();
      $data['js_include'] = array('main');
      $data['interval'] = 14;
      
      $this->load->view('templates/header');
      $this->load->view('main_view', $data);
      $this->load->view('templates/footer', $data);  
    }
    else {
      // Load helpers
      $this->load->helper(array('form'));

      $data = array();
      $data['js_include'] = array('welcome');

      $this->load->view('templates/header');
      $this->load->view('welcome_view');
      $this->load->view('templates/footer', $data);  
    }
  }

  /* 
   * Meta page's controllers 
   */
  public function about() {
    $this->load->view('templates/header');
    $this->load->view('meta/about_view');
    $this->load->view('templates/footer');  
  }

  public function career() {
    $this->load->view('templates/header');
    $this->load->view('meta/career_view');
    $this->load->view('templates/footer');  
  }

  public function developers() {
    $this->load->view('templates/header');
    $this->load->view('meta/developers_view');
    $this->load->view('templates/footer');  
  }

  public function privacy() {
    $this->load->view('templates/header');
    $this->load->view('meta/privacy_view');
    $this->load->view('templates/footer');  
  }

  public function terms() {
    $this->load->view('templates/header');
    $this->load->view('meta/terms_view');
    $this->load->view('templates/footer');  
  }
}
?>