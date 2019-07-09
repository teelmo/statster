<?php
/*
 * This is the controller for the artists page, not for 
 * a single artist which is found from the music controller
 */
class Admin extends CI_Controller {

  public function index() {
    if (in_array($this->session->userdata['user_id'], ADMIN_USERS)) {

      // Load helpers.
      $this->load->helper(array());
      
      $data = array();
      
      $this->load->view('site_templates/header');
      $this->load->view('admin/admin_view', $data);
      $this->load->view('site_templates/footer');
    }
    else {
      show_403();
    }
  }
  public function artist($artist_id) {
    if (in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
      // Load helpers.
      $this->load->helper(array('artist_helper', 'img_helper'));

      $data = array();
      if (!empty($_POST)) {
        $data = $_POST;
        if ($data['image_uri'] === '') {
          $this->load->helper(array('lastfm_helper'));
          fetchArtistInfo($data, array('bio'));
        }
        else if (strpos($data['image_uri'], 'statster.') === false) {
          fetchImages($data, 'artist_img');
        }
        updateArtist($data);
        redirect('/music/' . url_title($data['artist_name']));
      }
      else {
        $this->load->helper(array('form'));

        $data += getArtistInfo(array('artist_id' => $artist_id));

        $data['image_uri'] = getArtistImg(array('artist_id' => $artist_id, 'size' => 32));

        $this->load->view('site_templates/header');
        $this->load->view('admin/edit_artist_view', $data);
        $this->load->view('site_templates/footer');
      }
    }
    else {
      show_403();
    }
  }
  public function album($album_id) {
    if (in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
      // Load helpers.
      $this->load->helper(array('album_helper', 'img_helper'));

      $data = array();
      if (!empty($_POST)) {
        $data = $_POST;
        if ($data['image_uri'] === '') {
          $this->load->helper(array('lastfm_helper'));
          fetchAlbumInfo($data, array('bio'));
        }
        else if (strpos($data['image_uri'], 'statster.') === false) {
          fetchImages($data, 'album_img');
        }
        updateAlbum($data);
        redirect('/music/' . url_title($data['artist_name']) . '/' . url_title($data['album_name']), 'refresh');
      }
      else {
        $this->load->helper(array('form'));

        $data += getAlbumInfo(array('album_id' => $album_id));

        $data['image_uri'] = getAlbumImg(array('album_id' => $album_id, 'size' => 32));

        $data['js_include'] = array('admin/edit_album');
        $this->load->view('site_templates/header');
        $this->load->view('admin/edit_album_view', $data);
        $this->load->view('site_templates/footer');
      }
    }
    else {
      show_403();
    }
  }
  public function genre($genre_id) {
    if (in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
      // Load helpers.
      $this->load->helper(array());

      $data = array();

      $this->load->view('site_templates/header');
      $this->load->view('music/edit_genre_view', $data);
      $this->load->view('site_templates/footer');
    }
    else {
      show_403();
    }
  }
  public function keyword($keyword_id) {
    if (in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
      // Load helpers.
      $this->load->helper(array());

      $data = array();

      $this->load->view('site_templates/header');
      $this->load->view('music/edit_keyword_view', $data);
      $this->load->view('site_templates/footer');
    }
    else {
      show_403();
    }
  }
  public function nationality($nationality_id) {
    if (in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
      // Load helpers.
      $this->load->helper(array());

      $data = array();

      $this->load->view('site_templates/header');
      $this->load->view('music/edit_nationality_view', $data);
      $this->load->view('site_templates/footer');
    }
    else {
      show_403();
    }
  }
}
?>
