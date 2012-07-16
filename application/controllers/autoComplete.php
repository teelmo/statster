<?php
class AutoComplete extends CI_Controller {

  public function index() {
    exit('No direct script access allowed');
  }

  public function addListening() {
    $this->load->helper(array('img_helper'));
    $content = array();
    $search_str = $_GET['query'];
    if (!empty($search_str)) {
      if (strpos($search_str, DASH)) {
        list($data['artist'], $data['album']) = explode(DASH, $search_str);
        $search_str_db_artist = trim($data['artist']);
        $search_str_db_artist_wc = '%' . trim($data['artist']) . '%';
        $search_str_db_album = trim($data['album']). '%';
        $search_str_db_album_wc = '%' . trim($data['album']) . '%';
      }
      else {
        $search_str_db_artist = $search_str_db_album = $search_str. '%';
        $search_str_db_artist_wc = $search_str_db_album_wc = '%' . $search_str . '%';
      }
      $search_str_db = '%' . $search_str . '%';
      $sql = "SELECT " . TBL_artist . ".`id` as artist_id,
                     " . TBL_artist . ".`artist_name`,
                     " . TBL_album . ".`id` as album_id,
                     " . TBL_album . ".`album_name`,
                     " . TBL_album . ".`year`,
                     (CASE WHEN " . TBL_artist . ".`artist_name` LIKE " . $this->db->escape($search_str_db_artist) . " THEN 0 ELSE 1 END) AS `artist_relevance`,
                     (CASE WHEN " . TBL_album . ".`album_name` LIKE " . $this->db->escape($search_str_db_album) . " THEN 0 ELSE 1 END) AS `album_relevance`
              FROM " . TBL_artist . ",
                   " . TBL_album . "
              WHERE " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id` 
                AND (" . TBL_artist . ".`artist_name` LIKE " . $this->db->escape($search_str_db_artist_wc) . "
                 OR " . TBL_album . ".`album_name` LIKE " . $this->db->escape($search_str_db_album_wc) . ")
              ORDER BY `artist_relevance`,
                       " . TBL_album . ".`year` DESC, 
                       `album_relevance`";
      $query = $this->db->query($sql);
      if ($query->num_rows() > 0) {
        foreach ($query->result() as $row) {
          $content['titles'][] = $row->artist_name . " " . DASH . " " . $row->album_name . "(" . $row->year . ")";
          $content['suggestions'][] = $row->artist_name . " " . DASH . " " . $row->album_name . " (" . $row->year . ")";
          $content['albums'][] = $row->artist_name . " " . DASH . " " . $row->album_name;
          $content['data'][] = $row->artist_name . " " . DASH . " " . $row->album_name;
          $content['images'][] = getAlbumImg(array('album_id' => $row->album_id, 'size' => 20));
        }
        echo json_encode(array('query' => $search_str, 'content' => array("" => $content)));
        return false;
      }
      echo json_encode(array('query' => $search_str, 'content' => null));
      return false;
    }
    else {
      echo json_encode(array('query' => $search_str, 'content' => null));
      return false;
    }
  }
}
?>