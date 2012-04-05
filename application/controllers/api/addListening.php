<?php
class AddListening extends CI_Controller {
  public function index() {
    $this->load->helper(array('id_helper'));

    if (empty($_POST)) {
      echo json_encode(array('error' => array('msg' => '$_POST parameters not delivered')));
      return false;
    }
    else {
      if (strpos($_POST['text'], '-')) {
      //if (strpos($_POST['text'], DASH)) {
        $data = array();
        list($data['artist'], $data['album']) = explode('-', $_POST['text']);
        //list($data['artist'], $data['album']) = explode(DASH, $_POST['text']);
        if (!$data['album_id'] = getAlbumID($data['artist'], $data['album'])) {
          echo json_encode(array('error' => array('msg' => 'Album error. Can\'t solve album id.')));
          return false;
        }
        if (!$data['user_id'] = getUserID($_SESSION['username'])) {
          echo json_encode(array('error' => array('msg' => 'Username error. Can\'t solve user id.')));
          return false;
        }
        // Check user 

        // Add data to DB
      }
    }
  }
}
?>