<?php
// application/core/MY_Exceptions.php
class MY_Exceptions extends CI_Exceptions {
  public function show_404($page = '', $log_error = true)
  {
    $ci =& get_instance();
    $ci->load->helper(array('img_helper', 'music_helper', 'output_helper'));
    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data = array();
    $data['js_include'] = array('404');
    $data['top_artist'] = (json_decode(getArtists($opts) ?? '', true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0, 'artist_name' => 'No data', 'count' => 0);
    $ci->load->view('site_templates/header');
    $ci->load->view('404_view', $data);
    $ci->load->view('site_templates/footer', $data);
    echo $ci->output->get_output();
    exit;
  }
}