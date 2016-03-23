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
                       (CASE WHEN " . TBL_artist . ".`artist_name` LIKE ? THEN 0 ELSE 1 END) AS `artist_relevance`,
                       (CASE WHEN " . TBL_album . ".`album_name` LIKE ? THEN 0 ELSE 1 END) AS `album_relevance`
                FROM " . TBL_artist . ",
                     " . TBL_album . "
                WHERE " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id` 
                  AND (" . TBL_artist . ".`artist_name` LIKE ?
                   AND " . TBL_album . ".`album_name` LIKE ?)
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
        if (${!${false}=$query->result()}[0]->album_relevance > 0) {
          $results[] = array(
            'value' => ${!${false}=$query->result()}[0]->artist_name . ' ' . DASH . ' ',
            'img' => '',
            'label' => ${!${false}=$query->result()}[0]->artist_name . ' ' . DASH . ' ',
          );
        }
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
      // Genres search.
      $sql = "SELECT " . TBL_genre . ".`id` as genre_id,
                     " . TBL_genre . ".`name`
              FROM " . TBL_genre . "
              WHERE " . TBL_genre . ".`name` LIKE ?
              ORDER BY " . TBL_genre . ".`name`
              LIMIT 0, 10";
      $query = $this->db->query($sql, array($search_str_wc));
      if ($query->num_rows() > 0) {
        $results[] = array(
          'label' => '<span class="title">Genres</span>',
          'value' => '',
        );
        foreach ($query->result() as $row) {
          $results[] = array(
            'img' => '',
            'label' => $row->name,
            'url' => '/genre/' . url_title($row->name),
            'value' => $row->name
          );
        }
      }
      // Keywords search.
      $sql = "SELECT " . TBL_keyword . ".`id` as keyword_id,
                     " . TBL_keyword . ".`name`
              FROM " . TBL_keyword . "
              WHERE " . TBL_keyword . ".`name` LIKE ?
              ORDER BY " . TBL_keyword . ".`name`
              LIMIT 0, 10";
      $query = $this->db->query($sql, array($search_str_wc));
      if ($query->num_rows() > 0) {
        $results[] = array(
          'label' => '<span class="title">Keywords</span>',
          'value' => '',
        );
        foreach ($query->result() as $row) {
          $results[] = array(
            'img' => '',
            'label' => $row->name,
            'url' => '/keyword/' . url_title($row->name),
            'value' => $row->name
          );
        }
      }
      // Nationalities search.
      $sql = "SELECT " . TBL_nationality . ".`id` as nationality_id,
                     " . TBL_nationality . ".`country`
              FROM " . TBL_nationality . "
              WHERE " . TBL_nationality . ".`country` LIKE ?
              ORDER BY " . TBL_nationality . ".`country`
              LIMIT 0, 10";
      $query = $this->db->query($sql, array($search_str_wc));
      if ($query->num_rows() > 0) {
        $results[] = array(
          'label' => '<span class="title">Nationalities</span>',
          'value' => '',
        );
        foreach ($query->result() as $row) {
          $results[] = array(
            'img' => '',
            'label' => $row->country,
            'url' => '/nationality/' . url_title($row->country),
            'value' => $row->country
          );
        }
      }
      // Years search.
      $sql = "SELECT DISTINCT " . TBL_album . ".`year`
              FROM " . TBL_album . "
              WHERE " . TBL_album . ".`year` LIKE ?
              ORDER BY " . TBL_album . ".`year`
              LIMIT 0, 10";
      $query = $this->db->query($sql, array($search_str_wc));
      if ($query->num_rows() > 0) {
        $results[] = array(
          'label' => '<span class="title">Years</span>',
          'value' => '',
        );
        foreach ($query->result() as $row) {
          $results[] = array(
            'img' => '',
            'label' => $row->year,
            'url' => '/year/' . url_title($row->year),
            'value' => $row->year
          );
        }
      }
      // Users search.
      $sql = "SELECT " . TBL_user . ".`username`,
                     " . TBL_user . ".`id` as `user_id`
              FROM " . TBL_user . "
              WHERE " . TBL_user . ".`username` LIKE ?
              ORDER BY " . TBL_user . ".`username`
              LIMIT 0, 10";
      $query = $this->db->query($sql, array($search_str_wc));
      if ($query->num_rows() > 0) {
        $results[] = array(
          'label' => '<span class="title">Users</span>',
          'value' => '',
        );
        foreach ($query->result() as $row) {
          $results[] = array(
            'img' => getUserImg(array('user_id' => $row->user_id, 'size' => 64)),
            'label' => $row->username,
            'url' => '/user/' . url_title($row->username),
            'value' => $row->username
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
