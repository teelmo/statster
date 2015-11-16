<?php
class Tag extends CI_Controller {

  public function index() {
    $data['request'] = array('tag');

    $this->load->view('site_templates/header');
    $this->load->view('tag/meta_view');
    $this->load->view('site_templates/footer', $data);
  }

  public function genre($tag_name = '') {
    // Load helpers
    $this->load->helper(array('genre_helper', 'id_helper', 'img_helper', 'output_helper'));
    $this->load->view('site_templates/header');
  
    $data['tag_type'] = 'Genre';
    if (!empty($tag_name)) {
      $data['tag_name'] = decode($tag_name);
      if ($data['tag_id'] = getGenreID($data)) {
        $data['js_include'] = array('tag');
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? TRUE : FALSE;
        $data += getGenreListenings($data);
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getGenreListenings($data);
        }
        $data['group_by'] = TBL_listening . '.`user_id`';
        $data['listener_count'] = sizeof(json_decode(getMusicByGenre($data), true));

        $this->load->view('tag/tag_view', $data);
      }
      else {
        show_404();
      }
    }
    else {
      $this->load->view('tag/tags_view', $data);
    }
    $this->load->view('site_templates/footer');
  }

  public function keyword($tag_name = '') {
    // Load helpers
    $this->load->helper(array('keyword_helper', 'id_helper', 'img_helper', 'output_helper'));
    $this->load->view('site_templates/header');

    $data['tag_type'] = 'Keyword';
    if (!empty($tag_name)) {
      $data['tag_name'] = decode($tag_name);
      if ($data['tag_id'] = getKeywordID($data)) {
        $data['js_include'] = array('tag');
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? TRUE : FALSE;
        $data += getKeywordListenings($data);
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getKeywordListenings($data);
        }
        $data['group_by'] = TBL_listening . '.`user_id`';
        $data['listener_count'] = sizeof(json_decode(getMusicByKeyword($data), true));

        $this->load->view('tag/tag_view', $data);
      }
      else {
        show_404();
      }
    }
    else {
      $this->load->view('tag/tags_view', $data);
    }
    $this->load->view('site_templates/footer');
  }

  public function year($tag_name = '') {
    $this->load->helper(array('id_helper', 'img_helper', 'output_helper'));
    $this->load->view('site_templates/header');
    
    $data['tag_type'] = 'Year';
    if (!empty($tag_name)) {
      $data['tag_name'] = decode($tag_name);
      if ($data['tag_id'] = getKeywordID($data)) {
        $data['js_include'] = array('tag');
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? TRUE : FALSE;
        $data += getKeywordListenings($data);
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getKeywordListenings($data);
        }
        $data['group_by'] = TBL_listening . '.`user_id`';
        $data['listener_count'] = sizeof(json_decode(getMusicByKeyword($data), true));

        $this->load->view('tag/tag_view', $data);
      }
      else {
        show_404();
      }
    }
    else {
      $this->load->view('tag/tags_view', $data);
    }
    $this->load->view('site_templates/footer');
  }

  public function nationality($tag_name = '') {
    $this->load->helper(array('nationality_helper', 'id_helper', 'img_helper', 'output_helper'));
    $this->load->view('site_templates/header');
    
    $data['tag_type'] = 'Nationality';
    if (!empty($tag_name)) {
      $data['tag_name'] = decode($tag_name);
      if ($data['tag_id'] = getNationalityID($data)) {
        $data['js_include'] = array('tag');
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? TRUE : FALSE;
        $data += getNationalityListenings($data);
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getNationalityListenings($data);
        }
        $data['group_by'] = TBL_listening . '.`user_id`';
        $data['listener_count'] = sizeof(json_decode(getMusicByNationality($data), true));

        $this->load->view('tag/tag_view', $data);
      }
      else {
        show_404();
      }
    }
    else {
      $this->load->view('tag/tags_view', $data);
    }
    $this->load->view('site_templates/footer');
  }
}
?>