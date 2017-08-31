<?php
class Like extends CI_Controller {

  public function index($artist_name = '', $album_name = '') {
    if (!empty($album_name)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'music_helper', 'album_helper', 'output_helper'));

      $data['artist_name'] = decode($artist_name);
      $data['album_name'] = decode($album_name);
      if ($data = getAlbumInfo($data)) {
        // Get albums's total listening data
        $data += getAlbumListenings($data);
        // Get logged in user's listening data
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getAlbumListenings($data);
        }
        $data['listener_count'] = sizeof(json_decode(getListeners($data), true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        
        $data['js_include'] = array('like_album', 'helpers/tag_helper');
        $this->load->view('site_templates/header');
        $this->load->view('like/like_album_view', $data);
        $this->load->view('site_templates/footer', $data);
      }
      else {
        show_404();
      }
    }
    else if (!empty($artist_name)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'music_helper', 'artist_helper', 'output_helper'));

      $data['artist_name'] = decode($artist_name);
      if ($data = getArtistInfo($data)) {
        // Get artist's total listening data
        $data += getArtistListenings($data);
        // Get logged in user's listening data
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getArtistListenings($data);
        }
        $data['listener_count'] = sizeof(json_decode(getListeners($data), true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';

        $data['js_include'] = array('like_artist', 'helpers/tag_helper');
        $this->load->view('site_templates/header');
        $this->load->view('like/like_artist_view', $data);
        $this->load->view('site_templates/footer', $data);
      }
      else {
        show_404();
      }
    }
    else {
      // Load helpers
      $this->load->helper(array('music_helper', 'img_helper', 'output_helper'));

      $data = array();
      $opts = array(
        'human_readable' => false,
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31'
      );
      $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array();
    
      $data['js_include'] = array('like');

      $this->load->view('site_templates/header');
      $this->load->view('like/like_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
  }

  public function love() {
    // Load helpers
    $this->load->helper(array('music_helper', 'img_helper', 'output_helper'));

    $data = array();
    $opts = array(
      'human_readable' => false,
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31'
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array();
    
    $data['js_include'] = array('love');

    $this->load->view('site_templates/header');
    $this->load->view('like/love_view', $data);
    $this->load->view('site_templates/footer', $data);
  }

  public function fan() {
    // Load helpers
    $this->load->helper(array('music_helper', 'img_helper', 'output_helper'));

    $data = array();
    $opts = array(
      'human_readable' => false,
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31'
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array();

    $data['js_include'] = array('fan');

    $this->load->view('site_templates/header');
    $this->load->view('like/fan_view', $data);
    $this->load->view('site_templates/footer', $data);
  }
}