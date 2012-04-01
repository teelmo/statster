<?php
class AddListening extends CI_Controller {
  public function index() {
    $this->load->helper(array('id_helper'));

    $data = array();
    if (strpos($_POST['text'], '-')) {
      list($data['artist'], $data['album']) = explode(DASH, $_POST['text']);
      if (!$data['album_id'] = getAlbumID($data['artist'], $data['album']) && 
          !$data['user_id'] = getUserID($data['username'])) { 
        echo json_encode('');
      }
    }
    /*
    $sql = "INSERT 
              INTO " . TBL_listening . " (`user_id`, `album_id`, `date`) 
              VALUES ($user_id, $album_id, '$date')";
    $query = $this->db->query($sql);
    if ($listening_id = $this->db->insert_id() > 0) {
      echo json_encode($query->result());
    }
    else {
      echo json_encode('');
    }*/
  }
}
?>