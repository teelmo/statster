<?php
/*
 * This is the controller for the albums page, not for 
 * a single album which is found from the music controller
 */
class Album extends CI_Controller {

  public function index() {
    $data['js_include'] = array('albums');
    $data['lower_limit'] = '1970-01-01';
    $data['upper_limit'] = CUR_DATE;
    $data['title'] = 'Albums';
    $data['year'] = '';
    $data['month'] = '';
    $data['side_title'] = 'Yearly';

    $this->load->view('site_templates/header');
    $this->load->view('music/albums_view', $data);
    $this->load->view('site_templates/footer', $data);
  }

  public function stats($year, $month = FALSE, $day = FALSE) {
    if ($month !== FALSE) {
      $data['lower_limit'] = $year . '-' . $month . '-' . '00';
      $data['upper_limit'] = $year . '-' . $month . '-' . '31';
      $data['title'] = 'Listened ' . intval($day) . date('S',mktime(1, 1, 1, 1, ((($day >= 10) + ($day >= 20) + ( $day == 0)) * 10 + $day % 10))) . ' of ' . DateTime::createFromFormat('!m', $month)->format('F') . ' ' . $year;
      $data['year'] = $year;
      $data['month'] = $month;
      $data['day'] = $day;
      $data['side_title'] = 'Daily';
    }
    else if ($month != FALSE) {
      $data['lower_limit'] = $year . '-' . $month . '-' . '00';
      $data['upper_limit'] = $year . '-' . $month . '-' . '31';
      $data['title'] = 'Albums <span class="meta">' . DateTime::createFromFormat('!m', $month)->format('F') . ' ' . $year . '</span>';
      $data['year'] = $year;
      $data['month'] = $month;
      $data['side_title'] = 'Daily';
    }
    else {
      $data['lower_limit'] = $year . '-00-' . '00';
      $data['upper_limit'] = $year . '-12-' . '31';
      $data['title'] = 'Albums <span class="meta">' . $year . '</span>';
      $data['year'] = $year;
      $data['month'] = '';
      $data['side_title'] = 'Monthly';
    }

    $data['js_include'] = array('albums');

    $this->load->view('site_templates/header');
    $this->load->view('music/albums_view', $data);
    $this->load->view('site_templates/footer', $data);
  }
}
?>
