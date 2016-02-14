<?php
class Tag extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List tags */
  public function get($tag_type = '') {
    // Load helpers
    $this->load->helper(array('output_helper'));

    switch ($tag_type) {
      case 'genre':
        $this->load->helper(array('genre_helper'));
        echo getGenres($_REQUEST);
        break;
      case 'keyword':
        $this->load->helper(array('keyword_helper'));
        echo getKeywords($_REQUEST);
        break;
      case 'nationality':
        $this->load->helper(array('nationality_helper'));
        echo getNationalities($_REQUEST);
        break;
      case 'year':
        $this->load->helper(array('year_helper'));
        echo getYears($_REQUEST);
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

  /* Add a tag */
  public function add($tag_type = '') {
    // Load helpers

    switch ($tag_type) {
      case 'genre':
        $this->load->helper(array('genre_helper'));
        echo addGenre($_REQUEST);
        break;
      case 'keyword':
        $this->load->helper(array('keyword_helper'));
        echo addKeyword($_REQUEST);
        break;
      case 'nationality':
        $this->load->helper(array('nationality_helper'));
        echo addNationality($_REQUEST);
        break;
      case 'year':
        $this->load->helper(array('year_helper'));
        echo addYear($_REQUEST);
        break;
      default:
        header("HTTP/1.1 400 Bad Request");
        break;
    }
  }

  /* Update tag information */
  public function update() {
    // Load helpers
    
  }

  /* Delete tag information */
  public function delete() {
    // Load helpers
    
  }
}
?>
