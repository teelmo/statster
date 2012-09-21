<?php
class Artist extends CI_Controller {

  public function index() {
    $data['request'] = 'artists';

    $this->load->view('templates/header');
    $this->load->view('artists_view');
    $this->load->view('templates/footer', $data);
  }

}
?>