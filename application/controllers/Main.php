<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Main extends MY_Controller {

  public function index() {
    $data = array();
    if ($this->session->userdata('logged_in') === TRUE) {
      // Load helpers.
      $this->load->helper(array('form', 'img_helper', 'music_helper', 'genre_helper', 'nationality_helper', 'year_helper', 'output_helper'));

      $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
      $data['top_album_main'] = isset($intervals['top_album_main']) ? $intervals['top_album_main'] : 30;
      $data['top_artist_main'] = isset($intervals['top_artist_main']) ? $intervals['top_artist_main'] : 30;
      
      $opts = array(
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      $data['top_album'] = (json_decode(getAlbums($opts) ?? '', true) !== NULL) ? json_decode(getAlbums($opts), true)[0] : array('album_id' => 0, 'album_name' => 'No data', 'count' => 0);
      $data['top_artist'] = (json_decode(getArtists($opts) ?? '', true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0, 'artist_name' => 'No data', 'count' => 0);
      $data['top_genre'] = (json_decode(getGenres($opts) ?? '', true) !== NULL) ? json_decode(getGenres($opts), true)[0] : array('tag_id' => 0, 'name' => 'No data', 'count' => 0);
      $data['top_nationality'] = (json_decode(getNationalities($opts) ?? '', true) !== NULL) ? json_decode(getNationalities($opts), true)[0] : array('tag_id' => 0, 'name' => 'No data', 'count' => 0);
      $data['top_year'] = (json_decode(getYears($opts) ?? '', true) !== NULL) ? json_decode(getYears($opts), true)[0] : array('tag_id' => 0, 'name' => 'No data', 'count' => 0);
      $data['js_include'] = array('main', 'libs/jquery.daterangepicker', 'helpers/add_listening_helper', 'helpers/time_interval_helper');

      $this->load->view('site_templates/header');
        
      $this->load->view('main_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
    else {
      // Load helpers.
      $this->load->helper(array('form', 'img_helper', 'music_helper', 'genre_helper', 'nationality_helper', 'year_helper', 'output_helper'));

      $opts = array(
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      $data['top_album'] = (json_decode(getAlbums($opts) ?? '', true) !== NULL) ? json_decode(getAlbums($opts), true)[0] : array('album_id' => 0, 'album_name' => 'No data', 'count' => 0);
      $data['top_artist'] = (json_decode(getArtists($opts) ?? '', true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0, 'artist_name' => 'No data', 'count' => 0);
      $data['top_genre'] = (json_decode(getGenres($opts) ?? '', true) !== NULL) ? json_decode(getGenres($opts), true)[0] : array('tag_id' => 0, 'name' => 'No data', 'count' => 0);
      $data['top_nationality'] = (json_decode(getNationalities($opts) ?? '', true) !== NULL) ? json_decode(getNationalities($opts), true)[0] : array('tag_id' => 0, 'name' => 'No data', 'count' => 0);
      $data['top_year'] = (json_decode(getYears($opts) ?? '', true) !== NULL) ? json_decode(getYears($opts), true)[0] : array('tag_id' => 0, 'name' => 'No data', 'count' => 0);
      $data['js_include'] = array('welcome');

      $this->load->view('site_templates/header');
      $this->load->view('welcome_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
  }

  /* 
   * Meta page's controllers.
   */
  public function about() {
    // Load helpers.
    $this->load->helper(array('form', 'img_helper', 'artist_helper', 'album_helper', 'music_helper', 'output_helper'));

    $data = array();

    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('name' => 'No data', 'count' => 0);
    $data['js_include'] = array('meta');

    $this->load->view('site_templates/header');
    $this->load->view('meta/about_view', $data);
    $this->load->view('site_templates/footer', $data);
  }

  public function career() {
    // Load helpers.
    $this->load->helper(array('form', 'img_helper', 'artist_helper', 'album_helper', 'music_helper', 'output_helper'));

    $data = array();

    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('name' => 'No data', 'count' => 0);
    $data['js_include'] = array('meta');

    $this->load->view('site_templates/header');
    $this->load->view('meta/career_view', $data);
    $this->load->view('site_templates/footer', $data);
  }

  public function developers() {
    // Load helpers.
    $this->load->helper(array('form', 'img_helper', 'artist_helper', 'album_helper', 'music_helper', 'output_helper'));

    $data = array();

    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('name' => 'No data', 'count' => 0);
    $data['js_include'] = array('meta');

    $this->load->view('site_templates/header');
    $this->load->view('meta/developers_view', $data);
    $this->load->view('site_templates/footer', $data);
  }

  public function privacy() {
    // Load helpers.
    $this->load->helper(array('form', 'img_helper', 'artist_helper', 'album_helper', 'music_helper', 'output_helper'));

    $data = array();

    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('name' => 'No data', 'count' => 0);
    $data['js_include'] = array('meta');

    $this->load->view('site_templates/header');
    $this->load->view('meta/privacy_view', $data);
    $this->load->view('site_templates/footer', $data);
  }

  public function terms() {
    // Load helpers.
    $this->load->helper(array('form', 'img_helper', 'artist_helper', 'album_helper', 'music_helper', 'output_helper'));

    $data = array();

    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('name' => 'No data', 'count' => 0);
    $data['js_include'] = array('meta');

    $this->load->view('site_templates/header');
    $this->load->view('meta/terms_view', $data);
    $this->load->view('site_templates/footer', $data);
  }
  public function error_404() {
    show_404();
  }
}
?>
