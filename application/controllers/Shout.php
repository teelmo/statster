<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Shout extends MY_Controller {

  public function index($artist_name = '', $album_name = '') {
    $data = array();
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
        $data['js_include'] = array('shout/shout_album', 'helpers/tag_helper');

        $this->load->view('site_templates/header');
        $this->load->view('shout/shout_album_view', $data);
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

        $data['listener_count'] = count(json_decode(getListeners($data), true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        $data['js_include'] = array('shout/shout_artist', 'helpers/tag_helper');

        $this->load->view('site_templates/header');
        $this->load->view('shout/shout_artist_view', $data);
        $this->load->view('site_templates/footer', $data);
      }
      else {
        show_404();
      }
    }
    else {
      // Load helpers.
      $this->load->helper(array('music_helper', 'shout_helper', 'img_helper', 'id_helper', 'output_helper'));

      $opts = array(
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      $data['top_artist'] = (json_decode(getArtists($opts) ?? '', true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
      $opts = array(
        'limit' => '1000',
        'lower_limit' => '1970-00-00',  
        'type' => 'user'
      );
      $data['total_album_shouts'] = getAlbumShoutCount($opts);
      $data['total_artist_shouts'] = getArtistShoutCount($opts);
      $data['total_user_shouts'] = getUserShoutCount($opts);
      if ($this->session->userdata('logged_in') === TRUE) {
        $data['total_album_shouts_user_count'] = getAlbumShoutCount(array('user_id' => $this->session->userdata('user_id')), TBL_artist);
        $data['total_artist_shouts_user_count'] = getArtistShoutCount(array('user_id' => $this->session->userdata('user_id')), TBL_artist);
        $data['total_user_shouts_user_count'] = getUserShoutCount(array('user_id' => $this->session->userdata('user_id')), TBL_artist);
      }
      $data['js_include'] = array('shout/shout', 'helpers/shout_helper');

      $this->load->view('site_templates/header');
      $this->load->view('shout/shout_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
  }
  public function album() {
    // Load helpers.
    $this->load->helper(array('music_helper', 'shout_helper', 'img_helper', 'id_helper', 'output_helper'));

    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = (json_decode(getArtists($opts) ?? '', true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
    $opts = array(
      'limit' => '1000',
      'lower_limit' => '1970-00-00',  
      'type' => 'user',
      'user_id' => (!empty($_GET['u']) ? getUserID(array('username' => $_GET['u'])) : '')
    );
    $data['total_count'] = getAlbumShoutCount(array(), TBL_artist);
    if ($this->session->userdata('logged_in') === TRUE) {
      $data['user_count'] = getAlbumShoutCount(array('user_id' => $this->session->userdata('user_id')), TBL_artist);
    }
    $data['js_include'] = array('shout/shout_albums', 'helpers/shout_helper');

    $this->load->view('site_templates/header');
    $this->load->view('shout/shout_albums_view', $data);
    $this->load->view('site_templates/footer', $data);
  }
  public function artist() {
    // Load helpers.
    $this->load->helper(array('music_helper', 'shout_helper', 'img_helper', 'id_helper', 'output_helper'));

    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = (json_decode(getArtists($opts) ?? '', true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
    $opts = array(
      'limit' => '1000',
      'lower_limit' => '1970-00-00',  
      'type' => 'user',
      'user_id' => (!empty($_GET['u']) ? getUserID(array('username' => $_GET['u'])) : '')
    );
    $data['total_count'] = getArtistShoutCount(array(), TBL_artist);
    if ($this->session->userdata('logged_in') === TRUE) {
      $data['user_count'] = getArtistShoutCount(array('user_id' => $this->session->userdata('user_id')), TBL_artist);
    }
    $data['js_include'] = array('shout/shout_artists', 'helpers/shout_helper');

    $this->load->view('site_templates/header');
    $this->load->view('shout/shout_artists_view', $data);
    $this->load->view('site_templates/footer', $data);
  }
  public function user() {
    // Load helpers.
    $this->load->helper(array('music_helper', 'shout_helper', 'img_helper', 'id_helper', 'output_helper'));

    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = (json_decode(getArtists($opts) ?? '', true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
    $opts = array(
      'limit' => '1000',
      'lower_limit' => '1970-00-00',  
      'type' => 'user',
      'user_id' => (!empty($_GET['u']) ? getUserID(array('username' => $_GET['u'])) : '')
    );
    $data['total_count'] = getUserShoutCount(array(), TBL_artist);
    if ($this->session->userdata('logged_in') === TRUE) {
      $data['user_count'] = getUserShoutCount(array('user_id' => $this->session->userdata('user_id')), TBL_artist);
    }
    $data['js_include'] = array('shout/shout_users', 'helpers/shout_helper');

    $this->load->view('site_templates/header');
    $this->load->view('shout/shout_users_view', $data);
    $this->load->view('site_templates/footer', $data);
  }
}
?>
