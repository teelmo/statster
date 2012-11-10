<?php
class Love extends CI_Controller {
  public function index() {
    exit ('No direct script access allowed');
  }

  /* List loves */
  public function get() {
    // Load helpers
    $this->load->helper(array('favorites_helper', 'return_helper'));
    
    echo getLove($_REQUEST);
  }

  /* Add a love */
  public function add() {
    // Load helpers
    $this->load->helper(array('favorites_helper', 'id_helper'));

    echo addLove($_POST);  
  }

  /* Update love information */
  public function update() {
    // Load helpers
    
  }

  /* Delete love information */
  public function delete() {
    // Load helpers
    
  }
}
?>