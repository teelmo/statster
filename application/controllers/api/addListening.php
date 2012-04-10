<?php
class AddListening extends CI_Controller {
  public function index() {
    $this->load->helper(array('id_helper'));
    if (empty($_POST)) {
      echo json_encode(array('error' => array('msg' => '$_POST parameters not delivered')));
      return false;
    }
    if (strpos($_POST['text'], DASH)) {
      $data = array();
      $data['username'] = 'teelmo';
      list($data['artist'], $data['album']) = explode(DASH, $_POST['text']);
      $data['artist'] = trim($data['artist']);
      $data['album'] = trim($data['album']);
      $data['album_id'] = getAlbumID($data);
      if (!$data['album_id'] = getAlbumID($data)) {
        echo json_encode(array('error' => array('msg' => 'Album error. Can\'t solve album id.')));
        return false;
      }
      if (!$data['user_id'] = getUserID($data)) {
        echo json_encode(array('error' => array('msg' => 'Username error. Can\'t solve user id.')));
        return false;
      }
      // Check user 

      // Add data to DB
      $date = date('Y-m-d');
      $sql = "INSERT
                INTO " . TBL_listening . " (`user_id`, `album_id`, `date`)
                VALUES ({$data['user_id']}, {$data['album_id']}, '$date')";
      $query = $this->db->query($sql);
      if($this->db->affected_rows() != 1) {

      }
    }
    else {
      echo json_encode(array('error' => array('msg' => 'Format error.')));
      return false;
    }
  }
}
?>