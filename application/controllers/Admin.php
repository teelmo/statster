<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * This is the controller for the artists page, not for 
 * a single artist which is found from the music controller
 */
class Admin extends MY_Controller {

  public function index() {
    if (isset($this->session->userdata['user_id']) && in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
      // Load helpers.
      $this->load->helper(array('form', 'img_helper', 'artist_helper', 'album_helper', 'music_helper', 'output_helper'));

      $data = array();

      $opts = array(
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('name' => 'No data', 'count' => 0);

      $data['all_artists'] = getArtistsUnique();
      $data['all_albums'] = getAlbumsUnique();
      $data['js_include'] = array('admin/admin');

      $this->load->view('site_templates/header');
      $this->load->view('admin/admin_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
    else {
      show_404();
    }
  }
  public function artist($artist_id) {
    if (isset($this->session->userdata['user_id']) && in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
      // Load helpers.
      $this->load->helper(array('artist_helper', 'img_helper', 'output_helper'));

      $data = array();
      if (!empty($_POST)) {
        $data = $_POST;
        if (strpos($data['image_uri'], IMAGE_SERVER) === FALSE) {
          fetchImages($data, 'artist');
        }
        $data['user_id'] = $this->session->userdata['user_id'];
        updateArtist($data);
        updateAssociatedArtists($data);
        redirect('/music/' . url_title($data['artist_name']));
      }
      else {
        $this->load->helper(array('form'));

        $data += getArtistInfo(array('artist_id' => $artist_id));

        $data['associated_artists'] = is_string(getAssociatedArtists($data)) ? json_decode(getAssociatedArtists($data), true) : array();
        $data['all_artists'] = getArtistsUnique();
        $data['image_uri'] = getArtistImg(array('artist_id' => $artist_id, 'size' => 32));
        $data['js_include'] = array('admin/edit_artist');

        $this->load->view('site_templates/header');
        $this->load->view('admin/edit_artist_view', $data);
        $this->load->view('site_templates/footer', $data);
      }
    }
    else {
      show_404();
    }
  }
  public function album($album_id) {
    if (isset($this->session->userdata['user_id']) && in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
      // Load helpers.
      $this->load->helper(array('album_helper', 'artist_helper', 'img_helper'));

      $data = array();
      if (!empty($_POST)) {
        $data = $_POST;
        if (strpos($data['image_uri'], IMAGE_SERVER) === FALSE) {
          $data['type'] = 'album';
          fetchImages($data, 'album');
        }
        $data['user_id'] = $this->session->userdata['user_id'];
        updateAlbum($data);
        if (in_array($data['parent_artist_name'], array_map(function($artist_id) { return getArtistInfo(array('artist_id' => $artist_id))['artist_name'];}, $data['artist_ids']))) {
          redirect('/music/' . url_title($data['parent_artist_name']) . '/' . url_title($data['album_name']));
        } 
        else {
          redirect('/music/' . url_title(getArtistInfo(array('artist_id' => $data['artist_ids'][0]))['artist_name']) . '/' . url_title($data['album_name']));
        }
      }
      else {
        $this->load->helper(array('form', 'artist_helper', 'music_helper', 'year_helper', 'output_helper'));

        $data += getAlbumInfo(array('album_id' => $album_id));

        $artists = array_map(function($artist) { 
          return array('artist_id' => $artist['artist_id'],
                       'artist_name' => $artist['artist_name']);
        }, $data);
        $data += $data[0];

        $data['all_artists'] = getArtistsUnique();
        $data['artists'] = $artists;
        $rank = 0;
        $data['most_listened_releaseyear'] = false;
        $last_item_count = false;
        foreach (json_decode(getMusicByYear(array('lower_limit' => '1970-00-00', 'limit' => 10, 'tag_id' => $data['year'], 'username' => (!empty($_GET['u']) ? $_GET['u'] : '')))) as $item) {
          if ($item->count != $last_item_count) {
            $rank++;
          }
          if ($item->album_id == $data['album_id']) {
            $data['most_listened_releaseyear'] = $rank;
            break;
          }
          $last_item_count = $item->count;
        }
        $data['image_uri'] = getAlbumImg(array('album_id' => $album_id, 'size' => 32));
        $data['js_include'] = array('admin/edit_album');

        $this->load->view('site_templates/header');
        $this->load->view('admin/edit_album_view', $data);
        $this->load->view('site_templates/footer', $data);
      }
    }
    else {
      show_404();
    }
  }
  public function genre($genre_id) {
    if (isset($this->session->userdata['user_id']) && in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
      // Load helpers.
      $this->load->helper(array());

      $data = array();

      $this->load->view('site_templates/header');
      $this->load->view('music/edit_genre_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
    else {
      show_404();
    }
  }
  public function keyword($keyword_id) {
    if (isset($this->session->userdata['user_id']) && in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
      // Load helpers.
      $this->load->helper(array());

      $data = array();

      $this->load->view('site_templates/header');
      $this->load->view('music/edit_keyword_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
    else {
      show_404();
    }
  }
  public function nationality($nationality_id) {
    if (isset($this->session->userdata['user_id']) && in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
      // Load helpers.
      $this->load->helper(array());

      $data = array();

      $this->load->view('site_templates/header');
      $this->load->view('music/edit_nationality_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
    else {
      show_404();
    }
  }
}
?>
