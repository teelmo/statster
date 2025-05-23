<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax extends MY_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  public function selectYourself($type) {
    switch ($type) {
      case 'get':
        echo (!empty($_SESSION['get_username'])) ? $_SESSION['get_username'] : '';
        header('HTTP/1.1 200 OK');
      case 'add':
      case 'update':
        $_SESSION['get_username'] = $_SESSION['username'];
        header('HTTP/1.1 200 OK');
        break;
      case 'delete':
        unset($_SESSION['get_username']);
        header('HTTP/1.1 200 OK');
        break;
      default:
        header('HTTP/1.1 200 OK');
        break;
    }
  }


  public function cache($type) {
    switch ($type) {
      case 'delete':
        $this->cache->file->clean();
        header('HTTP/1.1 200 OK');
        break;
      default:
        header('HTTP/1.1 200 OK');
        break;
    }
  }

  public function musicTable() {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'music_helper', 'love_helper', 'output_helper'));

      $this->load->view('templates/music_table', $_POST);
      header('HTTP/1.1 200 OK');
    }
    else {
      exit (ERR_NO_RESULTS);
    }
  }
  
  public function musicWall() {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'music_helper', 'output_helper'));

      $this->load->view('templates/music_wall', $_POST);
      header('HTTP/1.1 200 OK');
    }
    else {
      exit (ERR_NO_RESULTS);
    }
  }

  public function musicBar() {
    if (!empty($_POST)) {
      $this->load->view('templates/music_bar', $_POST);
      header('HTTP/1.1 200 OK');
    }
    else {
      exit (ERR_NO_RESULTS);
    }
  }

  public function albumList() {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'music_helper', 'output_helper'));

      $this->load->view('templates/album_list', $_POST);
      header('HTTP/1.1 200 OK');
    }
    else {
      exit (ERR_NO_RESULTS);
    }
  }

  public function artistList() {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper',  'id_helper', 'output_helper'));

      $this->load->view('templates/artist_list', $_POST);
      header('HTTP/1.1 200 OK');
    }
    else {
      exit (ERR_NO_RESULTS);
    }
  }

  public function sideTable() {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'music_helper', 'output_helper'));

      $this->load->view('templates/side_table', $_POST);
      header('HTTP/1.1 200 OK');
    }
    else {
      exit (ERR_NO_RESULTS);
    }
  }

  public function userTable() {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'output_helper'));

      $this->load->view('templates/user_table', $_POST);
      header('HTTP/1.1 200 OK');
    }
    else {
      exit (ERR_NO_RESULTS);
    }
  }

  public function eventTable() {
    if (!empty($_POST)) {
      $this->load->view('templates/artist_events', $_POST);
      header('HTTP/1.1 200 OK');
    }
    else {
      exit (ERR_NO_RESULTS);
    }
  }

  public function columnTable() {
    $this->load->helper(array('img_helper'));
    if (!empty($_POST)) {
      $this->load->view('templates/column_table', $_POST);
      header('HTTP/1.1 200 OK');
    }
    else {
      exit (ERR_NO_RESULTS);
    }
  }

  public function tagTable() {
    if (!empty($_POST)) {
      $this->load->view('templates/tag_table', $_POST);
      header('HTTP/1.1 200 OK');
    }
    else {
      exit (ERR_NO_RESULTS);
    }
  }

  public function tagList() {
    if (!empty($_POST)) {
      $this->load->view('templates/tag_list', $_POST);
      header('HTTP/1.1 200 OK');
    }
    else {
      exit (ERR_NO_RESULTS);
    }
  }

  public function likeList() {
    if (!empty($_POST)) {
      $this->load->view('templates/like_list', $_POST);
      header('HTTP/1.1 200 OK');
    }
    else {
      exit (ERR_NO_RESULTS);
    }
  }

  public function likeTable() {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'output_helper'));

      $this->load->view('templates/like_table', $_POST);
      header('HTTP/1.1 200 OK');
    }
    else {
      exit (ERR_NO_RESULTS);
    }
  }

  public function shoutTable() {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'output_helper'));
      
      $this->load->view('templates/shout_table', $_POST);
      header('HTTP/1.1 200 OK');
    }
    else {
      exit (ERR_NO_RESULTS);
    }
  }

  public function mosaic() {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'output_helper', 'music_helper'));

      $this->load->view('templates/mosaic_wall', $_POST);
      header('HTTP/1.1 200 OK');
    }
    else {
      exit (ERR_NO_RESULTS);
    }
  }

  public function searchList() {
    if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'output_helper'));

      $this->load->view('templates/search_list', $_POST);
      header('HTTP/1.1 200 OK');
    }
    else {
      exit (ERR_NO_RESULTS);
    }
  }
}
?>
