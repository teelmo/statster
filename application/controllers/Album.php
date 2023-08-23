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
    $intervals = unserialize($this->session->userdata('intervals'));
    $data['top_album_album'] = isset($intervals['top_album_album']) ? $intervals['top_album_album'] : 'overall';
    $data['lower_limit'] = $data['top_album_album'];
    $data['upper_limit'] = CUR_DATE;
    $data['title'] = 'Albums';
    $data['side_title'] = 'Yearly';
    $data['day'] = '';
    $data['month'] = '';
    $data['year'] = '';

    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
    $data['js_include'] = array('music/albums', 'helpers/time_interval_helper');

    $data['total_count'] = getListeningCount(array(), TBL_album);
    if ($this->session->userdata('logged_in') === TRUE) {
      $data['user_count'] = getListeningCount(array('username' => $this->session->userdata('username')), TBL_album);
    }
      
    $this->load->view('site_templates/header');
    $this->load->view('music/albums_view', $data);
    $this->load->view('site_templates/footer');
  }
  public function stats($year, $month = FALSE, $day = FALSE) {
    // Load helpers.
    $this->load->helper(array('music_helper', 'img_helper', 'output_helper'));
    
    $data = array();
    $data['lower_limit'] = $year . '-' . (($month === FALSE) ? '00' : $month) . '-00';
    $data['upper_limit'] = $year . '-' . (($month === FALSE) ? '12' : $month) . '-31';
    if ($day !== FALSE) {
      $data['title'] = DateTime::createFromFormat('!Y-m-d', $year . '-' . $month . '-' . $day)->format('l') . ' '. DateTime::createFromFormat('!Y-m-d', $year . '-' . $month . '-' . $day)->format('jS') . ' of ' . DateTime::createFromFormat('!m', $month)->format('F') . ' ' . $year;
    }
    else if ($month !== FALSE) {
      $data['title'] = 'Albums in ' . DateTime::createFromFormat('!m', $month)->format('F') . ' ' . $year;
    }
    else {
      $data['title'] = 'Albums '. $year;
    }
    $data['side_title'] = ($month === FALSE) ? 'Monthly' : (($day === FALSE) ? 'Listenings' : 'Listeners');
    $data['day'] = $day;
    $data['month'] = $month;
    $data['year'] = $year;

    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
    $data['js_include'] = array('music/albums');
      
    $this->load->view('site_templates/header');
    $this->load->view('music/albums_view', $data);
    $this->load->view('site_templates/footer');
  }
  public function mosaic() {
    // Load helpers.
    $this->load->helper(array('music_helper', 'img_helper', 'output_helper'));

    $data = array();
    $intervals = unserialize($this->session->userdata('intervals'));
    $data['top_album_mosaic'] = isset($intervals['top_album_mosaic']) ? $intervals['top_album_mosaic'] : 'overall';
    $data['lower_limit'] = $data['top_album_mosaic'];
    $data['title'] = 'Albums';

    $opts = array(
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
    $data['js_include'] = array('music/albums_mosaic', 'helpers/time_interval_helper');

    $data['total_count'] = getListeningCount(array(), TBL_album);
    if ($this->session->userdata('logged_in') === TRUE) {
      $data['user_count'] = getListeningCount(array('username' => $this->session->userdata('username')), TBL_album);
    }

    $this->load->view('site_templates/header');
    $this->load->view('music/albums_mosaic', $data);
    $this->load->view('site_templates/footer');
  }
}
?>
