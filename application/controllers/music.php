<?php
class Music extends CI_Controller {

  public function index() {
    $this->load->view('templates/header');
    $this->load->view('music_view');
    $this->load->view('templates/footer');
  }

  public function artist($artist) {
    $data['artist'] = $artist;
    $this->load->view('templates/header');
    $this->load->view('artist_view', $data);
    $this->load->view('templates/footer');
  }

  public function album($artist, $album) {
    $data['artist'] = $artist;
    $data['album'] = $album;
    $data['year'] = 'Unknown';
    $this->load->view('templates/header');
    $this->load->view('album_view', $data);
    $this->load->view('templates/footer');
  }

}
?>