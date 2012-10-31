<?php
class TopKeyword extends CI_Controller {
  public function index() {
    // Load helpers
    $this->load->helper(array('tag_helper', 'return_helper'));
    
    echo getTopKeywords($_REQUEST);
  }
}
?>