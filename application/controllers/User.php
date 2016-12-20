<?php
class User extends CI_Controller {

  public function index() {
    // Load helpers
    $this->load->helper(array('img_helper', 'music_helper', 'output_helper'));
    
    $data['js_include'] = array('user');

    $opts = array(
      'human_readable' => false,
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31'
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true)[0] !== NULL) ? json_decode(getArtists($opts), true)[0] : array();
    
    $this->load->view('site_templates/header');
    $this->load->view('user/user_view', $data);
    $this->load->view('site_templates/footer');
  }

  public function profile($username) {
    $this->load->helper(array('form', 'user_helper', 'img_helper', 'music_helper', 'genre_helper', 'nationality_helper', 'year_helper', 'output_helper', 'fan_helper', 'love_helper'));

    $data['username'] = $username;

    if ($data = getUser($data)) {
      $data['js_include'] = array('profile', 'helpers/chart_helper', 'helpers/comment_helper', 'helpers/add_listening_helper');
      $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
      $data['username'] = $this->uri->segment(2);
      $data['interval'] = 0;
      $opts = array(
        'human_readable' => false,
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
        'username' => $username
      );
      $data['top_album'] = (json_decode(getAlbums($opts), true) !== NULL) ? json_decode(getAlbums($opts), true)[0] : array();
      $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array();
      $data['top_genre'] = (json_decode(getGenres($opts), true) !== NULL) ? json_decode(getGenres($opts), true)[0] : array();
      $data['top_nationality'] = (json_decode(getNationalitiesListenings($opts), true) !== NULL) ? json_decode(getNationalitiesListenings($opts), true)[0] : array();
      $data['top_year'] = (json_decode(getYears($opts), true) !== NULL) ? json_decode(getYears($opts), true)[0] : array();
      
      $opts = array(
        'human_readable' => false,
        'limit' => '1',
        'lower_limit' => '1970-00-00',
        'username' => $username
      );
      $data += (json_decode(getArtists($opts), true)[0] !== NULL) ? json_decode(getArtists($opts), true)[0] : array();
      $data += getUserTags($data);
      $data['artist_count'] = getListeningCount($data, TBL_artist);
      $data['album_count'] = getListeningCount($data, TBL_album);
      $data['listening_count'] = getListeningCount($data, TBL_listening);
      $data['fan_count'] = getFanCount(array('user_id' => $data['user_id']), 'user_id');
      $data['love_count'] = getLoveCount(array('user_id' => $data['user_id']), 'user_id');
      $this->load->view('site_templates/header');
      $this->load->view('user/profile_view', $data);
      $this->load->view('site_templates/footer');
    }
    else {
      show_404();
    }
  }

  public function edit() {
    if ($this->session->userdata('logged_in') !== TRUE) {
      redirect('/login?redirect=user/edit', 'refresh');
    }
    else if (!empty($_POST)) {
      // Load helpers
      $this->load->helper(array('user_helper'));

      $data = $_POST;
      $data['user_id'] = $this->session->userdata('user_id');
      if (updateUser($data)) {
        redirect('/user/' . $this->session->userdata('username'), 'refresh');
      }
      else {
        show_403();
      }
    }
    else {
      // Load helpers
      $this->load->helper(array('form', 'user_helper', 'listening_helper', 'output_helper'));

      $data = getUser(array('username' => $this->session->userdata('username')));
      $data['formats'] = array();
      foreach (json_decode(getListeningFormats()) as $key => $format) {
        $data['formats'][$format->format_name] = $format;
        if ($format_types = json_decode(getListeningFormatTypes(array('format_id' => $format->format_id)))) {
          foreach ($format_types as $key => $format_type) {
            $data['formats'][$format->format_name]->format_types[] = $format_type;
          }
        }
      }
      $this->load->view('site_templates/header');
      $this->load->view('user/edit_view', $data);
      $this->load->view('site_templates/footer');
    }
  }
}
?>
