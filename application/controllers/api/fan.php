<?php
class Fan extends CI_Controller {
  public function index() {
    exit ('No direct script access allowed');
  }

  /* List fans */
  public function get($artist_id = FALSE) {
    // Load helpers
    $this->load->helper(array('fan_helper', 'return_helper'));
    
    $_REQUEST['artist_id'] = $artist_id;
    echo getFan($_REQUEST);
  }

  /* Add a fan */
  public function add() {
    // Load helpers
    $this->load->helper(array('fan_helper'));

    echo addFan($_POST);    
  }

  /* Update fan information */
  public function update() {
    // Load helpers
    
  }

  /* Delete fan information */
  public function delete() {
    // Load helpers
    
  }
}
?>