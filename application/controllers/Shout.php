<?php
class Shout extends CI_Controller {

  public function index() {
    $data['js_include'] = array('shout');

    $this->load->view('site_templates/header');
    $this->load->view('shout/shouts_view', $data);
    $this->load->view('site_templates/footer', $data);
  }
}
?>
