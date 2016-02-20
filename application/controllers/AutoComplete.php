<?php
class AutoComplete extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  public function addListening() {
    // Load helpers
    $this->load->helper(array('img_helper'));
    
    $results = array();
    $search_str = $_GET['term'];
    if (!empty($search_str)) {
      if (strpos($search_str, DASH)) {
        list($data['artist'], $data['album']) = explode(DASH, $search_str);
        $search_str_db_artist = trim($data['artist']);
        $search_str_db_artist_wc = '%' . trim($data['artist']) . '%';
        $search_str_db_album = trim($data['album']). '%';
        $search_str_db_album_wc = '%' . trim($data['album']) . '%';
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
                   AND " . TBL_album . ".`album_name` LIKE " . $this->db->escape($search_str_db_album_wc) . ")
                ORDER BY `artist_relevance`,
                         " . TBL_album . ".`year` DESC,
                         `album_relevance`
                LIMIT 0, 20";
      }
      else {
        $search_str_db_artist = $search_str_db_album = trim($search_str) . '%';
        $search_str_db_artist_wc = $search_str_db_album_wc = '%' . trim($search_str) . '%';
        $sql = "SELECT " . TBL_artist . ".`id` as artist_id,
                       " . TBL_artist . ".`artist_name`,
                       " . TBL_album . ".`id` as album_id,
                       " . TBL_album . ".`album_name`,
                       " . TBL_album . ".`year`,
                       (CASE WHEN " . TBL_artist . ".`artist_name` LIKE ? THEN 0 ELSE 1 END) AS `artist_relevance`,
                       (CASE WHEN " . TBL_album . ".`album_name` LIKE ? THEN 0 ELSE 1 END) AS `album_relevance`
                FROM " . TBL_artist . ",
                     " . TBL_album . "
                WHERE " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id` 
                  AND (" . TBL_artist . ".`artist_name` LIKE ?
                   OR " . TBL_album . ".`album_name` LIKE ?)
                ORDER BY `artist_relevance`,
                         " . TBL_album . ".`year` DESC,
                         `album_relevance`
                LIMIT 0, 20";
      }
      $query = $this->db->query($sql, array($search_str_db_artist, $search_str_db_album, $search_str_db_artist_wc, $search_str_db_album_wc));
      if ($query->num_rows() > 0) {
        foreach ($query->result() as $row) {
          $results[] = array(
            'value' => $row->artist_name . ' ' . DASH . ' ' . $row->album_name,
            'img' => getAlbumImg(array('album_id' => $row->album_id, 'size' => 32)),
            'label' => $row->artist_name . ' ' . DASH . ' ' . $row->album_name . ' (' . $row->year . ')'
          );
        }
      }
      else {
        if (!strpos($search_str, DASH)) {
          $results[] = array(
            'img' => '',
            'label' => 'No results',
            'value' => $search_str
          );
        }
      }
      echo json_encode($results);
      return;
    }
    else {
      $results[] = array(
        'img' => '',
        'label' => 'No results',
        'value' => $search_str
      );
      echo json_encode($results);
      return;
    }
  }
  public function search() {
    // Load helpers
    $this->load->helper(array('img_helper'));
    
    $results = array();
    $search_str = $_GET['term'];
    if (!empty($search_str)) {
      $search_str = trim($search_str);
      $search_str_wc = '%' . trim($search_str) . '%';
      // Artists search.
      $sql = "SELECT " . TBL_artist . ".`id` as artist_id,
                     " . TBL_artist . ".`artist_name`,
                     (CASE WHEN " . TBL_artist . ".`artist_name` LIKE ? THEN 0 ELSE 1 END) AS `artist_relevance`
              FROM " . TBL_artist . "
              WHERE " . TBL_artist . ".`artist_name` LIKE ?
              ORDER BY `artist_relevance`
              LIMIT 0, 10";
      $query = $this->db->query($sql, array($search_str, $search_str_wc));
      if ($query->num_rows() > 0) {
        $results[] = array(
          'label' => '<span class="title">Artists</span>',
          'value' => ''
        );
        foreach ($query->result() as $row) {
          $results[] = array(
            'img' => getArtistImg(array('artist_id' => $row->artist_id, 'size' => 64)),
            'label' => $row->artist_name,
            'url' => '/music/' . url_title($row->artist_name),
            'value' => $row->artist_name
          );
        }
      }
      // Albums search.
      $sql = "SELECT " . TBL_artist . ".`id` as artist_id,
                     " . TBL_artist . ".`artist_name`,
                     " . TBL_album . ".`id` as album_id,
                     " . TBL_album . ".`album_name`,
                     (CASE WHEN " . TBL_album . ".`album_name` LIKE ? THEN 0 ELSE 1 END) AS `album_relevance`
              FROM " . TBL_artist . ",
                   " . TBL_album . "
              WHERE " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id`
                AND " . TBL_album . ".`album_name` LIKE ?
              ORDER BY `album_relevance`
              LIMIT 0, 10";
      $query = $this->db->query($sql, array($search_str, $search_str_wc));
      if ($query->num_rows() > 0) {
        $results[] = array(
          'label' => '<span class="title">Albums</span>',
          'value' => '',
        );
        foreach ($query->result() as $row) {
          $results[] = array(
            'img' => getAlbumImg(array('album_id' => $row->album_id, 'size' => 64)),
            'label' => $row->album_name,
            'url' => '/music/' . url_title($row->artist_name) . '/' . url_title($row->album_name),
            'value' => $row->album_name
          );
        }
      }
      if (empty($results)) {
        $results[] = array(
          'img' => '',
          'label' => 'No results',
          'value' => $search_str
        );
      }
      // Return all search results.
      echo json_encode($results);
      return;
    }
    else {
      $results[] = array(
          'img' => '',
          'label' => 'No results',
          'value' => $search_str
        );
      echo json_encode($results);
      return;
    }
  }
}
?>
