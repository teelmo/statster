<?php
class Login extends CI_Controller {

  public function index() {
    // Check that user has not already logged in.
    if ($this->session->userdata('logged_in') === TRUE) {
      $redirect = empty($_GET['redirect']) ? '/' : $_GET['redirect'];
      redirect(utf8_decode($redirect), 'refresh');
    }
    // Load helpers.
    $this->load->helper(array('form', 'img_helper', 'music_helper', 'output_helper'));

    $data = array();
    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
    $data['redirect'] = empty($_GET['redirect']) ? '/' : $_GET['redirect'];
    $data['js_include'] = array('login/login');

    $this->load->view('site_templates/header');
    $this->load->view('login_view', $data);
    $this->load->view('site_templates/footer', $data);  
  }
}
?>
