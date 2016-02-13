<?php
class Tag extends CI_Controller {

  public function index() {
    $data['js_include'] = array('tags');

    $this->load->view('site_templates/header');
    $this->load->view('tag/tags_view');
    $this->load->view('site_templates/footer', $data);
  }

  public function genre($tag_name = '') {
    // Load helpers
    $this->load->helper(array('genre_helper', 'id_helper', 'img_helper', 'output_helper'));
    $this->load->view('site_templates/header');
  
    $data['tag_type'] = 'genre';
    if (!empty($tag_name)) {
      $data['tag_name'] = decode($tag_name);
      if ($data['tag_id'] = getGenreID($data)) {
        $data['js_include'] = array('tag', 'helpers/chart_helper');
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? TRUE : FALSE;
        $data += getGenreListenings($data);
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getGenreListenings($data);
        }
        $data['group_by'] = TBL_listening . '.`user_id`';
        $data['limit'] = '100';
        $data['listener_count'] = sizeof(json_decode(getMusicByGenre($data), true));

        $this->load->view('tag/tag_view', $data);
      }
      else {
        show_404();
      }
    }
    else {
      $data['js_include'] = array('genres');
      $this->load->view('tag/genre_view', $data);
    }
    $this->load->view('site_templates/footer');
  }

  public function keyword($tag_name = '') {
    // Load helpers
    $this->load->helper(array('keyword_helper', 'id_helper', 'img_helper', 'output_helper'));
    $this->load->view('site_templates/header');

    $data['tag_type'] = 'keyword';
    if (!empty($tag_name)) {
      $data['tag_name'] = decode($tag_name);
      if ($data['tag_id'] = getKeywordID($data)) {
        $data['js_include'] = array('tag', 'helpers/chart_helper');
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? TRUE : FALSE;
        $data += getKeywordListenings($data);
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getKeywordListenings($data);
        }
        $data['group_by'] = TBL_listening . '.`user_id`';
        $data['limit'] = '100';
        $data['listener_count'] = sizeof(json_decode(getMusicByKeyword($data), true));

        $this->load->view('tag/tag_view', $data);
      }
      else {
        show_404();
      }
    }
    else {
      $data['js_include'] = array('keywords');
      $this->load->view('tag/keyword_view', $data);
    }
    $this->load->view('site_templates/footer');
  }

  public function nationality($tag_name = '') {
    $this->load->helper(array('nationality_helper', 'id_helper', 'img_helper', 'output_helper'));
    $this->load->view('site_templates/header');
    
    $data['tag_type'] = 'nationality';
    if (!empty($tag_name)) {
      $data['tag_name'] = decode($tag_name);
      if ($data['tag_id'] = getNationalityID($data)) {
        $data['js_include'] = array('tag', 'helpers/chart_helper');
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? TRUE : FALSE;
        $data += getNationalityListenings($data);
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getNationalityListenings($data);
        }
        $data['group_by'] = TBL_listening . '.`user_id`';
        $data['limit'] = '100';
        $data['listener_count'] = sizeof(json_decode(getMusicByNationality($data), true));

        $this->load->view('tag/tag_view', $data);
      }
      else {
        show_404();
      }
    }
    else {
      $data['js_include'] = array('nationalities');
      $this->load->view('tag/nationality_view', $data);
    }
    $this->load->view('site_templates/footer');
  }

  public function year($tag_name = '') {
    $this->load->helper(array('year_helper', 'img_helper', 'output_helper'));
    $this->load->view('site_templates/header');
    
    $data['tag_type'] = 'year';
    if (!empty($tag_name)) {
      $data['tag_id'] = decode($tag_name);
      $data['tag_name'] = decode($tag_name);
      $data['js_include'] = array('tag', 'helpers/chart_helper');
      $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? TRUE : FALSE;
      $data += getYearListenings($data);
      if ($data['user_id'] = $this->session->userdata('user_id')) {
        $data += getYearListenings($data);
      }
      $data['group_by'] = TBL_listening . '.`user_id`';
      $data['limit'] = '100';
      $data['listener_count'] = sizeof(json_decode(getMusicByYear($data), true));

      $this->load->view('tag/tag_view', $data);
    }
    else {
      $data['js_include'] = array('years', 'helpers/chart_helper');
      $this->load->view('tag/year_view', $data);
    }
    $this->load->view('site_templates/footer');
  }
}
?>
