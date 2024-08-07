<?php
class Search extends CI_Controller {

  public function index() {
    // Load helpers.
    $this->load->helper(array('img_helper', 'music_helper', 'output_helper'));

    $data = array();
    $data['q'] = (isset($_REQUEST['q'])) ? $_REQUEST['q'] : '';
    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = (json_decode(getArtists($opts) ?? '', true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
    $data['js_include'] = array('search/search');

    $this->load->view('site_templates/header');
    $this->load->view('search/search_view', $data);
    $this->load->view('site_templates/footer', $data);
  }
}
?>
