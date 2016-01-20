<?php
class Main extends CI_Controller {

  public function index() {
    if ($this->session->userdata('logged_in') === TRUE) {
      // Load helpers
      $this->load->helper(array('form', 'img_helper', 'music_helper', 'output_helper'));

      $data = array();
      $data['js_include'] = array('main');
      $data['interval'] = 14;

      $opts = array(
        'human_readable' => false,
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31'
      );
      $data += (${!${false}=json_decode(getArtists($opts), true)}[0] !== NULL) ? ${!${false}=json_decode(getArtists($opts), true)}[0] : array();

      $this->load->view('site_templates/header');
      $this->load->view('main_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
    else {
      // Load helpers
      $this->load->helper(array('form', 'img_helper', 'music_helper', 'album_helper', 'output_helper'));

      $data = array();
      $data['js_include'] = array('welcome');
      $opts = array(
        'human_readable' => false,
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31'
      );
      $data += (${!${false}=json_decode(getAlbums($opts), true)}[0] !== NULL) ? ${!${false}=json_decode(getAlbums($opts), true)}[0] : array();
      $data += getAlbumTags($data);

      $this->load->view('site_templates/header');
      $this->load->view('welcome_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
  }

  /* 
   * Meta page's controllers.
   */
  public function about() {
    $this->load->view('site_templates/header');
    $this->load->view('meta/about_view');
    $this->load->view('site_templates/footer');
  }

  public function career() {
    $this->load->view('site_templates/header');
    $this->load->view('meta/career_view');
    $this->load->view('site_templates/footer');
  }

  public function developers() {
    $this->load->view('site_templates/header');
    $this->load->view('meta/developers_view');
    $this->load->view('site_templates/footer');
  }

  public function privacy() {
    $this->load->view('site_templates/header');
    $this->load->view('meta/privacy_view');
    $this->load->view('site_templates/footer');
  }

  public function terms() {
    $this->load->view('site_templates/header');
    $this->load->view('meta/terms_view');
    $this->load->view('site_templates/footer');
  }
}
?>
