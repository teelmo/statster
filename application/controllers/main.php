<?php
class Main extends CI_Controller {

  public function index() {
    // Load the form helper for add listening functionality
    $this->load->helper(array('form', 'url'));

    $this->load->view('templates/header');
    $this->load->view('main_view');
    $this->load->view('templates/footer');
  }

  public function recentlyListened() {
    if(!empty($_POST)) {
      $data = $_POST;
      // Load the img_helper helper for showing user and album images
      $this->load->helper('img_helper');
      $this->load->view('templates/album_table', $data);
    }
    else {
      exit('No direct script access allowed');
    }
  }

  public function topAlbum() {
    if(!empty($_POST)) {
      $data = $_POST;
      // Load the img_helper helper for showing user and album images
      $this->load->helper('img_helper');
      $this->load->view('templates/album_list', $data);
    }
    else {
      exit('No direct script access allowed');
    }
  }

  public function popularAlbum() {
    if(!empty($_POST)) {
      $data = $_POST;
      // Load the img_helper helper for showing user and album images
      $this->load->helper('img_helper');
      $this->load->view('templates/album_list', $data);
    }
    else {
      exit('No direct script access allowed');
    }
  }

  public function newAlbum() {
    if(!empty($_POST)) {
      $data = $_POST;
      // Load the img_helper helper for showing user and album images
      $this->load->helper('img_helper');
      $this->load->view('templates/album_list', $data);
    }
    else {
      exit('No direct script access allowed');
    }
  }
}
?>