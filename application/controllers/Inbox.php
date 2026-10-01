<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Inbox extends MY_Controller {

  const FOLDERS = array('inbox', 'sent', 'notices', 'notifications', 'shares', 'trash');

  public function index($folder = 'inbox') {
    if ($this->session->userdata('logged_in') !== TRUE) {
      redirect('/login?redirect=inbox', 'refresh');
    }
    if (!in_array($folder, self::FOLDERS, TRUE)) {
      show_404();
    }
    // Load helpers.
    $this->load->helper(array('img_helper', 'music_helper', 'output_helper'));

    $data = array();
    $data['folder'] = $folder;
    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = decodeFirstOrDefault(getArtists($opts), array('artist_id' => 0));
    $data['js_include'] = array('inbox/inbox');

    $this->load->view('site_templates/header');
    $this->load->view('inbox/inbox_view', $data);
    $this->load->view('site_templates/footer', $data);
  }
}
?>
