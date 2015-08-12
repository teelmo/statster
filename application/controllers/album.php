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
    $data['title'] = 'Artists';

    $this->load->view('templates/header');
    $this->load->view('music/albums_view', $data);
    $this->load->view('templates/footer', $data);
  }

  public function stats($year, $month = FALSE) {
    if ($month != FALSE) {
      $data['lower_limit'] = $year . '-' . $month . '-' . '00'; 
      $data['upper_limit'] = $year . '-' . $month . '-' . '31';
      $data['title'] = 'Artists <span class="meta">' . $year . DateTime::createFromFormat('!m', $month)->format('F') . ' ' . $year . '</span>';
    }
    else {
      $data['lower_limit'] = $year . '-00-' . '00'; 
      $data['upper_limit'] = $year . '-12-' . '31';
      $data['title'] = 'Artists <span class="meta">' . $year . '</span>';
    }

    $data['js_include'] = array('albums');

    $this->load->view('templates/header');
    $this->load->view('music/albums_view', $data);
    $this->load->view('templates/footer', $data);
  }
}
?>