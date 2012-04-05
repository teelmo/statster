<?php
class PopularGenre extends CI_Controller {
  public function index() {
    $lower_limit = isset($_GET['lower_limit']) ? $_GET['lower_limit'] : date("Y-m-d", time() - (31 * 24 * 60 * 60));
    $upper_limit = isset($_GET['upper_limit']) ? $_GET['upper_limit'] : date("Y-m-d");
    $artist = isset($_GET['artist']) ? $_GET['artist'] : '%';
    $album = isset($_GET['album']) ? $_GET['album'] : '%';
    $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : '`count` DESC';
    $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
    $sql = "SELECT count(" . TBL_genre . ".`id`) as `count`, " . TBL_genre . ".`name`
            FROM " . TBL_album . ", 
                 " . TBL_artist . ", 
                 " . TBL_listening . ", 
                 " . TBL_user . ", 
                 " . TBL_genre . ", 
                 " . TBL_genres . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_album . ".`id` = " . TBL_genres . ".`album_id`
              AND " . TBL_genre . ".`id` = " . TBL_genres . ".`genre_id`
              AND " . TBL_listening . ".`date` BETWEEN " . $this->db->escape($lower_limit) . " 
                                                       AND " . $this->db->escape($upper_limit) . "
              AND " . TBL_artist . ".`artist_name` LIKE " . $this->db->escape($artist) . "
              AND " . TBL_album . ".`album_name` LIKE " . $this->db->escape($album) . "
              GROUP BY " . TBL_genre . ".`id`
              ORDER BY " . mysql_real_escape_string($order_by) . " 
              LIMIT " . mysql_real_escape_string($limit);
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