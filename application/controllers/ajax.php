<?php
class Ajax extends CI_Controller {

  public function index() {
    exit('No direct script access allowed');
  }

  public function recentlyListened() {
    if(!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'url', 'music_helper'));

      $data = $_POST;
      $this->load->view('templates/chart_table', $data);
    }
    else {
      exit('No direct script access allowed');
    }
  }

  public function topAlbumList() {
    if(!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'url'));

      $data = $_POST;
      $this->load->view('templates/album_list', $data);
    }
    else {
      exit('No direct script access allowed');
    }
  }

  public function topArtistBar() {
    if(!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('url'));

      $data = $_POST;
      $this->load->view('templates/bar_table', $data);
    }
    else {
      exit('No direct script access allowed');
    }
  }

  public function newAlbum() {
    if(!empty($_POST)) {
      // Load helpers
      $this->load->helper('img_helper');

      $data = $_POST;
      $this->load->view('templates/album_list', $data);
    }
    else {
      exit('No direct script access allowed');
    }
  }

  public function popularGenre() {
    if(!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('url'));

      $data = $_POST;
      $this->load->view('templates/genre_table', $data);
    }
    else {
      exit('No direct script access allowed');
    }
  }

  public function popularAlbum() {
    if(!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'url'));

      $data = $_POST;
      $this->load->view('templates/album_table', $data);
    }
    else {
      exit('No direct script access allowed');
    }
  }
}
?>