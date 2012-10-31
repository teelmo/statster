<?php
class Tag extends CI_Controller {

  public function index() {
    $data['request'] = array('tag');

    $this->load->view('templates/header');
    $this->load->view('tag/tag_view');
    $this->load->view('templates/footer', $data);
  }

  public function genre($genre = '') {
    $this->load->view('templates/header');
    $this->load->view('tag/genre_view');
    $this->load->view('templates/footer'); 
  }

  public function keyword($keyword = '') {
    $this->load->view('templates/header');
    $this->load->view('tag/keyword_view');
    $this->load->view('templates/footer'); 
  }

  public function release_year($release_year = '') {
    $this->load->view('templates/header');
    $this->load->view('tag/release_year_view');
    $this->load->view('templates/footer'); 
  }

  public function nationality($nationality = '') {
    $this->load->view('templates/header');
    $this->load->view('tag/nationality_view');
    $this->load->view('templates/footer'); 
  }
}
?>