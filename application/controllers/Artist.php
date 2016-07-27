<?php
/*
 * This is the controller for the artists page, not for 
 * a single artist which is found from the music controller
 */
class Artist extends CI_Controller {

  public function index() {
    $data['js_include'] = array('artists');
    $data['lower_limit'] = '1970-01-01';
    $data['upper_limit'] = CUR_DATE;
    $data['title'] = 'Artists';
    $data['year'] = '';
    $data['month'] = '';
    $data['side_title'] = 'Yearly';

    $this->load->view('site_templates/header');
    $this->load->view('music/artists_view', $data);
    $this->load->view('site_templates/footer', $data);
  }

  public function stats($year, $month = FALSE, $day = FALSE) {
    if ($day !== FALSE) {
      $data['lower_limit'] = $year . '-' . $month . '-' . '00';
      $data['upper_limit'] = $year . '-' . $month . '-' . '31';
      $data['title'] = 'Listened ' . intval($day) . date('S',mktime(1, 1, 1, 1, ((($day >= 10) + ($day >= 20) + ( $day == 0)) * 10 + $day % 10))) . ' of ' . DateTime::createFromFormat('!m', $month)->format('F') . ' ' . $year; 
      $data['year'] = $year;
      $data['month'] = $month;
      $data['day'] = $day;
      $data['side_title'] = 'Daily';
    }
    else if ($month !== FALSE) {
      $data['lower_limit'] = $year . '-' . $month . '-' . '00';
      $data['upper_limit'] = $year . '-' . $month . '-' . '31';
      $data['title'] = 'Artists <span class="meta">' . DateTime::createFromFormat('!m', $month)->format('F') . ' ' . $year . '</span>';
      $data['year'] = $year;
      $data['month'] = $month;
      $data['day'] = '';
      $data['side_title'] = 'Daily';
    }
    else {
      $data['lower_limit'] = $year . '-00-' . '00';
      $data['upper_limit'] = $year . '-12-' . '31';
      $data['title'] = 'Artists <span class="meta">' . $year . '</span>';
      $data['year'] = $year;
      $data['month'] = '';
      $data['day'] = '';
      $data['side_title'] = 'Monthly';
    }

    $data['js_include'] = array('artists');

    $this->load->view('site_templates/header');
    $this->load->view('music/artists_view', $data);
    $this->load->view('site_templates/footer', $data);
  }
}
?>
