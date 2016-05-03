<?php
class Tag extends CI_Controller {

  public function index($artist_name = '', $album_name = '') {
    if (!empty($album_name)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'music_helper', 'album_helper', 'output_helper'));

      $data['artist_name'] = decode($artist_name);
      $data['album_name'] = decode($album_name);
      if ($data = getAlbumInfo($data)) {
        // Get albums's total listening data
        $data += getAlbumListenings($data);
        // Get logged in user's listening data
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getAlbumListenings($data);
        }
        $data['listener_count'] = sizeof(json_decode(getListeners($data), true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? TRUE : FALSE;
        
        $data['js_include'] = array('meta_album');
        $this->load->view('site_templates/header');
        $this->load->view('tag/meta_album', $data);
        $this->load->view('site_templates/footer', $data);
      }
      else {
        show_404();
      }
    }
    else if (!empty($artist_name)) {
      // Load helpers
      $this->load->helper(array('img_helper', 'music_helper', 'artist_helper', 'output_helper'));

      $data['artist_name'] = decode($artist_name);
      if ($data = getArtistInfo($data)) {
        // Get artist's total listening data
        $data += getArtistListenings($data);
        // Get logged in user's listening data
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getArtistListenings($data);
        }
        $data['listener_count'] = sizeof(json_decode(getListeners($data), true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? TRUE : FALSE;

        $data['js_include'] = array('meta_artist');
        $this->load->view('site_templates/header');
        $this->load->view('tag/meta_artist', $data);
        $this->load->view('site_templates/footer', $data);
      }
      else {
        show_404();
      }
    }
    else {
      // Load helpers
      $this->load->helper(array('genre_helper', 'keyword_helper', 'nationality_helper', 'year_helper', 'output_helper'));

      $data['limit'] = 1;
      $data['lower_limit'] = date('Y-m-d', time() - (180 * 24 * 60 * 60));
      $data['username'] = $_GET['u'];
      $data['genre'] = ${!${false}=json_decode(getGenres($data), true)}[0];
      $data['keyword'] = ${!${false}=json_decode(getKeywords($data), true)}[0];
      $data['nationality'] = ${!${false}=json_decode(getNationalities($data), true)}[0];
      $data['year'] = ${!${false}=json_decode(getYears($data), true)}[0];

      $data['js_include'] = array('meta');
      $this->load->view('site_templates/header');
      $this->load->view('tag/meta_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
  }

  public function genre($tag_name = '') {
    // Load helpers
    $this->load->helper(array('genre_helper', 'id_helper', 'img_helper', 'output_helper'));
    $this->load->view('site_templates/header');

    $data['tag_type'] = 'genre';
    if (!empty($tag_name)) {
      $data['tag_name'] = decode($tag_name);
      if ($data['tag_id'] = getGenreID($data)) {
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? TRUE : FALSE;
        $data += getGenreListenings($data);
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getGenreListenings($data);
        }
        $data['lower_limit'] = '1970-01-01';
        $data['upper_limit'] = CUR_DATE;
        $data['limit'] = 100;
        $data['group_by'] = TBL_listening . '.`user_id`';
        $data['listener_count'] = sizeof(json_decode(getMusicByGenre($data), true));
        $data['limit'] = 1;
        $data['username'] = $_GET['u'];
        $data['group_by'] = TBL_artist . '.`id`';
        $data['artist'] = ${!${false}=json_decode(getMusicByGenre($data), true)}[0];
        if (!empty($type)) {
          $data['type'] = $type;
          $data['hide'] = ($type == 'artist') ? 'album:true' : 'artist:true';
          $data['js_include'] = array('tags');
          $data['title'] = ucfirst($type) . 's';
          $this->load->view('tag/tags_view', $data);
        }
        else {
          $data['js_include'] = array('tag', 'helpers/chart_helper');
          $this->load->view('tag/tag_view', $data);
        }
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
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? TRUE : FALSE;
        $data += getKeywordListenings($data);
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getKeywordListenings($data);
        }
        $data['lower_limit'] = '1970-01-01';
        $data['upper_limit'] = CUR_DATE;
        $data['limit'] = 100;
        $data['group_by'] = TBL_listening . '.`user_id`';
        $data['listener_count'] = sizeof(json_decode(getMusicByKeyword($data), true));
        $data['limit'] = 1;
        $data['username'] = $_GET['u'];
        $data['group_by'] = TBL_artist . '.`id`';
        $data['artist'] = ${!${false}=json_decode(getMusicByKeyword($data), true)}[0];
        if (!empty($type)) {
          $data['type'] = $type;
          $data['hide'] = ($type == 'artist') ? 'album:true' : 'artist:true';
          $data['js_include'] = array('tags');
          $data['title'] = ucfirst($type) . 's';
          $this->load->view('tag/tags_view', $data);
        }
        else {
          $data['js_include'] = array('tag', 'helpers/chart_helper');
          $this->load->view('tag/tag_view', $data);
        }
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
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? TRUE : FALSE;
        $data += getNationalityListenings($data);
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getNationalityListenings($data);
        }
        $data['lower_limit'] = '1970-01-01';
        $data['upper_limit'] = CUR_DATE;
        $data['limit'] = 100;
        $data['group_by'] = TBL_listening . '.`user_id`';
        $data['listener_count'] = sizeof(json_decode(getMusicByNationality($data), true));
        $data['limit'] = 1;
        $data['username'] = $_GET['u'];
        $data['group_by'] = TBL_artist . '.`id`';
        $data['artist'] = ${!${false}=json_decode(getMusicByNationality($data), true)}[0];
        if (!empty($type)) {
          $data['type'] = $type;
          $data['hide'] = ($type == 'artist') ? 'album:true' : 'artist:true';
          $data['js_include'] = array('tags');
          $data['title'] = ucfirst($type) . 's';
          $this->load->view('tag/tags_view', $data);
        }
        else {
          $data['js_include'] = array('tag', 'helpers/chart_helper');
          $this->load->view('tag/tag_view', $data);
        }
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
      $data['lower_limit'] = '1970-01-01';
      $data['upper_limit'] = CUR_DATE;
      $data['limit'] = 100;
      $data['group_by'] = TBL_listening . '.`user_id`';
      $data['listener_count'] = sizeof(json_decode(getMusicByYear($data), true));
      $data['limit'] = 1;
      $data['username'] = $_GET['u'];
      $data['group_by'] = TBL_artist . '.`id`';
      $data['artist'] = ${!${false}=json_decode(getMusicByYear($data), true)}[0];
      if (!empty($type)) {
        $data['type'] = $type;
        $data['hide'] = ($type == 'artist') ? 'album:true' : 'artist:true';
        $data['js_include'] = array('tags');
        $data['title'] = ucfirst($type) . 's';
        $this->load->view('tag/tags_view', $data);
      }
      else {
        $data['js_include'] = array('tag', 'helpers/chart_helper');
        $this->load->view('tag/tag_view', $data);
      }
    }
    else {
      $data['js_include'] = array('years', 'helpers/chart_helper');
      $this->load->view('tag/year_view', $data);
    }
    $this->load->view('site_templates/footer');
  }
}
?>
