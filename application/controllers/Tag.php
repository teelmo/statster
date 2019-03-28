<?php
class Tag extends CI_Controller {

  public function index($artist_name = '', $album_name = '') {
    $data = array();

    if (!empty($album_name)) {
      // Load helpers.
      $this->load->helper(array('img_helper', 'music_helper', 'album_helper', 'output_helper'));

      $data['artist_name'] = decode($artist_name);
      $data['album_name'] = decode($album_name);
      if ($data = getAlbumInfo($data)) {
        // Get albums's total listening data.
        $data += getAlbumListenings($data);
        // Get logged in user's listening data.
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getAlbumListenings($data);
        }
        $data['listener_count'] = sizeof(json_decode(getListeners($data), true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        $data['js_include'] = array('tag/tags_album', 'helpers/tag_helper');

        $this->load->view('site_templates/header');
        $this->load->view('tag/tags_album_view', $data);
        $this->load->view('site_templates/footer');
      }
      else {
        show_404();
      }
    }
    else if (!empty($artist_name)) {
      // Load helpers.
      $this->load->helper(array('img_helper', 'music_helper', 'artist_helper', 'output_helper'));

      $data['artist_name'] = decode($artist_name);
      if ($data = getArtistInfo($data)) {
        // Get artist's total listening data.
        $data += getArtistListenings($data);
        // Get logged in user's listening data.
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getArtistListenings($data);
        }
        $data['listener_count'] = sizeof(json_decode(getListeners($data), true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        $data['js_include'] = array('tag/tags_artist', 'helpers/tag_helper');

        $this->load->view('site_templates/header');
        $this->load->view('tag/tags_artist_view', $data);
        $this->load->view('site_templates/footer');
      }
      else {
        show_404();
      }
    }
    else {
      // Load helpers.
      $this->load->helper(array('genre_helper', 'keyword_helper', 'nationality_helper', 'music_helper', 'img_helper', 'year_helper', 'output_helper'));

      $opts = array(
        'human_readable' => false,
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31'
      );
      $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array();
      $data['limit'] = 1;
      $data['lower_limit'] = date('Y-m-d', time() - (180 * 24 * 60 * 60));
      $data['username'] = isset($_GET['u']) ? $_GET['u'] : '';
      $data['genre'] = json_decode(getGenres($data), true)[0];
      $data['keyword'] = json_decode(getKeywords($data), true)[0];
      $data['nationality'] = json_decode(getNationalities($data), true)[0];
      $data['year'] = json_decode(getYears($data), true)[0];
      $data['js_include'] = array('tag/tags');

      $this->load->view('site_templates/header');
      $this->load->view('tag/tags_view', $data);
      $this->load->view('site_templates/footer');
    }
  }

  public function genre($tag_name = '', $type = '') {
    // Load helpers.
    $this->load->helper(array('genre_helper', 'id_helper', 'img_helper', 'output_helper'));
    
    $this->load->view('site_templates/header');
    $data = array();
    $data['tag_type'] = 'genre';
    if (!empty($tag_name)) {
      $data['tag_name'] = decode($tag_name);
      if ($data['tag_id'] = getGenreID($data)) {
        if (!empty($type)) {
          $data['title'] = ucfirst($type) . 's';
          $data['side_title'] = 'Yearly';
          $data['type'] = $type;
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
          $data['username'] = isset($_GET['u']) ? $_GET['u'] : '';
          $data['group_by'] = TBL_artist . '.`id`';
          $data['artist'] = json_decode(getMusicByGenre($data), true)[0];
          $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';;
          if ($type === 'album') {
            $data['js_include'] = array('tag/tag_album');
            $this->load->view('tag/tag_album_view', $data);
          }
          else if ($type === 'artist') {
            $data['js_include'] = array('tag/tag_artist');
            $this->load->view('tag/tag_artist_view', $data);
          }
          else {
            show_404();
          }
        }
        else {
          $data += getGenreListenings($data);
          // Get biography.
          $data += getGenreBio($data);
          if (empty($data['bio_summary']) || empty($data['bio_content'])) {
            $this->load->helper(array('lastfm_helper'));
            unset($data['bio_summary']);
            unset($data['bio_content']);
            $data += fetchTagBio($data);
            addGenreBio($data);
          }
          else if ((time() - strtotime($data['bio_updated'])) > BIO_UPDATE_TIME) {
            $data['update_bio'] = true;
          }
          if ($data['user_id'] = $this->session->userdata('user_id')) {
            $data += getGenreListenings($data);
          }
          $data['lower_limit'] = '1970-01-01';
          $data['upper_limit'] = CUR_DATE;
          $data['limit'] = 100;
          $data['group_by'] = TBL_listening . '.`user_id`';
          $data['listener_count'] = sizeof(json_decode(getMusicByGenre($data), true));
          $data['limit'] = 1;
          $data['username'] = isset($_GET['u']) ? $_GET['u'] : '';
          $data['group_by'] = TBL_artist . '.`id`';
          $data['artist'] = json_decode(getMusicByGenre($data), true)[0];
          $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
          $data['js_include'] = array('tag/tag', 'libs/highcharts', 'libs/peity', 'helpers/chart_helper');

          $this->load->view('tag/tag_view', $data);
        }
      }
      else {
        show_404();
      }
    }
    else {
      // Load helpers.
      $this->load->helper(array('music_helper', 'img_helper', 'output_helper'));
      
      $opts = array(
        'human_readable' => false,
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31'
      );
      $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array();
      $data['js_include'] = array('tag/genres');

      $this->load->view('tag/genre_view', $data);
    }
    $this->load->view('site_templates/footer');
  }

  public function keyword($tag_name = '', $type = '') {
    // Load helpers.
    $this->load->helper(array('keyword_helper', 'id_helper', 'img_helper', 'output_helper'));

    $this->load->view('site_templates/header');
    $data = array();
    $data['tag_type'] = 'keyword';
    if (!empty($tag_name)) {
      $data['tag_name'] = decode($tag_name);
      if ($data['tag_id'] = getKeywordID($data)) {
        if (!empty($type)) {
          $data['title'] = ucfirst($type) . 's';
          $data['side_title'] = 'Yearly';
          $data['type'] = $type;
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
          $data['username'] = isset($_GET['u']) ? $_GET['u'] : '';
          $data['group_by'] = TBL_artist . '.`id`';
          $data['artist'] = json_decode(getMusicByKeyword($data), true)[0];
          $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';;
          if ($type === 'album') {
            $data['js_include'] = array('tag/tag_album');
            $this->load->view('tag/tag_album_view', $data);
          }
          else if ($type === 'artist') {
            $data['js_include'] = array('tag/tag_artist');
            $this->load->view('tag/tag_artist_view', $data);
          }
          else {
            show_404();
          }
        }
        else {
          $data += getKeywordListenings($data);
          // Get biography.
          $data += getKeywordBio($data);
          if (empty($data['bio_summary']) || empty($data['bio_content'])) {
            $this->load->helper(array('lastfm_helper'));
            unset($data['bio_summary']);
            unset($data['bio_content']);
            $data += fetchTagBio($data);
            addKeywordBio($data);
          }
          else if ((time() - strtotime($data['bio_updated'])) > BIO_UPDATE_TIME) {
            $data['update_bio'] = true;
          }
          if ($data['user_id'] = $this->session->userdata('user_id')) {
            $data += getKeywordListenings($data);
          }
          $data['lower_limit'] = '1970-01-01';
          $data['upper_limit'] = CUR_DATE;
          $data['limit'] = 100;
          $data['group_by'] = TBL_listening . '.`user_id`';
          $data['listener_count'] = sizeof(json_decode(getMusicByKeyword($data), true));
          $data['limit'] = 1;
          $data['username'] = isset($_GET['u']) ? $_GET['u'] : '';
          $data['group_by'] = TBL_artist . '.`id`';
          $data['artist'] = json_decode(getMusicByKeyword($data), true)[0];
          $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
          $data['js_include'] = array('tag/tag', 'libs/highcharts', 'libs/peity', 'helpers/chart_helper');

          $this->load->view('tag/tag_view', $data);
        }
      }
      else {
        show_404();
      }
    }
    else {
      // Load helpers.
      $this->load->helper(array('music_helper', 'img_helper', 'output_helper'));
      
      $opts = array(
        'human_readable' => false,
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31'
      );
      $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array();
      $data['js_include'] = array('tag/keywords');

      $this->load->view('tag/keyword_view', $data);
    }
    $this->load->view('site_templates/footer');
  }

  public function nationality($tag_name = '', $type = '') {
    $this->load->helper(array('nationality_helper', 'id_helper', 'img_helper', 'output_helper'));

    $this->load->view('site_templates/header');
    $data = array();
    $data['tag_type'] = 'nationality';
    if (!empty($tag_name)) {
      $data['tag_name'] = decode($tag_name);
      if ($data['tag_id'] = getNationalityID($data)) {
        if (!empty($type)) {
          $data['title'] = ucfirst($type) . 's';
          $data['side_title'] = 'Yearly';
          $data['type'] = $type;
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
          $data['username'] = isset($_GET['u']) ? $_GET['u'] : '';
          $data['group_by'] = TBL_artist . '.`id`';
          $data['artist'] = json_decode(getMusicByNationality($data), true)[0];
          $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';;
          if ($type === 'album') {
            $data['js_include'] = array('tag/tag_album');
            $this->load->view('tag/tag_album_view', $data);
          }
          else if ($type === 'artist') {
            $data['js_include'] = array('tag/tag_artist');
            $this->load->view('tag/tag_artist_view', $data);
          }
          else {
            show_404();
          }
        }
        else {
          $data += getNationalityListenings($data);
          // Get biography.
          $data += getNationalityBio($data);
          if (empty($data['bio_summary']) || empty($data['bio_content'])) {
            $this->load->helper(array('lastfm_helper'));
            unset($data['bio_summary']);
            unset($data['bio_content']);
            $data += fetchTagBio($data);
            addNationalityBio($data);
          }
          else if ((time() - strtotime($data['bio_updated'])) > BIO_UPDATE_TIME) {
            $data['update_bio'] = true;
          }
          if ($data['user_id'] = $this->session->userdata('user_id')) {
            $data += getNationalityListenings($data);
          }
          $data['lower_limit'] = '1970-01-01';
          $data['upper_limit'] = CUR_DATE;
          $data['limit'] = 100;
          $data['group_by'] = TBL_listening . '.`user_id`';
          $data['listener_count'] = sizeof(json_decode(getMusicByNationality($data), true));
          $data['limit'] = 1;
          $data['username'] = isset($_GET['u']) ? $_GET['u'] : '';
          $data['group_by'] = TBL_artist . '.`id`';
          $data['artist'] = json_decode(getMusicByNationality($data), true)[0];
          $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';;
          $data['js_include'] = array('tag/tag', 'libs/highcharts', 'libs/peity', 'helpers/chart_helper');

          $this->load->view('tag/tag_view', $data);
        }
      }
      else {
        show_404();
      }
    }
    else {
      // Load helpers.
      $this->load->helper(array('music_helper', 'img_helper', 'output_helper'));
      
      $opts = array(
        'human_readable' => false,
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31'
      );
      $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array();
      $data['js_include'] = array('tag/nationalities');
      
      $this->load->view('tag/nationality_view', $data);
    }
    $this->load->view('site_templates/footer');
  }

  public function year($tag_name = '', $type = '') {
    $this->load->helper(array('year_helper', 'img_helper', 'output_helper'));

    $this->load->view('site_templates/header');
    $data = array();
    $data['tag_type'] = 'year';
    if (!empty($tag_name)) {
      if (strlen($tag_name) === 4) {
        $data['tag_id'] = decode($tag_name);
        $data['tag_name'] = decode($tag_name);
        if (!empty($type)) {
          $data['title'] = ucfirst($type) . 's';
          $data['side_title'] = 'Yearly';
          $data['type'] = $type;
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
          $data['username'] = isset($_GET['u']) ? $_GET['u'] : '';
          $data['group_by'] = TBL_artist . '.`id`';
          $data['artist'] = json_decode(getMusicByYear($data), true)[0];
          $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';;
          if ($type === 'album') {
            $data['js_include'] = array('tag/tag_album');
            $this->load->view('tag/tag_album_view', $data);
          }
          else if ($type === 'artist') {
            $data['js_include'] = array('tag/tag_artist');
            $this->load->view('tag/tag_artist_view', $data);
          }
          else {
            show_404();
          }
        }
        else {
          $data += getYearListenings($data);
          // Get biography.
          $data += getYearBio($data);
          if (empty($data['bio_summary']) || empty($data['bio_content'])) {
            $this->load->helper(array('lastfm_helper'));
            unset($data['bio_summary']);
            unset($data['bio_content']);
            $data += fetchTagBio($data);
            addYearBio($data);
          }
          else if ((time() - strtotime($data['bio_updated'])) > BIO_UPDATE_TIME) {
            $data['update_bio'] = true;
          }
          if ($data['user_id'] = $this->session->userdata('user_id')) {
            $data += getYearListenings($data);
          }
          $data['lower_limit'] = '1970-01-01';
          $data['upper_limit'] = CUR_DATE;
          $data['limit'] = 100;
          $data['group_by'] = TBL_listening . '.`user_id`';
          $data['listener_count'] = sizeof(json_decode(getMusicByYear($data), true));
          $data['limit'] = 1;
          $data['username'] = isset($_GET['u']) ? $_GET['u'] : '';
          $data['group_by'] = TBL_artist . '.`id`';
          $data['artist'] = json_decode(getMusicByYear($data), true)[0];
          $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
          $data['js_include'] = array('tag/tag', 'libs/highcharts', 'libs/peity', 'helpers/chart_helper');

          $this->load->view('tag/tag_view', $data);
        }
      }
      else {
        show_404();
      }
    }
    else {
      // Load helpers.
      $this->load->helper(array('music_helper', 'img_helper', 'output_helper'));

      $opts = array(
        'human_readable' => false,
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31'
      );
      $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array();
      $data['js_include'] = array('tag/years', 'libs/highcharts', 'libs/peity', 'helpers/chart_helper');

      $this->load->view('tag/year_view', $data);
    }
    $this->load->view('site_templates/footer');
  }
}
?>
