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

    $this->load->view('site_templates/header');
    $this->load->view('music/artists_view', $data);
    $this->load->view('site_templates/footer', $data);
  }

  public function stats($year, $month = FALSE) {
    if ($month != FALSE) {
      $data['lower_limit'] = $year . '-' . $month . '-' . '00'; 
      $data['upper_limit'] = $year . '-' . $month . '-' . '31';
      $data['title'] = 'Artists <span class="meta">' . DateTime::createFromFormat('!m', $month)->format('F') . ' ' . $year . '</span>';
    }
    else {
      $data['lower_limit'] = $year . '-00-' . '00'; 
      $data['upper_limit'] = $year . '-12-' . '31';
      $data['title'] = 'Artists <span class="meta">' . $year . '</span>';
    }

    $data['js_include'] = array('artists');

    $this->load->view('site_templates/header');
    $this->load->view('music/artists_view', $data);
    $this->load->view('site_templates/footer', $data);
  }
}
?>