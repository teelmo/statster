<?php
class Music extends CI_Controller {

  public function index() {
    // Load helpers
    $this->load->helper(array('img_helper', 'music_helper', 'genre_helper', 'nationality_helper', 'year_helper', 'output_helper'));

    $data = array();
    $data['js_include'] = array('music', 'helpers/chart_helper');
    
    $opts = array(
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'limit' => '1',
      'human_readable' => true
    );
    $data['top_album'] = (json_decode(getAlbums($opts), true) !== NULL) ? ${!${false}=json_decode(getAlbums($opts), true)}[0] : array();
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? ${!${false}=json_decode(getArtists($opts), true)}[0] : array();
    $data['top_genre'] = (json_decode(getGenres($opts), true) !== NULL) ? ${!${false}=json_decode(getGenres($opts), true)}[0] : array();
    $data['top_nationality'] = (json_decode(getNationalities($opts), true) !== NULL) ? ${!${false}=json_decode(getNationalities($opts), true)}[0] : array();
    $data['top_year'] = (json_decode(getYears($opts), true) !== NULL) ? ${!${false}=json_decode(getYears($opts), true)}[0] : array();

    $this->load->view('site_templates/header', $data);
    $this->load->view('music/music_view', $data);
    $this->load->view('site_templates/footer');
  }

  public function artist($artist_name) {
    // Load helpers
    $this->load->helper(array('img_helper', 'music_helper', 'spotify_helper', 'artist_helper', 'output_helper'));

    $data = array();
    // Decode artist information
    $data['artist_name'] = decode($artist_name);
    // Get artist information aka. artist's name and id
    if ($data = getArtistInfo($data)) {
      // Get artist's total listening data
      $data += getArtistListenings($data);
      // Get biographty.
      $data += getArtistBio($data);
      if (empty($data['bio_summary']) || empty($data['bio_content'])) {
        $this->load->helper(array('lastfm_helper'));
        unset($data['bio_summary']);
        unset($data['bio_content']);
        $data += fetchArtistBio($data);
        addArtistBio($data);
      }
      else if ((time() - strtotime($data['bio_updated'])) > BIO_UPDATE_TIME) {
        $data['update_bio'] = true;
      }
      // Get logged in user's listening data
      if ($data['user_id'] = $this->session->userdata('user_id')) {
        $data += getArtistListenings($data);
      }
      $data['listener_count'] = sizeof(json_decode(getListeners($data), true));
      $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? TRUE : FALSE;
      $data['js_include'] = array('artist', 'lastfm', 'helpers/artist_album_helper', 'helpers/tag_helper', 'helpers/chart_helper', 'helpers/comment_helper');
      if (empty($data['spotify_uri'])) {
        $data['spotify_uri'] = getSpotifyResourceId($data);
      }
      $data += $_REQUEST;

      $this->load->view('site_templates/header', $data);
      $this->load->view('music/artist_view', $data);
      $this->load->view('site_templates/footer');
    }
    else {
      show_404();
    }
  }

  public function album($artist_name, $album_name) {
    // Load helpers
    $this->load->helper(array('img_helper', 'music_helper', 'spotify_helper', 'album_helper', 'nationality_helper', 'output_helper'));

    $data = array();
    $data['artist_name'] = decode($artist_name);
    $data['album_name'] = decode($album_name);

    // Get artist information aka. artist's name and id
    if ($data = getAlbumInfo($data)) {
      // Get albums's total listening data
      $data += getAlbumListenings($data);
      // Get biographty.
      $data += getAlbumBio($data);
      if (empty($data['bio_summary']) || empty($data['bio_content'])) {
        $this->load->helper(array('lastfm_helper'));
        unset($data['bio_summary']);
        unset($data['bio_content']);
        $data += fetchAlbumBio($data);
        addAlbumBio($data);
      }
      else if ((time() - strtotime($data['bio_updated'])) > BIO_UPDATE_TIME) {
        $data['update_bio'] = true;
      }
      // Get logged in user's listening data
      if ($data['user_id'] = $this->session->userdata('user_id')) {
        $data += getAlbumListenings($data);
      }
      $data['listener_count'] = sizeof(json_decode(getListeners($data), true));
      $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? TRUE : FALSE;
      $data['js_include'] = array('album', 'lastfm', 'helpers/artist_album_helper', 'helpers/tag_helper', 'helpers/chart_helper', 'helpers/comment_helper');
      if (empty($data['spotify_uri'])) {
        $data['spotify_uri'] = getSpotifyResourceId($data);
      }
      $data += $_REQUEST;

      $this->load->view('site_templates/header', $data);
      $this->load->view('music/album_view', $data);
      $this->load->view('site_templates/footer');
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

    $this->load->view('site_templates/header');
    $this->load->view('music/recent_view', $data);
    $this->load->view('site_templates/footer', $data);
  }

  public function listener($artist_name = '', $album_name = FALSE) {
    $data = array();
    $data['artist_name'] = decode($artist_name);
    $data['album_name'] = decode($album_name);
    $data['js_include'] = array('listener');
    $data += $_REQUEST;

    $this->load->view('site_templates/header');
    $this->load->view('music/listener_view', $data);
    $this->load->view('site_templates/footer', $data);
  }
}
?>
