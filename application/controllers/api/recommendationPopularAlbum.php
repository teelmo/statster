<?php
class RecommendationPopularAlbum extends CI_Controller {
  public function index() {
    $human_readable = isset($_GET['human_readable']) ? $_GET['human_readable'] : FALSE;
    $sql = '';

    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      if (!empty($human_readable)) {
        $this->load->helper(array('text_helper'));
        echo indent(json_encode($query->result()));
      }
      else {
        echo json_encode($query->result());
      }
      return FALSE;
    }
    else {
      if (!empty($human_readable)) {
        $this->load->helper(array('text_helper'));
        echo '<pre>' . indent(json_encode(array('error' => array('msg' => ERR_NO_RESULTS)))) . '</pre>';
      }
      else {
        echo json_encode(array('error' => array('msg' => ERR_NO_RESULTS)));
      }
      return FALSE;
    }
  }
}
?>