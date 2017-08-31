<?php
class Search extends CI_Controller {

  public function index() {
    // Load helpers
    $this->load->helper(array('img_helper', 'music_helper', 'output_helper'));

    $data = array();
    $data['js_include'] = array('search');
    $data['search'] = $_POST['searchStr'];
    $opts = array(
      'human_readable' => false,
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31'
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array();

    $this->load->view('site_templates/header');
    $this->load->view('search/search_view', $data);
    $this->load->view('site_templates/footer');
  }
}
?>
