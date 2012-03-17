<?php
class TopAlbum extends CI_Controller {
  public function index() {
    $lower_limit = isset($_GET['lower_limit']) ? $_GET['lower_limit'] : '%';
    $upper_limit = isset($_GET['upper_limit']) ? $_GET['upper_limit'] : '%';
    $username = isset($_GET['username']) ? $_GET['username'] : '%';
    $artist = isset($_GET['artist']) ? $_GET['artist'] : '%';
    $album = isset($_GET['album']) ? $_GET['album'] : '%';
    $group_by = isset($_GET['group_by']) ? $_GET['group_by'] :  TBL_album . '.`id`';
    $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : '`count` DESC';
    $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
    $sql = "SELECT count(" . TBL_album . ".`id`) as `count`, 
                   " . TBL_artist .".`artist_name`, 
                   " . TBL_artist .".`id` as artist_id, 
                   " . TBL_album .".`album_name`, 
                   " . TBL_album .".`id` as album_id, 
                   " . TBL_album .".`year`, 
              (SELECT count(" . TBL_love . ".`album_id`)
                FROM " . TBL_love . "
                WHERE " . TBL_love . ".`album_id` = " . TBL_album . ".`id` 
                  AND " . TBL_love . ".`user_id` = " . TBL_user . ".`id`
              ) AS `love`, ".TBL_user.".`username`
            FROM " . TBL_album . ", " . TBL_artist . ", " . TBL_listening . " , " . TBL_user . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`date` BETWEEN " . $this->db->escape($lower_limit) . " 
                                                   AND " . $this->db->escape($upper_limit) . "
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_user . ".`username` LIKE " . $this->db->escape($username) . "
              AND " . TBL_artist . ".`artist_name` LIKE " . $this->db->escape($artist) . "
              AND " . TBL_album . ".`album_name` LIKE " . $this->db->escape($album) . "
              GROUP BY " . mysql_real_escape_string($group_by) . "
              ORDER BY " . mysql_real_escape_string($order_by) . "
              LIMIT " . mysql_real_escape_string($limit);
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