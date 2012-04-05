<?php
class RecommendationNewAlbum extends CI_Controller {
  public function index() {
    $sql = '';

    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      echo json_encode($query->result());
      return false;
    }
    else {
      echo json_encode(array('error' => array('msg' => ERR_NO_RESULTS)));
      return false;
    }
  }
}
?>