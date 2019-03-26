<?php
class Inbox extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  /* List bulletins */
  public function get() {
    // Load helpers
    $this->load->helper(array('inbox_helper', 'output_helper'));
    
    echo getBulletins($_REQUEST);
  }

  /* Add a bulletin */
  public function add() {
    // Load helpers
    header('HTTP/1.1 501 Not Implemented');
  }

  /* Update bulletin information */
  public function update() {
    // Load helpers
    header('HTTP/1.1 501 Not Implemented');
  }

  /* Delete bulletin information */
  public function delete() {
    // Load helpers
    header('HTTP/1.1 501 Not Implemented');
  }
}
?>
