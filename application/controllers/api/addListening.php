<?php
class AddListening extends CI_Controller {
  public function index() {
    // Load helpers
    $this->load->helper(array('id_helper', 'music_helper'));
    if (empty($_POST)) {
      echo json_encode(array('error' => array('msg' => '$_POST parameters not delivered')));
      return FALSE;
    }
    if (strpos($_POST['text'], DASH)) {
      $data = array();
      list($data['artist'], $data['album']) = explode(DASH, $_POST['text']);
      $data['artist'] = trim($data['artist']);
      $data['album'] = preg_replace('/^(.*)\(([0-9]){4}\)$/', '$1', $data['album']);
      $data['album'] = trim($data['album']);
      $data['album_id'] = getAlbumID($data);
      $data['date'] = trim($_POST['date']);
      if (!$data['album_id'] = getAlbumID($data)) {
        echo json_encode(array('error' => array('msg' => 'Album error. Can\'t solve album id.')));
        return FALSE;
      }
      if (!$data['user_id'] = $this->session->userdata('user_id')) {
        echo json_encode(array('error' => array('msg' => 'Username error. Can\'t solve user id.')));
        return FALSE;
      }

      // Add data to DB
      $sql = "INSERT
                INTO " . TBL_listening . " (`user_id`, `album_id`, `date`)
                VALUES ({$data['user_id']}, {$data['album_id']}, '{$data['date']}')";
      $query = $this->db->query($sql);
      if ($this->db->affected_rows() == 1) {
        $data['listening_id'] = $this->db->insert_id();
        if (!empty($_POST['format'])) {
          list($data['format'], $data['format_type']) = explode(':', $_POST['format']);
          addListeningFormat($data);
        }
      }
    }
    else {
      echo json_encode(array('error' => array('msg' => 'Format error.')));
      return FALSE;
    }
  }
}
?>