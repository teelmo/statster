<?php
class Tag extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List tags */
  public function get($tag_type = '') {
    // Load helpers
    $this->load->helper(array('output_helper'));

    if (in_array($tag_type, array('genre', 'keyword', 'release year', 'nationality'))) {
      if ($tag_type == 'genre') {
        $this->load->helper(array('genre_helper'));
        echo getGenres($_REQUEST);
      }
      else if ($tag_type == 'keyword') {
        $this->load->helper(array('keyword_helper'));
        echo getKeywords($_REQUEST);
      }
    }
    else {
      if ($_GET['tag_type'] == 'Genre') {
        $this->load->helper(array('genre_helper'));
        echo getMusicByGenre($_REQUEST);
      }
      else if ($_GET['tag_type'] == 'Keyword') {
        $this->load->helper(array('keyword_helper'));
        echo getMusicByKeyword($_REQUEST);
      }
    }
  }

  /* Add a tag */
  public function add() {
    // Load helpers
    
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