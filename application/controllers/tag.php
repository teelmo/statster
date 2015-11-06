<?php
class Tag extends CI_Controller {

  public function index() {
    $data['request'] = array('tag');

    $this->load->view('site_templates/header');
    $this->load->view('tag/meta_view');
    $this->load->view('site_templates/footer', $data);
  }

  public function genre($tag_name = '') {
    // Load helpers
    $this->load->helper(array('tag_helper'));

    $data['js_include'] = array('tag');
    $data['tag_type'] = 'Genre';
    $this->load->view('site_templates/header');
    
    if (!empty($tag_name)) {
      $data['tag_name'] = decode($tag_name);
      
      // $data += getGenresMusic();
      
      $this->load->view('tag/tag_view', $data);
    }
    else {
      $this->load->view('tag/tags_view', $data);
    }
    $this->load->view('site_templates/footer');
  }

  public function keyword($tag_name = '') {
    $data['tag_type'] = 'Keyword';
    $this->load->view('site_templates/header');
    
    if (!empty($tag_name)) {
      $data['tag_name'] = decode($tag_name);
      
      $this->load->view('tag/tag_view', $data);
    }
    else {
      $this->load->view('tag/tags_view', $data);
    }
    $this->load->view('site_templates/footer');
  }

  public function year($tag_name = '') {
    $data['tag_type'] = 'Release Year';
    $this->load->view('site_templates/header');
    
    if (!empty($tag_name)) {
      $data['tag_name'] = decode($tag_name);
      
      $this->load->view('tag/tag_view', $data);
    }
    else {
      $this->load->view('tag/tags_view', $data);
    }
    $this->load->view('site_templates/footer');
  }

  public function nationality($tag_name = '') {
    $data['tag_type'] = 'Nationality';
    $this->load->view('site_templates/header');
    
    if (!empty($tag_name)) {
      $data['tag_name'] = decode($tag_name);
      
      $this->load->view('tag/tag_view', $data);
    }
    else {
      $this->load->view('tag/tags_view', $data);
    }
    $this->load->view('site_templates/footer');
  }
}
?>