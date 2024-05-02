<?php
class Music extends CI_Controller {

  public function index() {
    $data = array();
    if (isset($_GET['from']) || isset($_GET['to']) || isset($_GET['month']) || isset($_GET['day']) || isset($_GET['weekday'])) {
      // Load helpers.
      $this->load->helper(array('img_helper', 'id_helper', 'music_helper', 'shout_helper', 'fan_helper', 'love_helper', 'spotify_helper', 'output_helper'));
      $data['from'] = isset($_GET['from']) ? $_GET['from'] : '1970-00-00';
      $data['to'] = isset($_GET['to']) ? $_GET['to'] : CUR_DATE;
      $data['from_array'] = explode('-', $data['from']);
      $data['to_array'] = explode('-', $data['to']);
      $data['month'] = isset($_GET['month']) ? $_GET['month'] : '\'%\'' ;
      $data['day'] = isset($_GET['day']) ? $_GET['day'] : '\'%\'' ;
      $data['weekday'] = isset($_GET['weekday']) ? $_GET['weekday'] - 1 : '\'%\'' ;
      $data += array(
        'limit' => '1',
        'lower_limit' => $data['from'],
        'upper_limit' => $data['to'],
        'username' => (!empty($_GET['u']) ? $_GET['u'] : ''),
        'where' => 'MONTH(' . TBL_listening . '.`date`) LIKE ' . $data['month'] . ' AND DAY(' . TBL_listening . '.`date`) LIKE ' . $data['day'] . ' AND WEEKDAY(' . TBL_listening . '.`date`) LIKE ' . $data['weekday']
      );
      $data['artist_count'] = getListeningCount($data, TBL_artist);
      $data['album_count'] = getListeningCount($data, TBL_album);
      $data['listening_count'] = getListeningCount($data, TBL_listening);
      $data['top_artist'] = (json_decode(getArtists($data), true) !== NULL) ? json_decode(getArtists($data), true)[0] : array('artist_id' => 0);
      $data['top_album'] = (json_decode(getAlbums($data), true) !== NULL) ? json_decode(getAlbums($data), true)[0] : array();
      $data['where'] .= ' AND ' . TBL_artist . '.`created` BETWEEN  \'' . $data['from'] . '\' AND \'' . $data['to'] . '\'';
      $data['new_artist_count'] = getListeningCount($data, TBL_artist);
      $data['where'] .= ' AND ' . TBL_album . '.`created` BETWEEN  \'' . $data['from'] . '\' AND \'' . $data['to'] . '\'';
      $data['new_album_count'] = getListeningCount($data, TBL_album);
      $data['user_id'] = (!empty($data['username']) ? getUserID($data) : '');
      $data['where'] = 'MONTH(' . TBL_fan . '.`created`) LIKE ' .  $data['month'] . ' AND DAY(' . TBL_fan . '.`created`) LIKE ' . $data['day'] . ' AND WEEKDAY(' . TBL_fan . '.`created`) LIKE ' . $data['weekday'];
      $data['fan_count'] = getFanCount($data);
      $data['where'] = 'MONTH(' . TBL_love . '.`created`) LIKE ' .  $data['month'] . ' AND DAY(' . TBL_love . '.`created`) LIKE ' . $data['day'] . ' AND WEEKDAY(' . TBL_love . '.`created`) LIKE ' . $data['weekday'];
      $data['love_count'] = getLoveCount($data);
      $data['where'] = 'MONTH(`shouts`.`created`) LIKE ' .  $data['month'] . ' AND DAY(`shouts`.`created`) LIKE ' . $data['day'] . ' AND WEEKDAY(`shouts`.`created`) LIKE ' . $data['weekday'];
      $data['shout_count'] = getShoutCount($data);
      $data['album_average_age'] = (json_decode(getAlbumAverageAge($data), true) !== NULL) ? json_decode(getAlbumAverageAge($data), true)[0] : array('album_average_age' => 0);
      $data['js_include'] = array('music/library', 'libs/jquery.daterangepicker', 'libs/highcharts', 'helpers/chart_helper', 'helpers/date_filter_helper');

      $this->load->view('site_templates/header');
      $this->load->view('music/library_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
    else {
      // Load helpers.
      $this->load->helper(array('img_helper', 'music_helper', 'genre_helper', 'shout_helper', 'fan_helper', 'love_helper', 'nationality_helper', 'year_helper', 'id_helper', 'output_helper'));

      $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
      $data['popular_album_music'] = isset($intervals['popular_album_music']) ? $intervals['popular_album_music'] : 90;
      $opts = array(
        'lower_limit' => '1970-00-00',
        'upper_limit' => CUR_DATE,
        'limit' => '1',
        'user_id' => (!empty($_GET['u']) ? getUserID(array('username' => $_GET['u'])) : ''),
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      $data['artist_count'] = getListeningCount($opts, TBL_artist);
      $data['album_count'] = getListeningCount($opts, TBL_album);
      $data['listening_count'] = getListeningCount($opts, TBL_listening);
      $data['shout_count'] = getShoutCount($opts);
      $data['fan_count'] = getFanCount($opts);
      $data['love_count'] = getLoveCount($opts);

      $opts = array(
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      $data['top_album'] = (json_decode(getAlbums($opts), true) !== NULL) ? json_decode(getAlbums($opts), true)[0] : array();
      $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
      $data['top_genre'] = (json_decode(getGenres($opts), true) !== NULL) ? json_decode(getGenres($opts), true)[0] : array();
      $data['top_nationality'] = (json_decode(getNationalities($opts), true) !== NULL) ? json_decode(getNationalities($opts), true)[0] : array();
      $data['top_year'] = (json_decode(getYears($opts), true) !== NULL) ? json_decode(getYears($opts), true)[0] : array();
      $data['album_average_age'] = (json_decode(getAlbumAverageAge($data), true) !== NULL) ? json_decode(getAlbumAverageAge($data), true)[0] : array('album_average_age' => 0);
      $data['js_include'] = array('music/music', 'libs/jquery.daterangepicker', 'libs/highcharts', 'libs/peity', 'helpers/time_interval_helper', 'helpers/chart_helper', 'helpers/date_filter_helper');

      $this->load->view('site_templates/header');
      $this->load->view('music/music_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
  }

  public function artist_or_year($value) {
    $data = array();
    if ((int) $value > 1900 && (int) $value <= (CUR_YEAR + 1)) {
      // Load helpers.
      $this->load->helper(array('img_helper', 'music_helper', 'shout_helper', 'fan_helper', 'love_helper', 'spotify_helper', 'output_helper'));
      $data['year'] = $value;
      $data += array(
        'limit' => '1',
        'lower_limit' => $data['year'] . '-00-00',
        'upper_limit' => $data['year'] . '-12-31',
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      $data['artist_count'] = getListeningCount($data, TBL_artist);
      $data['album_count'] = getListeningCount($data, TBL_album);
      $data['listening_count'] = getListeningCount($data, TBL_listening);
      $data['top_artist'] = (json_decode(getArtists($data), true) !== NULL) ? json_decode(getArtists($data), true)[0] : array('artist_id' => 0);
      $data['top_album'] = (json_decode(getAlbums($data), true) !== NULL) ? json_decode(getAlbums($data), true)[0] : array();
      $data['where'] = TBL_artist . '.`created` LIKE \'' . $data['year'] . '%\'';
      $data['new_artist_count'] = getListeningCount($data, TBL_artist);
      $data['where'] = TBL_album . '.`created` LIKE \'' . $data['year'] . '%\'';
      $data['new_album_count'] = getListeningCount($data, TBL_album);
      $data['where'] = '';
      $data['fan_count'] = getFanCount($data);
      $data['love_count'] = getLoveCount($data);
      $data['shout_count'] = getShoutCount($data);
      $data['album_average_age'] = (json_decode(getAlbumAverageAge($data), true) !== NULL) ? json_decode(getAlbumAverageAge($data), true)[0] : array('album_average_age' => 0);
      $data['js_include'] = array('music/year', 'libs/highcharts', 'helpers/chart_helper');

      $this->load->view('site_templates/header');
      $this->load->view('music/year_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
    else {
      // Load helpers.
      $this->load->helper(array('img_helper', 'music_helper', 'spotify_helper', 'artist_helper', 'output_helper'));

      // Decode artist information.
      $data['artist_name'] = decode($value);

      // Get artist information aka. artist's name and id.
      if ($data = getArtistInfo($data)) {
        $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
        $data['artist_album'] = isset($intervals['artist_album']) ? $intervals['artist_album'] : '`count` DESC, `albums`.`year` DESC';
        // Get artist's total listening data.
        $data += getArtistListenings($data);
        // Get biography.
        $data += getArtistBio($data);
        if (empty($data['bio_summary']) || empty($data['bio_content'])) {
          $this->load->helper(array('metadata_helper'));
          unset($data['bio_summary']);
          unset($data['bio_content']);
          $data += fetchArtistInfo($data, array('bio', 'image'));
          addArtistBio($data);
        }
        else if ((time() - strtotime($data['bio_updated'])) > BIO_UPDATE_TIME) {
          $data['update_bio'] = true;
        }
        // Get logged in user's listening data.
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getArtistListenings($data);
        }
        $data['listener_count'] = (getListeners($data) !== NULL) ? count(json_decode(getListeners($data), true)) : 0;

        if (empty($data['spotify_id'])) {
          $data['spotify_id'] = getSpotifyResourceId($data);
        }

        $rank = 0;
        $data['most_listened_alltime'] = false;
        $last_item_count = false;
        foreach (json_decode(getArtists(array('lower_limit' => '1970-00-00', 'limit' => 200))) as $item) {
          $rank++;
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
          foreach (json_decode(getArtists(array('lower_limit' => '1970-00-00', 'limit' => 200, 'username' => $this->session->userdata('username')))) as $item) {
            $rank++;
            if ($item->artist_id == $data['artist_id']) {
              $data['most_listened_alltime_user'] = $rank;
              break;
            }
            $last_item_count = $item->count;
          }
        }
        $data['sub_group_by'] = TBL_artists . '.`album_id`';
        $data['per_year_user'] = (getListeningsPerYear($data) !== NULL) ? json_decode(getListeningsPerYear($data), true)[0]['count'] : 0;
        unset($data['user_id']);
        $data['per_year'] = (getListeningsPerYear($data) !== NULL) ? json_decode(getListeningsPerYear($data), true)[0]['count'] : 0;

        $data += $_REQUEST;
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        $data['js_include'] = array('music/artist', 'music/lastfm', 'helpers/artist_album_helper', 'helpers/tag_helper', 'libs/highcharts', 'libs/peity', 'helpers/chart_helper', 'helpers/shout_helper', 'helpers/time_interval_helper', 'helpers/per_year_helper');

        $this->load->view('site_templates/header');
        $this->load->view('music/artist_view', $data);
        $this->load->view('site_templates/footer', $data);
      }
      else {
        show_404();
      }
    }
  }

  public function album_or_month($value1, $value2) {
    $data = array();
    if ((int) $value1 > 1900 && (int) $value1 <= CUR_YEAR && (int) $value2 > 0 && (int) $value2 <= 12) {
      // Load helpers.
      $this->load->helper(array('img_helper', 'music_helper', 'shout_helper', 'fan_helper', 'love_helper', 'spotify_helper', 'output_helper'));
      $data['year'] = $value1;
      $data['month'] = $value2;
      $data += array(
        'lower_limit' => $data['year'] . '-' . $data['month'] . '-00',
        'upper_limit' => $data['year'] . '-' . $data['month'] . '-31',
        'limit' => '1',
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      $data['artist_count'] = getListeningCount($data, TBL_artist);
      $data['album_count'] = getListeningCount($data, TBL_album);
      $data['listening_count'] = getListeningCount($data, TBL_listening);
      $data['top_artist'] = (json_decode(getArtists($data), true) !== NULL) ? json_decode(getArtists($data), true)[0] : array('artist_id' => 0);
      $data['top_album'] = (json_decode(getAlbums($data), true) !== NULL) ? json_decode(getAlbums($data), true)[0] : array();
      $data['where'] = TBL_artist . '.`created` LIKE \'' . $data['year'] . '%\'';
      $data['new_artist_count'] = getListeningCount($data, TBL_artist);
      $data['where'] = TBL_album . '.`created` LIKE \'' . $data['year'] . '%\'';
      $data['new_album_count'] = getListeningCount($data, TBL_album);
      $data['where'] = '';
      $data['fan_count'] = getFanCount($data);
      $data['love_count'] = getLoveCount($data);
      $data['shout_count'] = getShoutCount($data);
      $data['js_include'] = array('music/month', 'libs/highcharts', 'helpers/chart_helper');

      $this->load->view('site_templates/header');
      $this->load->view('music/month_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
    else {
      // Load helpers.
      $this->load->helper(array('img_helper', 'music_helper', 'spotify_helper', 'artist_helper', 'album_helper', 'nationality_helper', 'year_helper', 'output_helper'));

      $data['artist_name'] = decode($value1);
      $data['album_name'] = decode($value2);
      
      // Get album information aka. album's name and id.
      if ($data = getAlbumInfo($data)) {
        $artists = array_map(function($artist) {
          return array('artist_id' => $artist['artist_id'],
                       'artist_name' => $artist['artist_name']);
        }, $data);
        $data = $data[0];
        usort($artists, function($artist_a, $artist_b) use ($value1) {
          ($artist_b['artist_name'] === decode($value1)) ? 1 : 0;
        });
        $data['artists'] = $artists;
        $intervals = $this->session->userdata('intervals') ? unserialize($this->session->userdata('intervals')) : [];
        $data['artist_album'] = isset($intervals['artist_album']) ? $intervals['artist_album'] : '`count` DESC, `albums`.`year` DESC';
        // Get albums's total listening data.
        $data += getAlbumListenings($data);
        // Get biography.
        $data += getAlbumBio($data);
        if (empty($data['bio_summary']) || empty($data['bio_content'])) {
          $this->load->helper(array('metadata_helper'));
          unset($data['bio_summary']);
          unset($data['bio_content']);
          $data += fetchAlbumInfo($data, array('bio'));
          addAlbumBio($data);
        }
        else if ((time() - strtotime($data['bio_updated'])) > BIO_UPDATE_TIME) {
          $data['update_bio'] = true;
        }
        // Get logged in user's listening data.
        if ($data['user_id'] = $this->session->userdata('user_id')) {
          $data += getAlbumListenings($data);
        }
        $data['listener_count'] = (getListeners($data) !== NULL) ? count(json_decode(getListeners($data), true)) : 0;
        if (empty($data['spotify_id'])) {
          $data['spotify_id'] = getSpotifyResourceId($data);
        }

        $rank = 0;
        $data['most_listened_alltime'] = false;
        $last_item_count = false;
        foreach (json_decode(getAlbums(array('lower_limit' => '1970-00-00', 'limit' => 200))) as $item) {
          $rank++;
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
          foreach (json_decode(getAlbums(array('lower_limit' => '1970-00-00', 'limit' => 200, 'username' => $this->session->userdata('username')))) as $item) {
            $rank++;
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
        foreach (json_decode(getMusicByYear(array('lower_limit' => '1970-00-00', 'limit' => 10, 'tag_id' => $data['year'], 'username' => (!empty($_GET['u']) ? $_GET['u'] : '')))) as $item) {
          $rank++;
          if ($item->album_id == $data['album_id']) {
            $data['most_listened_releaseyear'] = $rank;
            break;
          }
          $last_item_count = $item->count;
        }

        $data['sub_group_by'] = TBL_artists . '.`album_id`';
        $data['group_by'] = TBL_album . '.`id`';
        $data['per_year_user'] = (getListeningsPerYear($data) !== NULL && $data['year'] !== date('Y')) ? json_decode(getListeningsPerYear($data), true)[0]['count'] : 0;
        unset($data['user_id']);
        $data['per_year'] = (getListeningsPerYear($data) !== NULL && $data['year'] !== date('Y')) ? json_decode(getListeningsPerYear($data), true)[0]['count'] : 0;
        $artist_info = getArtistInfo(array('artist_name' => decode($value1)));
        $data['artist_id'] = $artist_info['artist_id'];
        $data['artist_name'] = $artist_info['artist_name'];
        $data += $_REQUEST;
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        $data['js_include'] = array('music/album', 'music/lastfm', 'helpers/artist_album_helper', 'helpers/tag_helper', 'libs/highcharts', 'libs/peity', 'helpers/chart_helper', 'helpers/shout_helper', 'helpers/time_interval_helper', 'helpers/per_year_helper');

        $this->load->view('site_templates/header');
        $this->load->view('music/album_view', $data);
        $this->load->view('site_templates/footer', $data);
      }
      else {
        show_404();
      }
    }
  }

  public function recent($artist_name = '', $album_name = FALSE) {
    // Load helpers.
    $this->load->helper(array('form'));

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
        foreach (json_decode(getAlbums(array('lower_limit' => '1970-00-00', 'limit' => 200))) as $item) {
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
          foreach (json_decode(getAlbums(array('lower_limit' => '1970-00-00', 'limit' => 200, 'username' => $this->session->userdata('username')))) as $item) {
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
        foreach (json_decode(getMusicByYear(array('lower_limit' => '1970-00-00', 'limit' => 10, 'tag_id' => $data['year'], 'username' => (!empty($_GET['u']) ? $_GET['u'] : '')))) as $item) {
          if ($item->count != $last_item_count) {
            $rank++;
          }
          if ($item->album_id == $data['album_id']) {
            $data['most_listened_releaseyear'] = $rank;
            break;
          }
          $last_item_count = $item->count;
        }

        $data['listener_count'] = count(json_decode(getListeners($data), true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        $data['js_include'] = array('music/recent_album', 'helpers/tag_helper');

        $this->load->view('site_templates/header');
        $this->load->view('music/recent_album_view', $data);
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
        foreach (json_decode(getArtists(array('lower_limit' => '1970-00-00', 'limit' => 200))) as $item) {
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
          foreach (json_decode(getArtists(array('lower_limit' => '1970-00-00', 'limit' => 200, 'username' => $this->session->userdata('username')))) as $item) {
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

        $data['listener_count'] = count(json_decode(getListeners($data), true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        $data['js_include'] = array('music/recent_artist', 'helpers/tag_helper');

        $this->load->view('site_templates/header');
        $this->load->view('music/recent_artist_view', $data);
        $this->load->view('site_templates/footer', $data);
      }
      else {
        show_404();
      }
    }
    else {
      // Load helpers.
      $this->load->helper(array('img_helper', 'music_helper', 'output_helper'));

      $opts = array(
        'human_readable' => false,
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
      $data['total_count'] = getListeningCount(array(), TBL_listening);
      if ($this->session->userdata('logged_in') === TRUE) {
        $data['user_count'] = getListeningCount(array('username' => $this->session->userdata('username')), TBL_listening);
      }
      $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
      $data['js_include'] = array('music/recent', 'libs/jquery.daterangepicker', 'helpers/add_listening_helper');

      $this->load->view('site_templates/header');
      $this->load->view('music/recent_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
  }

  public function mosaic() {
    // Load helpers.
    $this->load->helper(array('form', 'img_helper', 'music_helper', 'output_helper'));

    $data = array();
    $opts = array(
      'human_readable' => false,
      'limit' => '1',
      'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
      'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
      'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
    );
    $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
    $data['total_count'] = getListeningCount(array(), TBL_listening);
    if ($this->session->userdata('logged_in') === TRUE) {
      $data['user_count'] = getListeningCount(array('username' => $this->session->userdata('username')), TBL_listening);
    }
    $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
    $data['js_include'] = array('music/mosaic', 'libs/jquery.daterangepicker', 'helpers/add_listening_helper');

    $this->load->view('site_templates/header');
    $this->load->view('music/mosaic_view', $data);
    $this->load->view('site_templates/footer', $data);
  }

  public function listener($artist_name = '', $album_name = FALSE) {
    // Load helpers.
    $this->load->helper(array('form'));

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
        
        $data['listener_count'] = count(json_decode(getListeners($data), true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        $data['js_include'] = array('music/listener_album', 'helpers/tag_helper');

        $this->load->view('site_templates/header');
        $this->load->view('music/listener_album_view', $data);
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

        $data['listener_count'] = count(json_decode(getListeners($data), true));
        $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
        $data['js_include'] = array('music/listener_artist', 'helpers/tag_helper');

        $this->load->view('site_templates/header');
        $this->load->view('music/listener_artist_view', $data);
        $this->load->view('site_templates/footer', $data);
      }
      else {
        show_404();
      }
    }
    else {
      // Load helpers
      $this->load->helper(array('img_helper', 'music_helper', 'output_helper'));
      
      $opts = array(
        'limit' => '1',
        'lower_limit' => date('Y-m', strtotime('first day of last month')) . '-00',
        'upper_limit' => date('Y-m', strtotime('first day of last month')) . '-31',
        'username' => (!empty($_GET['u']) ? $_GET['u'] : '')
      );
      $data['top_artist'] = (json_decode(getArtists($opts), true) !== NULL) ? json_decode(getArtists($opts), true)[0] : array('artist_id' => 0);
      $data['total_count'] = getListeningCount(array(), TBL_listening);
      if ($this->session->userdata('logged_in') === TRUE) {
        $data['user_count'] = getListeningCount(array('username' => $this->session->userdata('username')), TBL_listening);
      }
      $data['logged_in'] = ($this->session->userdata('logged_in') === TRUE) ? 'true' : 'false';
      $data['js_include'] = array('music/listener');
      
      $this->load->view('site_templates/header');
      $this->load->view('music/listener_view', $data);
      $this->load->view('site_templates/footer', $data);
    }
  }
}
?>
