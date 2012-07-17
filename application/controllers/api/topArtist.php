<?php
class TopArtist extends CI_Controller {
  public function index() {
    $lower_limit = isset($_GET['lower_limit']) ? $_GET['lower_limit'] : date("Y-m-d", time() - (31 * 24 * 60 * 60));
    $upper_limit = isset($_GET['upper_limit']) ? $_GET['upper_limit'] : date("Y-m-d");
    $username = isset($_GET['username']) ? $_GET['username'] : '%';
    $artist = isset($_GET['artist']) ? $_GET['artist'] : '%';
    $group_by = isset($_GET['group_by']) ? $_GET['group_by'] :  TBL_artist . '.`id`';
    $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : '`count` DESC, `artist_name` ASC';
    $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
    $human_readable = isset($_GET['human_readable']) ? $_GET['human_readable'] : FALSE;
    $sql = "SELECT count(" . TBL_artist . ".`id`) as `count`, 
                   " . TBL_artist . ".`artist_name`, 
                   " . TBL_artist . ".`id` as artist_id, 
                   " . TBL_user . ". `username`, 
                   " . TBL_user . ". `id` as user_id, 
              (SELECT count(" . TBL_fan . ".`artist_id`)
                FROM " . TBL_fan . "
                WHERE " . TBL_fan . ".`artist_id` = " . TBL_artist . ".`id` 
                  AND " . TBL_fan . ".`user_id` = " . TBL_user . ".`id`
              ) AS `love`
            FROM " . TBL_album . ", 
                 " . TBL_artist . ", 
                 " . TBL_listening . " , 
                 " . TBL_user . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`date` BETWEEN " . $this->db->escape($lower_limit) . " 
                                                   AND " . $this->db->escape($upper_limit) . "
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_user . ".`username` LIKE " . $this->db->escape($username) . "
              AND " . TBL_artist . ".`artist_name` LIKE " . $this->db->escape($artist) . "
              GROUP BY " . mysql_real_escape_string($group_by) . "
              ORDER BY " . mysql_real_escape_string($order_by) . "
              LIMIT " . mysql_real_escape_string($limit);
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