<?php
class ListenedAlbum extends CI_Controller {
  public function index() {
    $username = isset($_GET['username']) ? $_GET['username'] : '%';
    $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : '`album_name` ASC';
    $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
    $sql = "SELECT " . TBL_album . ".`id`, " . TBL_album . ".`album_name`
            FROM " . TBL_album . ", 
                 " . TBL_listening . ", 
                 " . TBL_user . "
            WHERE " . TBL_listening . ".`user_id` = " . TBL_user. ".`id` 
              AND " . TBL_listening . ".`album_id` = " . TBL_album . ".`id` 
              AND " . TBL_user . ".`username` LIKE " . $this->db->escape($username) . "
            ORDER BY " . $this->db->escape($order_by) . "
            LIMIT " . $this->db->escape($limit);
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