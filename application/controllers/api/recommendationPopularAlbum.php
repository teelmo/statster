<?php
class RecommendationPopularAlbum extends CI_Controller {
  public function index() {
    $sql = '';

    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      echo json_encode($query->result());
    }
    else {
      echo json_encode('');
    }
  }
}
?>