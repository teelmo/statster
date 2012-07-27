<?php
class Music extends CI_Controller {

  public function index() {
    // Load helpers
    $this->load->helper(array('form'));

    $data = array();
    $data['request'] = 'music';
    
    $this->load->view('templates/header', $data);
    $this->load->view('music_view');
    $this->load->view('templates/footer');
  }

  public function artist($artist) {
    $data = array();
    $data['artist'] = $artist;
    $data['request'] = 'artist';

    $this->load->view('templates/header', $data);
    $this->load->view('artist_view', $data);
    $this->load->view('templates/footer');
  }

  public function album($artist, $album) {
    $data = array();
    $data['artist'] = $artist;
    $data['album'] = $album;
    $data['year'] = 'Unknown';
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