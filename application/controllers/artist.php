<?php
/*
 * This is the controller for the artists page, not for 
 * a single artist which is found from the music controller
 */
class Artist extends CI_Controller {

  public function index() {
    $data['js_include'] = array('artists');

    $this->load->view('templates/header');
    $this->load->view('artists_view');
    $this->load->view('templates/footer', $data);
  }

}
?>