<?php
class Format extends CI_Controller {

  public function index($artist_name = '', $album_name = '') {
    if (!empty($album_name)) {
      // Load helpers
      $this->load->helper(array('album_helper', 'output_helper'));

      $data['artist_name'] = decode($artist_name);
      $data['album_name'] = decode($album_name);
      if ($data = getAlbumInfo($data)) {
        $data['js_include'] = array('format_album');

        $this->load->view('site_templates/header');
        $this->load->view('format/format_album_view', $data);
        $this->load->view('site_templates/footer', $data);

      }
      else {
        show_404();
      }
    }
    else if (!empty($artist_name)) {
      // Load helpers
      $this->load->helper(array('artist_helper', 'output_helper'));

      $data['artist_name'] = decode($artist_name);
      if ($data = getArtistInfo($data)) {
        $data['js_include'] = array('format_artist');

        $this->load->view('site_templates/header');
        $this->load->view('format/format_artist_view', $data);
        $this->load->view('site_templates/footer', $data);
      }
      else {
        show_404();
      }
    }
    else {
      // Load helpers
      $this->load->helper(array('output_helper'));
      $data['js_include'] = array('format');

      $this->load->view('site_templates/header');
      $this->load->view('format/format_view', $data);
      $this->load->view('site_templates/footer', $data);

    }
  }

}
?>
