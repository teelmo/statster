<?php
class Music extends CI_Controller {

  public function index() {
    $data = array();
    $data['js_include'] = array('music');
    
    $this->load->view('templates/header', $data);
    $this->load->view('music_view');
    $this->load->view('templates/footer');
  }

  public function artist($artist_name) {
    // Load helpers
    $this->load->helper(array('img_helper', 'music_helper', 'artist_helper'));

    $data = array();
    // Decode artist information
    $data['artist_name'] = decode($artist_name);
    // Get artist information aka. artist's name and id
    if ($data = getArtistInfo($data)) {
      // Get artist's totaol listening data
      $data += getArtistListenings($data);
      // Get logged in user's listening data
      if ($data['user_id'] = $this->session->userdata('user_id')) {
        $data += getArtistListenings($data);
      }
      // Get artists tags (genres, keywords) data
      $data['limit'] = 9;
      $data += getArtistTags($data);
      $data['js_include'] = array('artist', 'artistAlbum', 'similarArtist', 'artistBio', 'artistEvent');
      $data += $_REQUEST;

      $this->load->view('templates/header', $data);
      $this->load->view('artist_view', $data);
      $this->load->view('templates/footer');
    }
    else {
      show_404();
    }
  }

  public function album($artist_name, $album_name) {
    // Load helpers
    $this->load->helper(array('img_helper', 'music_helper', 'album_helper'));

    $data = array();
    $data['artist_name'] = decode($artist_name);
    $data['album_name'] = decode($album_name);

    // Get artist information aka. artist's name and id
    if ($data = getAlbumInfo($data)) {
      // Get artist's totaol listening data
      $data += getAlbumListenings($data);
      // Get logged in user's listening data
      if ($data['user_id'] = $this->session->userdata('user_id')) {
        $data += getAlbumListenings($data);
      }
      // Get artists tags (genres, keywords) data
      $data['limit'] = 9;
      $data += getAlbumTags($data);
      $data['js_include'] = array('album', 'artistAlbum', 'similarArtist', 'artistBio', 'artistEvent');
      $data += $_REQUEST;

      $this->load->view('templates/header', $data);
      $this->load->view('album_view', $data);
      $this->load->view('templates/footer');
    }
    else {
      show_404();
    }
  }

  public function recent($artist_name = '', $album_name = FALSE) {
    $data = array();
    $data['artist_name'] = decode($artist_name);
    $data['album_name'] = decode($album_name);
    $data['js_include'] = array('recent');
    $data += $_REQUEST;

    $this->load->view('templates/header');
    $this->load->view('recent_view', $data);
    $this->load->view('templates/footer', $data);
  }

  public function listener($artist_name = '', $album_name = FALSE) {
    $data = array();
    $data['artist_name'] = decode($artist_name);
    $data['album_name'] = decode($album_name);
    $data['js_include'] = array('listener');
    $data += $_REQUEST;

    $this->load->view('templates/header');
    $this->load->view('listener_view', $data);
    $this->load->view('templates/footer', $data);
  }
}
?>