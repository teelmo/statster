<?php
class User extends CI_Controller {

  public function index() {
    // Load helpers
    $this->load->helper(array('img_helper', 'music_helper', 'output_helper'));
    
    $data = array();
    $intervals = unserialize($this->session->userdata('intervals'));
    $data['top_listener_user'] = isset($intervals['top_listener_user']) ? $intervals['top_listener_user'] : 'overall';

    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true)[0] !== NULL) ? json_decode(getArtists($opts), true)[0] : array();
    $data['js_include'] = array('user/user', 'helpers/time_interval_helper');
    
    $this->load->view('site_templates/header');
    $this->load->view('user/user_view', $data);
    $this->load->view('site_templates/footer');
  }

  public function profile($username) {
    $this->load->helper(array('form', 'user_helper', 'img_helper', 'music_helper', 'genre_helper', 'nationality_helper', 'year_helper', 'output_helper', 'fan_helper', 'love_helper', 'shout_helper'));

    $data['username'] = $username;
    if ($data = getUser($data)) {
      $data['username'] = $this->uri->segment(2);
      $intervals = unserialize($this->session->userdata('intervals'));
      $data['top_album_profile'] = isset($intervals['top_album_profile']) ? $intervals['top_album_profile'] : 'overall';
      $data['top_artist_profile'] = isset($intervals['top_artist_profile']) ? $intervals['top_artist_profile'] : 'overall';
      $data['top_listening_format_profile'] = isset($intervals['top_listening_format_profile']) ? $intervals['top_listening_format_profile'] : 'overall';
      $data['top_genre_profile'] = isset($intervals['top_genre_profile']) ? $intervals['top_genre_profile'] : 'overall';
      $data['top_keyword_profile'] = isset($intervals['top_keyword_profile']) ? $intervals['top_keyword_profile'] : 'overall';
      $data['top_nationality_profile'] = isset($intervals['top_nationality_profile']) ? $intervals['top_nationality_profile'] : 'overall';
      $data['top_year_profile'] = isset($intervals['top_year_profile']) ? $intervals['top_year_profile'] : 'overall';

      $opts = array(
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
        'username' => $username
      );
      $data['top_album'] = (json_decode(getAlbums($opts), true) !== NULL) ? json_decode(getAlbums($opts), true)[0] : array();
      $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
      $data['top_genre'] = (json_decode(getGenres($opts), true) !== NULL) ? json_decode(getGenres($opts), true)[0] : array();
      $data['top_nationality'] = (json_decode(getNationalities($opts), true) !== NULL) ? json_decode(getNationalities($opts), true)[0] : array();
      $data['top_year'] = (json_decode(getYears($opts), true) !== NULL) ? json_decode(getYears($opts), true)[0] : array();
      $opts = array(
        'limit' => '1',
        'lower_limit' => '1970-00-00',
        'username' => $username
      );
      $data += (json_decode(getArtists($opts), true)[0] !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0, 'artist_name' => 'Unknown');
      $data += getUserTags($data);
      $data['artist_count'] = getListeningCount($data, TBL_artist);
      $data['album_count'] = getListeningCount($data, TBL_album);
      $data['listening_count'] = getListeningCount($data, TBL_listening);
      $data['fan_count'] = getFanCount(array('user_id' => $data['user_id']));
      $data['love_count'] = getLoveCount(array('user_id' => $data['user_id']));
      $data['shout_count'] = getShoutCount(array('user_id' => $data['user_id']));
      if ($data['username'] !==  $this->session->userdata('username')) {
        $data['similarity'] = getUserSimilarity($data);
      }
      $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
      $data['js_include'] = array('user/profile', 'libs/highcharts', 'libs/peity', 'libs/jquery.daterangepicker', 'helpers/chart_helper', 'helpers/comment_helper', 'helpers/time_interval_helper');
      if ($data['logged_in'] === 'true') {
        $data['js_include'][] = 'helpers/add_listening_helper';
      }
      
      $this->load->view('site_templates/header');
      $this->load->view('user/profile_view', $data);
      $this->load->view('site_templates/footer');
    }
    else {
      show_404();
    }
  }

  public function edit() {
    $data = array();
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
        show_404();
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
