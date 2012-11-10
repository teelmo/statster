<?php
class Listening extends CI_Controller {
  public function index() {
    exit ('No direct script access allowed');
  }

  public function get() {
    // Load helpers
    $this->load->helper(array('music_helper', 'return_helper'));
    
    echo getRecentlyListened($_REQUEST);
  }

  public function add() {
    // Load helpers
    $this->load->helper(array('id_helper', 'music_helper'));

    if (empty($_POST)) {
      echo json_encode(array('error' => array('msg' => '$_POST parameters not delivered')));
      return FALSE;
    }
    if (strpos($_POST['text'], DASH)) {
      $data = array();
      
      // Get user id from session
      if (!$data['user_id'] = $this->session->userdata('user_id')) {
        echo json_encode(array('error' => array('msg' => 'Username error. Can\'t solve user id.')));
        return FALSE;
      }
      list($data['artist'], $data['album']) = explode(DASH, $_POST['text']);
      $data['artist'] = trim($data['artist']);
      $data['album'] = preg_replace('/^(.*)\(([0-9]){4}\)$/', '$1', $data['album']);
      $data['album'] = trim($data['album']);
      // Check that album exists
      if (!$data['album_id'] = getAlbumID($data)) {
        echo json_encode(array('error' => array('msg' => 'Album error. Can\'t solve album id.')));
        return FALSE;
      }
      $data['date'] = trim($_POST['date']);

      // Add listening data to DB
      $sql = "INSERT
                INTO " . TBL_listening . " (`user_id`, `album_id`, `date`)
                VALUES ({$data['user_id']}, {$data['album_id']}, '{$data['date']}')";
      $query = $this->db->query($sql);
      if ($this->db->affected_rows() == 1) {
        $data['listening_id'] = $this->db->insert_id();
        // Add listening format data to DB
        if (!empty($_POST['format'])) {
          list($data['format'], $data['format_type']) = explode(':', $_POST['format']);
          addListeningFormat($data);
        }
      }
      else {
        echo json_encode(array('error' => array('msg' => ERR_GENERAL)));
        return FALSE;
      }
    }
    else {
      echo json_encode(array('error' => array('msg' => 'Format error.')));
      return FALSE;
    }
  }

  /* Update listening information */
  public function update() {
    // Load helpers
    
  }

  /* Delete listening information */
  public function delete() {
    // Load helpers
    
  }
}
?> 