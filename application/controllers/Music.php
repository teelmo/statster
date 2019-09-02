<?php
class Music extends CI_Controller {

  public function index() {
    // Load helpers.
    $this->load->helper(array('img_helper', 'music_helper', 'genre_helper', 'nationality_helper', 'year_helper', 'output_helper'));

    $data = array();
    $intervals = unserialize($this->session->userdata('intervals'));
    $data['popular_album_music'] = isset($intervals['popular_album_music']) ? $intervals['popular_album_music'] : 90;
    $opts = array(
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'limit' => '1',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_album'] = (json_decode(getAlbums($opts), true) !== NULL) ? json_decode(getAlbums($opts), true)[0] : array();
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array();
    $data['top_genre'] = (json_decode(getGenres($opts), true) !== NULL) ? json_decode(getGenres($opts), true)[0] : array();
    $data['top_nationality'] = (json_decode(getNationalities($opts), true) !== NULL) ? json_decode(getNationalities($opts), true)[0] : array();
    $data['top_year'] = (json_decode(getYears($opts), true) !== NULL) ? json_decode(getYears($opts), true)[0] : array();
    $data['js_include'] = array('music/music', 'libs/highcharts', 'libs/peity', 'helpers/chart_helper', 'helpers/time_interval_helper');

    $this->load->view('site_templates/header');
    $this->load->view('music/music_view', $data);
    $this->load->view('site_templates/footer');
  }

  public function artist_or_year($value) {
    $data = array();
    if ((int) $value > 1900 && (int) $value <= CUR_YEAR) {
      // Load helpers.
      $this->load->helper(array('img_helper', 'music_helper', 'shout_helper', 'fan_helper', 'love_helper', 'spotify_helper', 'output_helper'));
      $data['year'] = $value;
      $data += array(
        'limit' => '1',
        'lower_limit' => $data['year'] . '-00-00',
        'upper_limit' => $data['year'] . '-12-31'
      );
      $data['username'] = $this->session->userdata('username');
      $data['artist_count'] = getListeningCount($data, TBL_artist);
      $data['album_count'] = getListeningCount($data, TBL_album);
      $data['listening_count'] = getListeningCount($data, TBL_listening);
      $data['top_artist'] = (json_decode(getArtists($data), true) !== NULL) ? json_decode(getArtists($data), true)[0] : array();
      $data['top_album'] = (json_decode(getAlbums($data), true) !== NULL) ? json_decode(getAlbums($data), true)[0] : array();
      $data['where'] = TBL_album . '.`created` LIKE \'' . $data['year'] . '%\'';
      $data['new_artist_count'] = getListeningCount($data, TBL_artist);
      $data['new_album_count'] = getListeningCount($data, TBL_album);
      $data['fan_count'] = getFanCount($data);
      $data['love_count'] = getLoveCount($data);
      $data['shout_count'] = getShoutCount($data);
      $data['js_include'] = array('music/year', 'libs/highcharts', 'helpers/chart_helper');

      $this->load->view('site_templates/header');
      $this->load->view('music/year_view', $data);
      $this->load->view('site_templates/footer');
    }
    else {
      // Load helpers.
      $this->load->helper(array('img_helper', 'music_helper', 'spotify_helper', 'artist_helper', 'output_helper'));

      // Decode artist information.
      $data['artist_name'] = decode($value);
      // Get artist information aka. artist's name and id.
      if ($data = getArtistInfo($data)) {
        // Get artist's total listening data.
        $data += getArtistListenings($data);
        // Get biography.
        $data += getArtistBio($data);
        if (empty($data['bio_summary']) || empty($data['bio_content'])) {
          $this->load->helper(array('lastfm_helper'));
          unset($data['bio_summary']);
          unset($data['bio_content']);
          $data += fetchArtistInfo($data, array('bio'));
          addArtistBio($data);
        }
        else if ((time() - strtotime($data['bio_updated'])) > BIO_UPDATE_TIME) {
          $data['update_bio'] = true;
        }
        // Get logged in user's listening data.
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getArtistListenings($data);
        }
        $data['listener_count'] = sizeof(json_decode(getListeners($data), true));

        if (empty($data['spotify_uri'])) {
          $data['spotify_uri'] = getSpotifyResourceId($data);
        }

        $index = 1;
        $data['most_listened_alltime'] = false;
        $last_item_count = false;
        foreach (json_decode(getArtists(array('lower_limit' => '1970-00-00','limit' => 200))) as $item) {
          if ($item->artist_id === $data['artist_id']) {
            $data['most_listened_alltime'] = $index;
            break;
          }
          if ($item->count !== $last_item_count) {
            $last_item_count = $item->count;
            $index++;
          }
        }
        if ($this->session->userdata('username')) {
          $index = 1;
          $data['most_listened_alltime_user'] = false;
          $last_item_count = false;
          foreach (json_decode(getArtists(array('lower_limit' => '1970-00-00','limit' => 200,'username' => $this->session->userdata('username')))) as $item) {
            if ($item->artist_id === $data['artist_id']) {
              $data['most_listened_alltime_user'] = $index;
              break;
            }
            if ($item->count != $last_item_count) {
              $last_item_count = $item->count;
              $index++;
            }
          }
        }

        $data += $_REQUEST;
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        $data['js_include'] = array('music/artist', 'music/lastfm', 'helpers/artist_album_helper', 'helpers/tag_helper', 'libs/highcharts', 'libs/peity', 'helpers/chart_helper', 'helpers/shout_helper');
        
        $this->load->view('site_templates/header');
        $this->load->view('music/artist_view', $data);
        $this->load->view('site_templates/footer');
      }
      else {
        show_404();
      }
    }
  }

  public function album_or_month($value1, $value2) {
    $data = array();
    if ((int) $value1 > 1900 && (int) $value1 <= CUR_YEAR && (int) $value2 > 0 && (int) $value2 <= 12) {
      // Load helpers.
      $this->load->helper(array('img_helper', 'music_helper', 'shout_helper', 'fan_helper', 'love_helper', 'spotify_helper', 'output_helper'));
      $data['year'] = $value1;
      $data['month'] = $value2;
      $data += array(
        'lower_limit' => $data['year'] . '-' . $data['month'] . '-00',
        'upper_limit' => $data['year'] . '-' . $data['month'] . '-31',
        'limit' => '1',
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      $data['username'] = $this->session->userdata('username');
      $data['artist_count'] = getListeningCount($data, TBL_artist);
      $data['album_count'] = getListeningCount($data, TBL_album);
      $data['listening_count'] = getListeningCount($data, TBL_listening);
      $data['top_artist'] = (json_decode(getArtists($data), true) !== NULL) ? json_decode(getArtists($data), true)[0] : array();
      $data['top_album'] = (json_decode(getAlbums($data), true) !== NULL) ? json_decode(getAlbums($data), true)[0] : array();
      $data['where'] = TBL_album . '.`created` LIKE \'' . $data['year'] . '%\'';
      $data['new_artist_count'] = getListeningCount($data, TBL_artist);
      $data['new_album_count'] = getListeningCount($data, TBL_album);
      $data['fan_count'] = getFanCount($data);
      $data['love_count'] = getLoveCount($data);
      $data['shout_count'] = getShoutCount($data);
      $data['js_include'] = array('music/month', 'libs/highcharts', 'helpers/chart_helper');

      $this->load->view('site_templates/header');
      $this->load->view('music/month_view', $data);
      $this->load->view('site_templates/footer');
    }
    else {
      // Load helpers.
      $this->load->helper(array('img_helper', 'music_helper', 'spotify_helper', 'album_helper', 'nationality_helper', 'year_helper', 'output_helper'));

      $data['artist_name'] = decode($value1);
      $data['album_name'] = decode($value2);
      // Get artist information aka. artist's name and id.
      if ($data = getAlbumInfo($data)) {
        // Get albums's total listening data.
        $data += getAlbumListenings($data);
        // Get biography.
        $data += getAlbumBio($data);
        if (empty($data['bio_summary']) || empty($data['bio_content'])) {
          $this->load->helper(array('lastfm_helper'));
          unset($data['bio_summary']);
          unset($data['bio_content']);
          $data += fetchAlbumInfo($data, array('bio'));
          addAlbumBio($data);
        }
        else if ((time() - strtotime($data['bio_updated'])) > BIO_UPDATE_TIME) {
          $data['update_bio'] = true;
        }
        // Get logged in user's listening data.
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getAlbumListenings($data);
        }
        $data['listener_count'] = sizeof(json_decode(getListeners($data), true));
        if (empty($data['spotify_uri'])) {
          $data['spotify_uri'] = getSpotifyResourceId($data);
        }

        $index = 1;
        $data['most_listened_alltime'] = false;
        $last_item_count = false;
        foreach (json_decode(getAlbums(array('lower_limit' => '1970-00-00','limit' => 200))) as $item) {
          if ($item->album_id === $data['album_id']) {
            $data['most_listened_alltime'] = $index;
            break;
          }
          if ($item->count !== $last_item_count) {
            $last_item_count = $item->count;
            $index++;
          }
        }
        if ($this->session->userdata('username')) {
          $index = 1;
          $data['most_listened_alltime_user'] = false;
          $last_item_count = false;
          foreach (json_decode(getAlbums(array('lower_limit' => '1970-00-00','limit' => 200,'username' => $this->session->userdata('username')))) as $item) {
            if ($item->album_id === $data['album_id']) {
              $data['most_listened_alltime_user'] = $index;
              break;
            }
            if ($item->count != $last_item_count) {
              $last_item_count = $item->count;
              $index++;
            }
          }
        }
        $index = 1;
        $data['most_listened_releaseyear'] = false;
        $last_item_count = false;
        foreach (json_decode(getMusicByYear(array('lower_limit' => '1970-00-00','limit' => 200,'tag_id' => $data['year'],'username' => (!empty($_GET['u']) ? $_GET['u'] : '')))) as $item) {
          if ($item->album_id === $data['album_id']) {
            $data['most_listened_releaseyear'] = $index;
            break;
          }
          if ($item->count != $last_item_count) {
            $last_item_count = $item->count;
            $index++;
          }
        }

        $data += $_REQUEST;
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        $data['js_include'] = array('music/album', 'music/lastfm', 'helpers/artist_album_helper', 'helpers/tag_helper', 'libs/highcharts', 'libs/peity', 'helpers/chart_helper', 'helpers/shout_helper');

        $this->load->view('site_templates/header');
        $this->load->view('music/album_view', $data);
        $this->load->view('site_templates/footer');      
      }
      else {
        show_404();
      }
    }
  }

  public function recent($artist_name = '', $album_name = FALSE) {
    // Load helpers.
    $this->load->helper(array('form'));

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
        $data['js_include'] = array('music/recent_album', 'helpers/tag_helper');

        $this->load->view('site_templates/header');
        $this->load->view('music/recent_album_view', $data);
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
        $data['js_include'] = array('music/recent_artist', 'helpers/tag_helper');

        $this->load->view('site_templates/header');
        $this->load->view('music/recent_artist_view', $data);
        $this->load->view('site_templates/footer');
      }
      else {
        show_404();
      }
    }
    else {
      // Load helpers.
      $this->load->helper(array('img_helper', 'music_helper', 'output_helper'));

      $opts = array(
        'human_readable' => false,
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array();
      $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
      $data['js_include'] = array('music/recent', 'helpers/add_listening_helper');

      $this->load->view('site_templates/header');
      $this->load->view('music/recent_view', $data);
      $this->load->view('site_templates/footer');
    }
  }

  public function listener($artist_name = '', $album_name = FALSE) {
    // Load helpers.
    $this->load->helper(array('form'));

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
        $data['js_include'] = array('music/listener_album', 'helpers/tag_helper');

        $this->load->view('site_templates/header');
        $this->load->view('music/listener_album_view', $data);
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
        $data['js_include'] = array('music/listener_artist', 'helpers/tag_helper');

        $this->load->view('site_templates/header');
        $this->load->view('music/listener_artist_view', $data);
        $this->load->view('site_templates/footer');
      }
      else {
        show_404();
      }
    }
    else {
      // Load helpers
      $this->load->helper(array('img_helper', 'music_helper', 'output_helper'));
      
      $opts = array(
        'human_readable' => false,
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array();
      $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
      $data['js_include'] = array('music/listener');
      
      $this->load->view('site_templates/header');
      $this->load->view('music/listener_view', $data);
      $this->load->view('site_templates/footer');
    }
  }
}
?>
