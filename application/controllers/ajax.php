<?php
class Ajax extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  public function chartTable() {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'love_helper', 'output_helper', 'return_helper'));

      $this->load->view('templates/chart_table', $_POST);
      header("HTTP/1.1 200 OK");
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function albumList($size = 124) {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper'));

      $this->load->view('templates/album_list', $_POST);
      header("HTTP/1.1 200 OK");
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function artistList($size = 124) {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper'));

      $this->load->view('templates/artist_list', $_POST);
      header("HTTP/1.1 200 OK");
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function albumTable() {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'output_helper'));

      $this->load->view('templates/album_table', $_POST);
      header("HTTP/1.1 200 OK");
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function userTable() {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'output_helper'));

      $this->load->view('templates/user_table', $_POST);
      header("HTTP/1.1 200 OK");
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function eventTable() {
    if (!empty($_POST)) {
      $this->load->view('templates/event_table', $_POST);
      header("HTTP/1.1 200 OK");
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function artistBar() {
    if (!empty($_POST)) {
      $this->load->view('templates/bar_table', $_POST);
      header("HTTP/1.1 200 OK");
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function albumBar() {
    if (!empty($_POST)) {
      $this->load->view('templates/bar_table', $_POST);
      header("HTTP/1.1 200 OK");
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function popularTag() {
    if (!empty($_POST)) {
      $this->load->view('templates/tag_table', $_POST);
      header("HTTP/1.1 200 OK");
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function albumLove() {
    if (!empty($_POST)) {
      $this->load->view('templates/list_like', $_POST);
      header("HTTP/1.1 200 OK");
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function artistFan() {
    if (!empty($_POST)) {
      $this->load->view('templates/list_like', $_POST);
      header("HTTP/1.1 200 OK");
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function artistBio() {
    if (!empty($_POST)) {
      $this->load->view('templates/artist_bio', $_POST);
      header("HTTP/1.1 200 OK");
    }
    else {
      exit ('No direct script access allowed');
    }
  }
}
?>