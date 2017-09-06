<?php
class Format extends CI_Controller {

  public function index($artist_name = '', $album_name = '') {
    $data = array();
    if (!empty($album_name)) {
      // Load helpers.
      $this->load->helper(array('img_helper', 'music_helper', 'album_helper', 'output_helper'));

      $data['artist_name'] = decode($artist_name);
      $data['album_name'] = decode($album_name);
      if ($data = getAlbumInfo($data)) {
        // Get albums's total listening data.
        $data += getAlbumListenings($data);
        // Get logged in user's listening data.
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getAlbumListenings($data);
        }
        $data['listener_count'] = sizeof(json_decode(getListeners($data), true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        $data['js_include'] = array('format_album');

        $this->load->view('site_templates/header');
        $this->load->view('format/format_album_view', $data);
        $this->load->view('site_templates/footer');
      }
      else {
        show_404();
      }
    }
    else if (!empty($artist_name)) {
      // Load helpers.
      $this->load->helper(array('img_helper', 'music_helper', 'artist_helper', 'output_helper'));

      $data['artist_name'] = decode($artist_name);
      if ($data = getArtistInfo($data)) {
        $data['album_name'] = decode($album_name);
        // Get artist's total listening data.
        $data += getArtistListenings($data);
        // Get logged in user's listening data.
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getArtistListenings($data);
        }
        $data['listener_count'] = sizeof(json_decode(getListeners($data), true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        $data['js_include'] = array('format_artist');

        $this->load->view('site_templates/header');
        $this->load->view('format/format_artist_view', $data);
        $this->load->view('site_templates/footer');
      }
      else {
        show_404();
      }
    }
    else {
      // Load helpers.
      $this->load->helper(array('music_helper', 'img_helper', 'year_helper', 'output_helper'));

      $opts = array(
        'human_readable' => false,
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31'
      );
      $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array();
      $data['js_include'] = array('format');

      $this->load->view('site_templates/header');
      $this->load->view('format/format_view', $data);
      $this->load->view('site_templates/footer');
    }
  }

}
?>
