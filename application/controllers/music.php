<?php
class Music extends CI_Controller {

  public function index() {
    $data = array();
    $data['request'] = 'music';
    
    $this->load->view('templates/header', $data);
    $this->load->view('music_view');
    $this->load->view('templates/footer');
  }

  public function artist($artist) {
    // Load helpers
    $this->load->helper(array('img_helper', 'artist_helper'));

    $data = array();
    // Decode artist information
    $data['artist'] = urldecode(utf8_decode($artist));
    // Get artist information aka. artist's name and id
    $data = getArtistInfo($data);
    // Get artist's totaol listening data
    $data += getArtistListenings($data);
    // Get logged in user's listening data
    if ($data['user_id'] = $this->session->userdata['user_id']) {
      $data += getArtistListenings($data);
    }
    // Get artists tags (genres, keywords) data
    $data['limit'] = 9;
    $data += getArtistTags($data);

    $data['request'] = 'artist';

    $this->load->view('templates/header', $data);
    $this->load->view('artist_view', $data);
    $this->load->view('templates/footer');
  }

  public function album($artist, $album) {
    // Load helpers
    $this->load->helper(array('img_helper', 'album_helper'));

    $data = array();
    $data['artist'] = urldecode(utf8_decode($artist));
    $data['album'] = urldecode(utf8_decode($album));

    // Get artist information aka. artist's name and id
    $data = getAlbumInfo($data);
    // Get artist's totaol listening data
    $data += getAlbumListenings($data);
    // Get logged in user's listening data
    if ($data['user_id'] = $this->session->userdata['user_id']) {
      $data += getAlbumListenings($data);
    }
    // Get artists tags (genres, keywords) data
    $data['limit'] = 9;
    $data += getAlbumTags($data);

    $data['request'] = 'album';

    $this->load->view('templates/header', $data);
    $this->load->view('album_view', $data);
    $this->load->view('templates/footer');
  }

public function recent() {
    $data = array();
    $data += $_REQUEST;
    $data['request'] = 'recent';

    $this->load->view('templates/header');
    $this->load->view('recent_view', $data);
    $this->load->view('templates/footer', $data);
  }
}
?>