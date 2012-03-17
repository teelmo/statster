<?php
class Main extends CI_Controller {

  public function index() {
    // Load the form helper for add listening functionality
    $this->load->helper('form');

    $data = array();
    // Load recent listenings
    //$data['listenings'] = $this->db->query('SELECT name, title, email FROM my_table');

    $this->load->view('templates/header');
    $this->load->view('main_view', $data);
    $this->load->view('templates/footer');
  }

  public function recentlyListened() {
    $data = $_POST;
    // Load the img_helper helper for showing user and album images
    $this->load->helper('img_helper');
    $this->load->view('templates/album_table', $data);
  }

  public function topAlbum() {
    $data = $_POST;
    // Load the img_helper helper for showing user and album images
    $this->load->helper('img_helper');
    $this->load->view('templates/album_list', $data);
  }

  public function popularAlbum() {
    $data = $_POST;
    // Load the img_helper helper for showing user and album images
    $this->load->helper('img_helper');
    $this->load->view('templates/album_list', $data);
  }

  public function newAlbum() {
    $data = $_POST;
    // Load the img_helper helper for showing user and album images
    $this->load->helper('img_helper');
    $this->load->view('templates/album_list', $data);
  }
}
?>