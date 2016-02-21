<?php
class Like extends CI_Controller {

  public function index() {
    // Load helpers
    $this->load->helper(array());

    $data = array();
    $data['js_include'] = array('like');

    $this->load->view('site_templates/header');
    $this->load->view('like/like_view', $data);
    $this->load->view('site_templates/footer', $data);
  }

  public function love() {
    // Load helpers
    $this->load->helper(array());

    $data = array();
    $data['js_include'] = array('love');

    $this->load->view('site_templates/header');
    $this->load->view('like/love_view', $data);
    $this->load->view('site_templates/footer', $data);
  }

  public function fan() {
    // Load helpers
    $this->load->helper(array());

    $data = array();
    $data['js_include'] = array('fan');

    $this->load->view('site_templates/header');
    $this->load->view('like/fan_view', $data);
    $this->load->view('site_templates/footer', $data);
  }
}