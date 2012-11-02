<?php
class Ajax extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  public function chartTable() {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'music_helper', 'text_helper'));

      $data = $_POST;
      $this->load->view('templates/chart_table', $data);
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function albumList($size = 124) {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper'));

      $data = $_POST;
      $this->load->view('templates/album_list', $data);
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function artistList($size = 124) {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper'));

      $data = $_POST;
      $this->load->view('templates/artist_list', $data);
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function albumTable() {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'text_helper'));

      $data = $_POST;
      $this->load->view('templates/album_table', $data);
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function artistBar() {
    if (!empty($_POST)) {
      $data = $_POST;
      $this->load->view('templates/bar_table', $data);
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function albumBar() {
    if (!empty($_POST)) {
      $data = $_POST;
      $this->load->view('templates/bar_table', $data);
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function popularTag() {
    if (!empty($_POST)) {
      $data = $_POST;
      $this->load->view('templates/tag_table', $data);
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function albumLove() {
    if (!empty($_POST)) {
      $data = $_POST;
      $this->load->view('templates/list_like', $data);
    }
    else {
      exit ('No direct script access allowed');
    }
  }

  public function artistFan() {
    if (!empty($_POST)) {
      $data = $_POST;
      $this->load->view('templates/list_like', $data);
    }
    else {
      exit ('No direct script access allowed');
    }
  }
}
?>