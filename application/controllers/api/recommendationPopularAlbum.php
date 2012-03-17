<?php
class RecommendationPopularAlbum extends CI_Controller {
  public function index() {
    $username = isset($_GET['username']) ? $_GET['username'] : '%';
    $artist = isset($_GET['artist']) ? $_GET['artist'] : '%';
    $album = isset($_GET['album']) ? $_GET['album'] : '%';
    $date = isset($_GET['date']) ? $_GET['date'] : '%';
    $limit = isset($_GET['limit']) ? $_GET['limit'] : 10;
    $sql = "SELECT " . TBL_listening . ". `id`, 
                   " . TBL_artist . ". `artist_name`,
                   " . TBL_artist . ". `id` as artist_id,
                   " . TBL_album . ". `album_name`, 
                   " . TBL_album . ". `id` as album_id, 
                   " . TBL_album . ". `year`, 
                   " . TBL_listening . ". `date`, 
                   " . TBL_user . ". `username`, 
                   " . TBL_user . ". `id` as user_id, 
                  (SELECT count(" . TBL_love . ". `album_id`)
                    FROM " . TBL_love."
                    WHERE " . TBL_love . ". `album_id` = " . TBL_album . ". `id` 
                      AND " . TBL_love . ". `user_id` = " . TBL_user . ". `id`
                  ) AS love, " . TBL_listening . ". `created`
            FROM " . TBL_album.", " . TBL_artist.", " . TBL_listening.", " . TBL_user."
            WHERE " . TBL_album . ". `id` = " . TBL_listening . ". `album_id`
              AND " . TBL_user . ". `id` = " . TBL_listening . ". `user_id`
              AND " . TBL_album . ". `artist_id` = " . TBL_artist . ". `id`
              AND " . TBL_user . ". `username` LIKE " . $this->db->escape($username) . "
              AND " . TBL_artist . ". `artist_name` LIKE " . $this->db->escape($artist) . "
              AND " . TBL_album . ". `album_name` LIKE " . $this->db->escape($album) . "
              AND " . TBL_listening . ". `date` LIKE " . $this->db->escape($date) . "
            ORDER BY " . TBL_listening . ". `date` DESC, " . TBL_listening . ". `id` DESC
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