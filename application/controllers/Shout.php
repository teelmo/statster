<?php
class Shout extends CI_Controller {

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
        
        $data['js_include'] = array('shout_album', 'helpers/tag_helper');
        $this->load->view('site_templates/header');
        $this->load->view('shout/shout_album_view', $data);
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

        $data['js_include'] = array('shout_artist', 'helpers/tag_helper');
        $this->load->view('site_templates/header');
        $this->load->view('shout/shout_artist_view', $data);
        $this->load->view('site_templates/footer', $data);
      }
      else {
        show_404();
      }
    }
    else {
     // Load helpers
      $this->load->helper(array());
      $data['js_include'] = array('shout', 'helpers/shout_helper');

      $this->load->view('site_templates/header');
      $this->load->view('shout/shouts_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
  }
}
?>
