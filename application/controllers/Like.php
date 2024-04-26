<?php
class Like extends CI_Controller {

  public function index($artist_name = '', $album_name = '') {
    if (!empty($album_name)) {
      // Load helpers.
      $this->load->helper(array('img_helper', 'music_helper', 'album_helper', 'year_helper', 'output_helper'));

      $data['artist_name'] = decode($artist_name);
      $data['album_name'] = decode($album_name);
      if ($data = getAlbumInfo($data)) {
        $artists = array_map(function($artist) {
          return array('artist_id' => $artist['artist_id'],
                       'artist_name' => $artist['artist_name']);
        }, $data);
        $data = $data[0];
        usort($artists, function($artist_a, $artist_b) use ($artist_name) {
          ($artist_b['artist_name'] === decode($artist_name)) ? 1 : 0;
        });
        $data['artists'] = $artists;
        // Get albums's total listening data.
        $data += getAlbumListenings($data);
        // Get logged in user's listening data.
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getAlbumListenings($data);
        }

        $rank = 0;
        $data['most_listened_alltime'] = false;
        $last_item_count = false;
        foreach (json_decode(getAlbums(array('lower_limit' => '1970-00-00','limit' => 200))) as $item) {
          if ($item->count != $last_item_count) {
            $rank++;
          }
          if ($item->album_id == $data['album_id']) {
            $data['most_listened_alltime'] = $rank;
            break;
          }
          $last_item_count = $item->count;
        }
        if ($this->session->userdata('username')) {
          $rank = 0;
          $data['most_listened_alltime_user'] = false;
          $last_item_count = false;
          foreach (json_decode(getAlbums(array('lower_limit' => '1970-00-00','limit' => 200,'username' => $this->session->userdata('username')))) as $item) {
            if ($item->count != $last_item_count) {
              $rank++;
            }
            if ($item->album_id == $data['album_id']) {
              $data['most_listened_alltime_user'] = $rank;
              break;
            }
            $last_item_count = $item->count;
          }
        }
        $rank = 0;
        $data['most_listened_releaseyear'] = false;
        $last_item_count = false;
        foreach (json_decode(getMusicByYear(array('lower_limit' => '1970-00-00','limit' => 10,'tag_id' => $data['year'],'username' => (!empty($_GET['u']) ? $_GET['u'] : '')))) as $item) {
          if ($item->count != $last_item_count) {
            $rank++;
          }
          if ($item->album_id == $data['album_id']) {
            $data['most_listened_releaseyear'] = $rank;
            break;
          }
          $last_item_count = $item->count;
        }
        
        $data['listener_count'] = count(json_decode(getListeners($data), true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        $data['js_include'] = array('like/like_album', 'helpers/tag_helper');

        $this->load->view('site_templates/header');
        $this->load->view('like/like_album_view', $data);
        $this->load->view('site_templates/footer', $data);
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
        // Get artist's total listening data.
        $data += getArtistListenings($data);
        // Get logged in user's listening data.
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getArtistListenings($data);
        }
        $rank = 0;
        $data['most_listened_alltime'] = false;
        $last_item_count = false;
        foreach (json_decode(getArtists(array('lower_limit' => '1970-00-00','limit' => 200))) as $item) {
          if ($item->count != $last_item_count) {
            $rank++;
          }
          if ($item->artist_id == $data['artist_id']) {
            $data['most_listened_alltime'] = $rank;
            break;
          }
          $last_item_count = $item->count;
        }
        if ($this->session->userdata('username')) {
          $rank = 0;
          $data['most_listened_alltime_user'] = false;
          $last_item_count = false;
          foreach (json_decode(getArtists(array('lower_limit' => '1970-00-00','limit' => 200,'username' => $this->session->userdata('username')))) as $item) {
            if ($item->count != $last_item_count) {
              $rank++;
            }
            if ($item->artist_id == $data['artist_id']) {
              $data['most_listened_alltime_user'] = $rank;
              break;
            }
            $last_item_count = $item->count;
          }
        }

        $rank = 0;
        $data['most_listened_alltime'] = false;
        $last_item_count = false;
        foreach (json_decode(getArtists(array('lower_limit' => '1970-00-00','limit' => 200))) as $item) {
          if ($item->count != $last_item_count) {
            $rank++;
          }
          if ($item->artist_id == $data['artist_id']) {
            $data['most_listened_alltime'] = $rank;
            break;
          }
          $last_item_count = $item->count;
        }
        if ($this->session->userdata('username')) {
          $rank = 0;
          $data['most_listened_alltime_user'] = false;
          $last_item_count = false;
          foreach (json_decode(getArtists(array('lower_limit' => '1970-00-00','limit' => 200,'username' => $this->session->userdata('username')))) as $item) {
            if ($item->count != $last_item_count) {
              $rank++;
            }
            if ($item->artist_id == $data['artist_id']) {
              $data['most_listened_alltime_user'] = $rank;
              break;
            }
            $last_item_count = $item->count;
          }
        }
        $data['listener_count'] = count(json_decode(getListeners($data), true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        $data['js_include'] = array('like/like_artist', 'helpers/tag_helper');

        $this->load->view('site_templates/header');
        $this->load->view('like/like_artist_view', $data);
        $this->load->view('site_templates/footer', $data);
      }
      else {
        show_404();
      }
    }
    else {
      // Load helpers.
      $this->load->helper(array('music_helper', 'fan_helper', 'love_helper', 'id_helper', 'img_helper', 'output_helper'));

      $data = array();
      $opts = array(
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
      $opts = array(
        'limit' => '1000',
        'lower_limit' => '1970-00-00',
      );
      $data['total_album_loves'] = getLoveCount($opts);
      $data['total_artist_fans'] = getFanCount($opts);
      if ($this->session->userdata('logged_in') === TRUE) {
        $data['total_album_loves_user_count'] = getLoveCount(array('user_id' => $this->session->userdata('user_id')), TBL_album);
        $data['total_artist_fans_user_count'] = getFanCount(array('user_id' => $this->session->userdata('user_id')), TBL_artist);
      }
      $data['js_include'] = array('like/like');

      $this->load->view('site_templates/header');
      $this->load->view('like/like_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
  }

  public function love() {
    // Load helpers.
    $this->load->helper(array('music_helper', 'love_helper', 'img_helper', 'output_helper'));

    $data = array();
    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
    $data['total_count'] = getLoveCount(array(), TBL_artist);
    if ($this->session->userdata('logged_in') === TRUE) {
      $data['user_count'] = getLoveCount(array('user_id' => $this->session->userdata('user_id')), TBL_artist);
    }
    $data['js_include'] = array('like/love');

    $this->load->view('site_templates/header');
    $this->load->view('like/love_view', $data);
    $this->load->view('site_templates/footer', $data);
  }

  public function fan() {
    // Load helpers.
    $this->load->helper(array('music_helper', 'fan_helper', 'img_helper', 'output_helper'));

    $data = array();
    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
    $data['total_count'] = getFanCount(array(), TBL_artist);
    if ($this->session->userdata('logged_in') === TRUE) {
      $data['user_count'] = getFanCount(array('user_id' => $this->session->userdata('user_id')), TBL_artist);
    }
    $data['js_include'] = array('like/fan');

    $this->load->view('site_templates/header');
    $this->load->view('like/fan_view', $data);
    $this->load->view('site_templates/footer', $data);
  }
}