<?php
class Tag extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List tags */
  public function get($tag_type = '') {
    // Load helpers
    $this->load->helper(array('tag_helper', 'output_helper'));

    if (in_array($tag_type, array('genre', 'tag', 'release year', 'nationality'))) {
      $_REQUEST['tag_type'] = $tag_type;
      echo getGenres($_REQUEST);
    }
    else {
      echo getMusicByGenre($_REQUEST);
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