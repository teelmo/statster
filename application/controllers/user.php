<?php
class User extends CI_Controller {

  public function index() {
    $data['js_include'] = array('user');

    $this->load->view('site_templates/header');
    $this->load->view('user/user_view', $data);
    $this->load->view('site_templates/footer');
  }

  public function profile($username) {
    $this->load->helper(array('user_helper', 'img_helper', 'music_helper', 'output_helper'));

    $data['username'] = $username;

    if ($data = getUser($data)) {
      $data['js_include'] = array('profile');
      $data['username'] = $this->uri->segment(2);
      $data['interval'] = 0;
      $opts = array(
        'lower_limit' => '1970-00-00',
        'limit' => '1',
        'username' => $username,
        'human_readable' => true
      );
      $data_tmp = json_decode(getArtists($opts), true);
      $data += $data_tmp[0];
      $data += getUserTags($data);
      $data['artist_count'] = getListeningCount($data, TBL_artist);
      $data['album_count'] = getListeningCount($data, TBL_album);
      $data['listening_count'] = getListeningCount($data, TBL_listening);

      $this->load->view('site_templates/header');
      $this->load->view('user/profile_view', $data);
      $this->load->view('site_templates/footer');
    }
    else {
      show_404();
    }
  }

  public function edit() {
    if ($this->session->userdata('logged_in') !== TRUE) {
      redirect('/login?redirect=user/edit', 'refresh');
    }

    // Load helpers
    $this->load->helper(array('form'));
      
    $this->load->view('site_templates/header');
    $this->load->view('user/edit_view');
    $this->load->view('site_templates/footer');
  }
}
?>