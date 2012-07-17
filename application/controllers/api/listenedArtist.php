<?php
class ListenedArtist extends CI_Controller {
  public function index() {
    $username = isset($_GET['username']) ? $_GET['username'] : '%';
    $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : '`artist_name` ASC';
    $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
    $human_readable = isset($_GET['human_readable']) ? $_GET['human_readable'] : FALSE;
    $q = "SELECT DISTINCT " . TBL_artist . ".`id`, " . TBL_artist . ".`artist_name`
          FROM " . TBL_album . ", ".TBL_artist.", ".TBL_listening . ", " . TBL_user . "
          WHERE " . TBL_listening . ".`user_id` = " . TBL_user . ".`id` 
            AND " . TBL_listening . ".`album_id` = " . TBL_album . ".`id` 
            AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
            AND " . TBL_user . ".`username` LIKE " . $this->db->escape($username) . "
          ORDER BY " . mysql_real_escape_string($order_by) . "
          LIMIT " . mysql_real_escape_string($limit);
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
      if (!empty($human_readable)) {
        $this->load->helper(array('text_helper'));
        echo indentJSON(json_encode($query->result()));
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