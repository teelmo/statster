<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Tag extends MY_Controller {

  public function index($artist_name = '', $album_name = '') {
    $data = array();

    if (!empty($album_name)) {
      // Load helpers.
      $this->load->helper(array('img_helper', 'music_helper', 'album_helper', 'year_helper', 'output_helper'));

      $data['artist_name'] = decode($artist_name);
      $data['album_name'] = decode($album_name);
      if ($data = getAlbumInfo($data)) {
        $artists = array_map(function($artist) {
          return array('artist_id' => $artist['artist_id'],
                       'artist_name' => $artist['artist_name']);
        }, $data);
        $data = $data[0];
        usort($artists, function($artist_a, $artist_b) use ($artist_name) {
          ($artist_b['artist_name'] === decode($artist_name)) ? 1 : 0;
        });
        $data['artists'] = $artists;
        // Get albums's total listening data.
        $data += getAlbumListenings($data);
        // Get logged in user's listening data.
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getAlbumListenings($data);
        }

        $rank = 0;
        $data['most_listened_alltime'] = false;
        $last_item_count = false;
        foreach (json_decode(getAlbums(array('lower_limit' => '1970-00-00','limit' => 200))) as $item) {
          if ($item->count != $last_item_count) {
            $rank++;
          }
          if ($item->album_id == $data['album_id']) {
            $data['most_listened_alltime'] = $rank;
            break;
          }
          $last_item_count = $item->count;
        }
        if ($this->session->userdata('username')) {
          $rank = 0;
          $data['most_listened_alltime_user'] = false;
          $last_item_count = false;
          foreach (json_decode(getAlbums(array('lower_limit' => '1970-00-00','limit' => 200,'username' => $this->session->userdata('username')))) as $item) {
            if ($item->count != $last_item_count) {
              $rank++;
            }
            if ($item->album_id == $data['album_id']) {
              $data['most_listened_alltime_user'] = $rank;
              break;
            }
            $last_item_count = $item->count;
          }
        }
        $rank = 0;
        $data['most_listened_releaseyear'] = false;
        $last_item_count = false;
        foreach (json_decode(getMusicByYear(array('lower_limit' => '1970-00-00','limit' => 10,'tag_id' => $data['year'],'username' => (!empty($_GET['u']) ? $_GET['u'] : '')))) as $item) {
          if ($item->count != $last_item_count) {
            $rank++;
          }
          if ($item->album_id == $data['album_id']) {
            $data['most_listened_releaseyear'] = $rank;
            break;
          }
          $last_item_count = $item->count;
        }

        $data['listener_count'] = count(json_decode(getListeners($data) ?? '', true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        $data['js_include'] = array('tag/tags_album', 'helpers/tag_helper');

        $this->load->view('site_templates/header');
        $this->load->view('tag/tags_album_view', $data);
        $this->load->view('site_templates/footer', $data);
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

        $rank = 0;
        $data['most_listened_alltime'] = false;
        $last_item_count = false;
        foreach (json_decode(getArtists(array('lower_limit' => '1970-00-00','limit' => 200))) as $item) {
          if ($item->count != $last_item_count) {
            $rank++;
          }
          if ($item->artist_id == $data['artist_id']) {
            $data['most_listened_alltime'] = $rank;
            break;
          }
          $last_item_count = $item->count;
        }
        if ($this->session->userdata('username')) {
          $rank = 0;
          $data['most_listened_alltime_user'] = false;
          $last_item_count = false;
          foreach (json_decode(getArtists(array('lower_limit' => '1970-00-00','limit' => 200,'username' => $this->session->userdata('username')))) as $item) {
            if ($item->count != $last_item_count) {
              $rank++;
            }
            if ($item->artist_id == $data['artist_id']) {
              $data['most_listened_alltime_user'] = $rank;
              break;
            }
            $last_item_count = $item->count;
          }
        }

        $data['listener_count'] = count(json_decode(getListeners($data) ?? '', true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        $data['js_include'] = array('tag/tags_artist', 'helpers/tag_helper');

        $this->load->view('site_templates/header');
        $this->load->view('tag/tags_artist_view', $data);
        $this->load->view('site_templates/footer', $data);
      }
      else {
        show_404();
      }
    }
    else {
      // Load helpers.
      $this->load->helper(array('genre_helper', 'keyword_helper', 'nationality_helper', 'music_helper', 'img_helper', 'year_helper', 'output_helper'));

      $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
      $data['top_genre_tags'] = isset($intervals['top_genre_tags']) ? $intervals['top_genre_tags'] : 180;
      $data['top_keyword_tags'] = isset($intervals['top_keyword_tags']) ? $intervals['top_keyword_tags'] : 180;
      $data['top_nationality_tags'] = isset($intervals['top_nationality_tags']) ? $intervals['top_nationality_tags'] : 180;
      $data['top_year_tags'] = isset($intervals['top_year_tags']) ? $intervals['top_year_tags'] : 180;

      $opts = array(
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      $data['top_artist'] = (json_decode(getArtists($opts) ?? '', true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
      $data['username'] = isset($_GET['u']) ? $_GET['u'] : '';
      $data['js_include'] = array('tag/tags', 'helpers/time_interval_helper');

      $opts = array(
        'limit' => '1000',
        'lower_limit' => '1970-00-00'
      );
      $data['total_genres'] = count(json_decode(getGenres($opts)));
      $data['total_keywords'] = count(json_decode(getKeywords($opts)));
      $data['total_nationalities'] = count(json_decode(getNationalities($opts)));
      $data['total_years'] = count(json_decode(getYears($opts)));
      $opts = array(
        'limit' => '1000',
        'lower_limit' => '1970-00-00',
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      if ($this->session->userdata('logged_in') === TRUE) {
        $data['total_genres_user_count'] = count(json_decode(getGenres($opts)));
        $data['total_keywords_user_count'] = count(json_decode(getKeywords($opts)));
        $data['total_nationalities_user_count'] = count(json_decode(getNationalities($opts)));
        $data['total_years_user_count'] = count(json_decode(getYears($opts)));
      }

      $this->load->view('site_templates/header');
      $this->load->view('tag/tags_view', $data);
      $this->load->view('site_templates/footer', $data);
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
          $data['lower_limit'] = '1970-00-00';
          $data['upper_limit'] = CUR_DATE;
          $data['limit'] = 100;
          $data['group_by'] = TBL_listening . '.`user_id`';
          $data['no_content'] = FALSE;
          $data['listener_count'] = count(json_decode(getMusicByGenre($data) ?? '', true));
          $data['limit'] = 1;
          $data['username'] = isset($_GET['u']) ? $_GET['u'] : '';
          $data['group_by'] = TBL_artist . '.`id`';
          $data['artist'] = json_decode(getMusicByGenre($data) ?? '', true)[0];
          $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
          if ($type === 'album') {
            $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
            $data['top_album_' . $type . '_album'] = isset($intervals['top_album_' . $type . '_album']) ? $intervals['top_album_' . $type . '_album'] : 'overall';
            $data['top_album_tag_album'] = $data['top_album_' . $type . '_album'];
            $data['js_include'] = array('tag/tag_album', 'helpers/time_interval_helper');
            $this->load->view('tag/tag_album_view', $data);
          }
          else if ($type === 'artist') {
            $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
            $data['top_artist_' . $type . '_artist'] = isset($intervals['top_artist_' . $type . '_artist']) ? $intervals['top_artist_' . $type . '_artist'] : 'overall';
            $data['top_artist_tag_artist'] = $data['top_artist_' . $type . '_artist'];
            $data['js_include'] = array('tag/tag_artist', 'helpers/time_interval_helper');
            $this->load->view('tag/tag_artist_view', $data);
          }
          else {
            show_404();
          }
        }
        else {
          $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
          $data['top_album_tag_genre'] = isset($intervals['top_album_tag_genre']) ? $intervals['top_album_tag_genre'] : 'overall';
          $data['top_artist_tag_genre'] = isset($intervals['top_artist_tag_genre']) ? $intervals['top_artist_tag_genre'] : 'overall';
          $data['top_album_tag'] = $data['top_album_tag_genre'];
          $data['top_artist_tag'] = $data['top_artist_tag_genre'];
          $data += getGenreListenings($data);
          $data['related'] = json_decode(getRelatedGenres($data) ?? '', true);
          // Get biography.
          $data += getGenreBio($data);
          if (empty($data['bio_summary']) || empty($data['bio_content'])) {
            $this->load->helper(array('metadata_helper'));
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
          $data['lower_limit'] = '1970-00-00';
          $data['upper_limit'] = CUR_DATE;
          $data['limit'] = 100;
          $data['group_by'] = TBL_listening . '.`user_id`';
          $data['listener_count'] = count(json_decode(getMusicByGenre($data) ?? '', true));
          $data['limit'] = 1;
          $data['username'] = isset($_GET['u']) ? $_GET['u'] : '';
          $data['group_by'] = TBL_artist . '.`id`';
          $data['artist'] = json_decode(getMusicByGenre($data) ?? '', true)[0];
          $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
          $data['js_include'] = array('tag/tag', 'libs/highcharts', 'libs/peity', 'helpers/chart_helper', 'helpers/time_interval_helper');

          $this->load->view('tag/tag_view', $data);
        }
      }
      else {
        show_404();
      }
    }
    else {
      // Load helpers.
      $this->load->helper(array('genre_helper', 'music_helper', 'img_helper', 'output_helper'));
      
      $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
      $data['top_genre_genre'] = isset($intervals['top_genre_genre']) ? $intervals['top_genre_genre'] : 'overall';
      $opts = array(
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      $data['top_artist'] = (json_decode(getArtists($opts) ?? '', true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
      $opts = array(
        'limit' => '1000',
        'lower_limit' => '1970-00-00',
        'username' => (!empty($_GET['u'] && ($this->session->userdata('username') !== $_GET['u'])) ? $_GET['u'] : '')
      );
      $data['total_count'] = count(json_decode(getGenres($opts)));
      if ($this->session->userdata('logged_in') === TRUE) {
        $opts['username'] = $this->session->userdata('username');
        $data['user_count'] = count(json_decode(getGenres($opts)));
      }
      $data['js_include'] = array('tag/genres', 'helpers/time_interval_helper');

      $this->load->view('tag/genre_view', $data);
    }
    $this->load->view('site_templates/footer', $data);
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
          $data['lower_limit'] = '1970-00-00';
          $data['upper_limit'] = CUR_DATE;
          $data['limit'] = 100;
          $data['group_by'] = TBL_listening . '.`user_id`';
          $data['no_content'] = FALSE;
          $data['listener_count'] = count(json_decode(getMusicByKeyword($data) ?? '', true));
          $data['limit'] = 1;
          $data['username'] = isset($_GET['u']) ? $_GET['u'] : '';
          $data['group_by'] = TBL_artist . '.`id`';
          $data['artist'] = json_decode(getMusicByKeyword($data) ?? '', true)[0];
          $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
          if ($type === 'album') {
            $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
            $data['top_album_' . $type . '_album'] = isset($intervals['top_album_' . $type . '_album']) ? $intervals['top_album_' . $type . '_album'] : 'overall';
            $data['top_album_tag_album'] = $data['top_album_' . $type . '_album'];
            $data['js_include'] = array('tag/tag_album', 'helpers/time_interval_helper');
            $this->load->view('tag/tag_album_view', $data);
          }
          else if ($type === 'artist') {
            $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
            $data['top_artist_' . $type . '_artist'] = isset($intervals['top_artist_' . $type . '_artist']) ? $intervals['top_artist_' . $type . '_artist'] : 'overall';
            $data['top_artist_tag_artist'] = $data['top_artist_' . $type . '_artist'];
            $data['js_include'] = array('tag/tag_artist', 'helpers/time_interval_helper');
            $this->load->view('tag/tag_artist_view', $data);
          }
          else {
            show_404();
          }
        }
        else {
          $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
          $data['top_album_tag_keyword'] = isset($intervals['top_album_tag_keyword']) ? $intervals['top_album_tag_keyword'] : 'overall';
          $data['top_artist_tag_keyword'] = isset($intervals['top_artist_tag_keyword']) ? $intervals['top_artist_tag_keyword'] : 'overall';
          $data['top_album_tag'] = $data['top_album_tag_keyword'];
          $data['top_artist_tag'] = $data['top_artist_tag_keyword'];
          $data += getKeywordListenings($data);
          $data['related'] = json_decode(getRelatedKeywords($data) ?? '', true);
          // Get biography.
          $data += getKeywordBio($data);
          if (empty($data['bio_summary']) || empty($data['bio_content'])) {
            $this->load->helper(array('metadata_helper'));
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
          $data['lower_limit'] = '1970-00-00';
          $data['upper_limit'] = CUR_DATE;
          $data['limit'] = 100;
          $data['group_by'] = TBL_listening . '.`user_id`';
          $data['no_content'] = FALSE;
          $data['listener_count'] = count(json_decode(getMusicByKeyword($data) ?? '', true) ?? array());
          $data['limit'] = 1;
          $data['username'] = isset($_GET['u']) ? $_GET['u'] : '';
          $data['group_by'] = TBL_artist . '.`id`';
          $data['artist'] = json_decode(getMusicByKeyword($data) ?? '', true)[0] ?? array('artist_id' => 0);
          $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
          $data['js_include'] = array('tag/tag', 'libs/highcharts', 'libs/peity', 'helpers/chart_helper', 'helpers/time_interval_helper');

          $this->load->view('tag/tag_view', $data);
        }
      }
      else {
        show_404();
      }
    }
    else {
      // Load helpers.
      $this->load->helper(array('keyword_helper', 'music_helper', 'img_helper', 'output_helper'));
      
      $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
      $data['top_keyword_keyword'] = isset($intervals['top_keyword_keyword']) ? $intervals['top_keyword_keyword'] : 'overall';
      $opts = array(
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31'
      );
      $data['top_artist'] = (json_decode(getArtists($opts) ?? '', true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
      $opts = array(
        'limit' => '1000',
        'lower_limit' => '1970-00-00',
        'username' => (!empty($_GET['u'] && ($this->session->userdata('username') !== $_GET['u'])) ? $_GET['u'] : '')
      );
      $data['total_count'] = count(json_decode(getKeywords($opts)));
      if ($this->session->userdata('logged_in') === TRUE) {
        $opts['username'] = $this->session->userdata('username');
        $data['user_count'] = count(json_decode(getKeywords($opts)));
      }
      $data['js_include'] = array('tag/keywords', 'helpers/time_interval_helper');

      $this->load->view('tag/keyword_view', $data);
    }
    $this->load->view('site_templates/footer', $data);
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
          $data['lower_limit'] = '1970-00-00';
          $data['upper_limit'] = CUR_DATE;
          $data['limit'] = 100;
          $data['group_by'] = TBL_listening . '.`user_id`';
          $data['no_content'] = FALSE;
          $data['listener_count'] = count(json_decode(getMusicByNationality($data) ?? '', true));
          $data['limit'] = 1;
          $data['username'] = isset($_GET['u']) ? $_GET['u'] : '';
          $data['group_by'] = TBL_artist . '.`id`';
          $data['artist'] = json_decode(getMusicByNationality($data) ?? '', true)[0];
          $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';;
          if ($type === 'album') {
            $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
            $data['top_album_' . $type . '_album'] = isset($intervals['top_album_' . $type . '_album']) ? $intervals['top_album_' . $type . '_album'] : 'overall';
            $data['top_album_tag_album'] = $data['top_album_' . $type . '_album'];
            $data['js_include'] = array('tag/tag_album', 'helpers/time_interval_helper');
            $this->load->view('tag/tag_album_view', $data);
          }
          else if ($type === 'artist') {
            $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
            $data['top_artist_' . $type . '_artist'] = isset($intervals['top_artist_' . $type . '_artist']) ? $intervals['top_artist_' . $type . '_artist'] : 'overall';
            $data['top_artist_tag_artist'] = $data['top_artist_' . $type . '_artist'];
            $data['js_include'] = array('tag/tag_artist', 'helpers/time_interval_helper');
            $this->load->view('tag/tag_artist_view', $data);
          }
          else {
            show_404();
          }
        }
        else {
          $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
          $data['top_album_tag_nationality'] = isset($intervals['top_album_tag_nationality']) ? $intervals['top_album_tag_nationality'] : 'overall';
          $data['top_artist_tag_nationality'] = isset($intervals['top_artist_tag_nationality']) ? $intervals['top_artist_tag_nationality'] : 'overall';
          $data['top_album_tag'] = $data['top_album_tag_nationality'];
          $data['top_artist_tag'] = $data['top_artist_tag_nationality'];
          $data += getNationalityListenings($data);
          $data['related'] = json_decode(getRelatedNationalities($data) ?? '', true);
          // Get biography.
          $data += getNationalityBio($data);
          if (empty($data['bio_summary']) || empty($data['bio_content'])) {
            $this->load->helper(array('metadata_helper'));
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
          $data['lower_limit'] = '1970-00-00';
          $data['upper_limit'] = CUR_DATE;
          $data['limit'] = 100;
          $data['group_by'] = TBL_listening . '.`user_id`';
          $data['no_content'] = FALSE;
          $data['listener_count'] = count(json_decode(getMusicByNationality($data) ?? '', true));
          $data['limit'] = 1;
          $data['username'] = isset($_GET['u']) ? $_GET['u'] : '';
          $data['group_by'] = TBL_artist . '.`id`';
          $data['artist'] = json_decode(getMusicByNationality($data) ?? '', true)[0];
          $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
          $data['js_include'] = array('tag/tag', 'libs/highcharts', 'libs/peity', 'helpers/chart_helper', 'helpers/time_interval_helper');

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
      
      $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
      $data['top_nationality_nationality'] = isset($intervals['top_nationality_nationality']) ? $intervals['top_nationality_nationality'] : 'overall';
      $opts = array(
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      $data['top_artist'] = (json_decode(getArtists($opts) ?? '', true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
      $opts = array(
        'limit' => '1000',
        'lower_limit' => '1970-00-00',
        'username' => (!empty($_GET['u'] && ($this->session->userdata('username') !== $_GET['u'])) ? $_GET['u'] : '')
      );
      $data['total_count'] = count(json_decode(getNationalities($opts)));
      if ($this->session->userdata('logged_in') === TRUE) {
        $opts['username'] = $this->session->userdata('username');
        $data['user_count'] = count(json_decode(getNationalities($opts)));
      }
      $data['js_include'] = array('tag/nationalities', 'helpers/time_interval_helper');
      
      $this->load->view('tag/nationality_view', $data);
    }
    $this->load->view('site_templates/footer', $data);
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
          $data['lower_limit'] = '1970-00-00';
          $data['upper_limit'] = CUR_DATE;
          $data['limit'] = 100;
          $data['group_by'] = TBL_listening . '.`user_id`';
          $data['no_content'] = FALSE;
          $data['listener_count'] = count(json_decode(getMusicByYear($data) ?? '', true));
          $data['limit'] = 1;
          $data['username'] = isset($_GET['u']) ? $_GET['u'] : '';
          $data['group_by'] = TBL_artist . '.`id`';
          $data['artist'] = json_decode(getMusicByYear($data) ?? '', true)[0];
          $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
          if ($type === 'album') {
            $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
            $data['top_album_' . $type . '_album'] = isset($intervals['top_album_' . $type . '_album']) ? $intervals['top_album_' . $type . '_album'] : 'overall';
            $data['top_album_tag_album'] = $data['top_album_' . $type . '_album'];
            $data['js_include'] = array('tag/tag_album', 'helpers/time_interval_helper');
            $this->load->view('tag/tag_album_view', $data);
          }
          else if ($type === 'artist') {
            $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
            $data['top_artist_' . $type . '_artist'] = isset($intervals['top_artist_' . $type . '_artist']) ? $intervals['top_artist_' . $type . '_artist'] : 'overall';
            $data['top_artist_tag_artist'] = $data['top_artist_' . $type . '_artist'];
            $data['js_include'] = array('tag/tag_artist', 'helpers/time_interval_helper');
            $this->load->view('tag/tag_artist_view', $data);
          }
          else {
            show_404();
          }
        }
        else {
          $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
          $data['top_album_tag_year'] = isset($intervals['top_album_tag_year']) ? $intervals['top_album_tag_year'] : 'overall';
          $data['top_artist_tag_year'] = isset($intervals['top_artist_tag_year']) ? $intervals['top_artist_tag_year'] : 'overall';
          $data['top_album_tag'] = $data['top_album_tag_year'];
          $data['top_artist_tag'] = $data['top_artist_tag_year'];
          $data += getYearListenings($data);
          $data['related'] = json_decode(getRelatedYears($data) ?? '', true);
          // Get biography.
          $data += getYearBio($data);
          if (empty($data['bio_summary']) || empty($data['bio_content'])) {
            $this->load->helper(array('metadata_helper'));
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
          $data['lower_limit'] = '1970-00-00';
          $data['upper_limit'] = CUR_DATE;
          $data['limit'] = 100;
          $data['group_by'] = TBL_listening . '.`user_id`';
          $data['no_content'] = FALSE;
          $data['listener_count'] = count(json_decode(getMusicByYear($data) ?? '', true));
          $data['limit'] = 1;
          $data['username'] = isset($_GET['u']) ? $_GET['u'] : '';
          $data['group_by'] = TBL_artist . '.`id`';
          $data['artist'] = json_decode(getMusicByYear($data) ?? '', true)[0];
          $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
          $data['js_include'] = array('tag/tag', 'libs/highcharts', 'libs/peity', 'helpers/chart_helper', 'helpers/time_interval_helper');

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

      $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
      $data['top_year_year'] = isset($intervals['top_year_year']) ? $intervals['top_year_year'] : 'overall';
      $opts = array(
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      $data['top_artist'] = (json_decode(getArtists($opts) ?? '', true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
      $opts = array(
        'limit' => '1000',
        'lower_limit' => '1970-00-00',
        'username' => (!empty($_GET['u']) && ($this->session->userdata('username') !== $_GET['u']) ? $_GET['u'] : '')
      );
      $data['total_count'] = count(json_decode(getYears($opts)));
      if ($this->session->userdata('logged_in') === TRUE) {
        $opts['username'] = $this->session->userdata('username');
        $data['user_count'] = count(json_decode(getYears($opts)));
      }
      $data['js_include'] = array('tag/years', 'libs/highcharts', 'helpers/chart_helper', 'helpers/time_interval_helper');

      $this->load->view('tag/year_view', $data);
    }
    $this->load->view('site_templates/footer', $data);
  }
}
?>
