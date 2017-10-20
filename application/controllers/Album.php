<?php
/*
 * This is the controller for the albums page, not for 
 * a single album which is found from the music controller
 */
class Album extends CI_Controller {

  public function index() {
    // Load helpers.
    $this->load->helper(array('music_helper', 'img_helper', 'output_helper'));

    $data = array();
    $data['lower_limit'] = '1970-00-00';
    $data['upper_limit'] = CUR_DATE;
    $data['title'] = 'Albums';
    $data['side_title'] = 'Yearly';
    $data['day'] = '';
    $data['month'] = '';
    $data['year'] = '';

    $opts = array(
      'human_readable' => false,
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31'
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array();
    $data['js_include'] = array('albums');
      
    $this->load->view('site_templates/header');
    $this->load->view('music/albums_view', $data);
    $this->load->view('site_templates/footer');
  }
  public function stats($year, $month = FALSE) {
    // Load helpers.
    $this->load->helper(array('music_helper', 'img_helper', 'output_helper'));
    
    $data = array();
    $data['lower_limit'] = $year . '-' . (($month === FALSE) ? '00' : $month) . '-00';
    $data['upper_limit'] = $year . '-' . (($month === FALSE) ? '12' : $month) . '-31';
    $data['title'] = (($month === FALSE) ? 'Albums '. $year : 'Albums '. $year . ' ' . DateTime::createFromFormat('!m', $month)->format('F'));
    $data['side_title'] = ($month === FALSE) ? 'Monthly': 'Daily';
    $data['day'] = '';
    $data['month'] = $month;
    $data['year'] = $year;

    $opts = array(
      'human_readable' => false,
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31'
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array();
    $data['js_include'] = array('albums');
      
    $this->load->view('site_templates/header');
    $this->load->view('music/albums_view', $data);
    $this->load->view('site_templates/footer');
  }
}
?>
