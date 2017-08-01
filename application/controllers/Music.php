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
      'human_readable' => false
    );
    $data['top_album'] = (json_decode(getAlbums($opts), true) !== NULL) ? json_decode(getAlbums($opts), true)[0] : array();
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array();
    $data['top_genre'] = (json_decode(getGenres($opts), true) !== NULL) ? json_decode(getGenres($opts), true)[0] : array();
    $data['top_nationality'] = (json_decode(getNationalitiesListenings($opts), true) !== NULL) ? json_decode(getNationalitiesListenings($opts), true)[0] : array();
    $data['top_year'] = (json_decode(getYears($opts), true) !== NULL) ? json_decode(getYears($opts), true)[0] : array();

    $this->load->view('site_templates/header', $data);
    $this->load->view('music/music_view', $data);
    $this->load->view('site_templates/footer');
  }

  public function artist_or_year($value) {
    if ((int) $value > 1900 && (int) $value <= CUR_YEAR) {
      // Load helpers
      $this->load->helper(array('img_helper', 'music_helper', 'shout_helper', 'fan_helper', 'love_helper', 'spotify_helper', 'output_helper'));
      $data['year'] = $value;
      $data += array(
        'lower_limit' => $data['year'] . '-00-00',
        'upper_limit' => $data['year'] . '-12-31',
        'limit' => '1',
        'human_readable' => false
      );
      $data['username'] = $this->session->userdata('username');
      $data['artist_count'] = getListeningCount($data, TBL_artist);
      $data['album_count'] = getListeningCount($data, TBL_album);
      $data['listening_count'] = getListeningCount($data, TBL_listening);
      $data['where'] = TBL_album . '.`created` LIKE \'' . $data['year'] . '%\'';
      $data['new_artist_count'] = getListeningCount($data, TBL_artist);
      $data['new_album_count'] = getListeningCount($data, TBL_album);
      $data['fan_count'] = getFanCount($data);
      $data['love_count'] = getLoveCount($data);
      $data['shout_count'] = getShoutCount($data);
      $data['top_artist'] = (json_decode(getArtists($data), true) !== NULL) ? json_decode(getArtists($data), true)[0] : array();
      $data['top_album'] = (json_decode(getAlbums($data), true) !== NULL) ? json_decode(getAlbums($data), true)[0] : array();
        
      $data['js_include'] = array('year', 'helpers/chart_helper');
      $this->load->view('site_templates/header');
      $this->load->view('music/year_view', $data);
      $this->load->view('site_templates/footer');
    }
    else {
      // Load helpers
      $this->load->helper(array('img_helper', 'music_helper', 'spotify_helper', 'artist_helper', 'output_helper'));

      // Decode artist information
      $data['artist_name'] = decode($value);
      // Get artist information aka. artist's name and id
      if ($data = getArtistInfo($data)) {
        // Get artist's total listening data
        $data += getArtistListenings($data);
        // Get biography
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
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        if (empty($data['spotify_uri'])) {
          $data['spotify_uri'] = getSpotifyResourceId($data);
        }
        $data += $_REQUEST;

        $data['js_include'] = array('artist', 'lastfm', 'helpers/artist_album_helper', 'helpers/tag_helper', 'helpers/chart_helper', 'helpers/shout_helper');
        $this->load->view('site_templates/header', $data);
        $this->load->view('music/artist_view', $data);
        $this->load->view('site_templates/footer');
      }
      else {
        show_404();
      }
    }
  }

  public function album_or_month($value1, $value2) {
    if ((int) $value1 > 1900 && (int) $value1 <= CUR_YEAR && (int) $value2 > 0 && (int) $value2 <= 12) {
      // Load helpers
      $this->load->helper(array('img_helper', 'music_helper', 'shout_helper', 'fan_helper', 'love_helper', 'spotify_helper', 'output_helper'));
      $data['year'] = $value1;
      $data['month'] = $value2;
      $data += array(
        'lower_limit' => $data['year'] . '-' . $data['month'] . '-00',
        'upper_limit' => $data['year'] . '-' . $data['month'] . '-31',
        'limit' => '1',
        'human_readable' => false
      );
      $data['username'] = $this->session->userdata('username');
      $data['artist_count'] = getListeningCount($data, TBL_artist);
      $data['album_count'] = getListeningCount($data, TBL_album);
      $data['listening_count'] = getListeningCount($data, TBL_listening);
      $data['where'] = TBL_album . '.`created` LIKE \'' . $data['year'] . '%\'';
      $data['new_artist_count'] = getListeningCount($data, TBL_artist);
      $data['new_album_count'] = getListeningCount($data, TBL_album);
      $data['fan_count'] = getFanCount($data);
      $data['love_count'] = getLoveCount($data);
      $data['shout_count'] = getShoutCount($data);
      $data['top_artist'] = (json_decode(getArtists($data), true) !== NULL) ? json_decode(getArtists($data), true)[0] : array();
      $data['top_album'] = (json_decode(getAlbums($data), true) !== NULL) ? json_decode(getAlbums($data), true)[0] : array();
        
      $data['js_include'] = array('month', 'helpers/chart_helper');
      $this->load->view('site_templates/header');
      $this->load->view('music/month_view', $data);
      $this->load->view('site_templates/footer');
    }
    else {
      // Load helpers
      $this->load->helper(array('img_helper', 'music_helper', 'spotify_helper', 'album_helper', 'nationality_helper', 'output_helper'));

      $data['artist_name'] = decode($value1);
      $data['album_name'] = decode($value2);

      // Get artist information aka. artist's name and id
      if ($data = getAlbumInfo($data)) {
        // Get albums's total listening data
        $data += getAlbumListenings($data);
        // Get biography
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
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        if (empty($data['spotify_uri'])) {
          $data['spotify_uri'] = getSpotifyResourceId($data);
        }
        $data += $_REQUEST;

        $data['js_include'] = array('album', 'lastfm', 'helpers/artist_album_helper', 'helpers/tag_helper', 'helpers/chart_helper', 'helpers/shout_helper');
        $this->load->view('site_templates/header', $data);
        $this->load->view('music/album_view', $data);
        $this->load->view('site_templates/footer');      
      }
      else {
        show_404();
      }
    }
  }

  public function recent($artist_name = '', $album_name = FALSE) {
    // Load helpers
    $this->load->helper(array('form'));

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
        
        $data['js_include'] = array('recent_album', 'helpers/tag_helper');
        $this->load->view('site_templates/header');
        $this->load->view('music/recent_album_view', $data);
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
        $data['album_name'] = decode($album_name);
        // Get artist's total listening data
        $data += getArtistListenings($data);
        // Get logged in user's listening data
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getArtistListenings($data);
        }
        $data['listener_count'] = sizeof(json_decode(getListeners($data), true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';

        $data['js_include'] = array('recent_artist', 'helpers/tag_helper');
        $this->load->view('site_templates/header');
        $this->load->view('music/recent_artist_view', $data);
        $this->load->view('site_templates/footer', $data);
      }
      else {
        show_404();
      }
    }
    else {
      $data['artist_name'] = decode($artist_name);
      $data['album_name'] = decode($album_name);
      $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';

      $data['js_include'] = array('recent', 'helpers/add_listening_helper');
      $this->load->view('site_templates/header');
      $this->load->view('music/recent_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
  }

  public function listener($artist_name = '', $album_name = FALSE) {
    // Load helpers
    $this->load->helper(array('form'));

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
        
        $data['js_include'] = array('listener_album', 'helpers/tag_helper');
        $this->load->view('site_templates/header');
        $this->load->view('music/listener_album_view', $data);
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
        $data['album_name'] = decode($album_name);
        // Get artist's total listening data
        $data += getArtistListenings($data);
        // Get logged in user's listening data
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getArtistListenings($data);
        }
        $data['listener_count'] = sizeof(json_decode(getListeners($data), true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';

        $data['js_include'] = array('listener_artist', 'helpers/tag_helper');
        $this->load->view('site_templates/header');
        $this->load->view('music/listener_artist_view', $data);
        $this->load->view('site_templates/footer', $data);
      }
      else {
        show_404();
      }
    }
    else {
      $data['artist_name'] = decode($artist_name);
      $data['album_name'] = decode($album_name);
      $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';

      $data['js_include'] = array('listener');
      $this->load->view('site_templates/header');
      $this->load->view('music/listener_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
  }
}
?>
