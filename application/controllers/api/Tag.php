<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Tag extends MY_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List tags */
  public function get($tag_type = '', $type = '') {
    // Load helpers
    $this->load->helper(array('output_helper'));

    switch ($tag_type) {
      case 'genre':
        $this->load->helper(array('genre_helper'));
        if ($type === 'cumulative') {
          echo getGenresCumulative($_REQUEST);
        }
        else {
          echo getGenres($_REQUEST);
        }
        break;
      case 'keyword':
        $this->load->helper(array('keyword_helper'));
        if ($type === 'cumulative') {
          echo getKeywordsCumulative($_REQUEST);
        }
        else {
          echo getKeywords($_REQUEST);
        }
        break;
      case 'nationality':
        $this->load->helper(array('nationality_helper'));
        if ($type === 'cumulative') {
          echo getNationalitiesCumulative($_REQUEST);
        }
        else {
          echo getNationalities($_REQUEST);
        }
        break;
      case 'year':
        $this->load->helper(array('year_helper'));
        if ($type === 'cumulative') {
          echo getYearsCumulative($_REQUEST);
        }
        else {
          echo getYears($_REQUEST);
        }
        break;
      case 'album':
        $this->load->helper(array('music_helper', 'album_helper'));
        echo getAlbumTags($_REQUEST);
        break;
      case 'artist':
        $this->load->helper(array('music_helper', 'artist_helper'));
        echo getArtistTags($_REQUEST);
        break;
      default:
        if ($_GET['tag_type'] == 'genre') {
          $this->load->helper(array('genre_helper'));
          echo getMusicByGenre($_REQUEST);
        }
        else if ($_GET['tag_type'] == 'keyword') {
          $this->load->helper(array('keyword_helper'));
          echo getMusicByKeyword($_REQUEST);
        }
        else if ($_GET['tag_type'] == 'nationality') {
          $this->load->helper(array('nationality_helper'));
          echo getMusicByNationality($_REQUEST);
        }
        else if ($_GET['tag_type'] == 'year') {
          $this->load->helper(array('year_helper'));
          echo getMusicByYear($_REQUEST);
        }
        break;
    }
  }

  /* Add a tag to album */
  public function add($tag_type = '') {
    // Load helpers
    if ($_REQUEST['type'] == 'artist') {
      $this->load->helper(array('id_helper'));
      $albums = getAlbumIDs($_REQUEST);
    }
    else if ($_REQUEST['type'] == 'album') {
      $albums = array();
      $albums[0] = new stdClass();
      $albums[0]->album_id = $_REQUEST['album_id'];
    }
    foreach ($albums as $key => $album) {
      $_REQUEST['album_id'] = $album->album_id;
      switch ($tag_type) {
        case 'genre':
          $this->load->helper(array('genre_helper'));
          echo addAlbumGenre($_REQUEST);
          break;
        case 'keyword':
          $this->load->helper(array('keyword_helper'));
          echo addAlbumKeyword($_REQUEST);
          break;
        case 'nationality':
          $this->load->helper(array('nationality_helper'));
          echo addAlbumNationality($_REQUEST);
          break;
        default:
          header("HTTP/1.1 400 Bad Request");
          break;
      }
    }
  }

  /* Update tag information */
  public function update() {
    // Load helpers
    header('HTTP/1.1 501 Not Implemented');
  }

  /* Delete tag information */
  public function delete() {
    // Load helpers
    header('HTTP/1.1 501 Not Implemented');
  }
}
?>