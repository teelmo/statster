<?php
/*
 * This is the controller for the albums page, not for 
 * a single album which is found from the music controller
 */
class Album extends CI_Controller {

  public function index() {
    $data['js_include'] = array('albums');

    $this->load->view('templates/header');
    $this->load->view('albums_view');
    $this->load->view('templates/footer', $data);
  }
}
?>