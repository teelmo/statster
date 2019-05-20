<?php
class Search extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  public function get($limit) {
    // Load helpers.
    $this->load->helper(array('img_helper'));
    
    $results = array();
    $search_str = isset($_GET['q']) ? $_GET['q'] : (isset($_GET['term']) ? $_GET['term'] : FALSE);
    if (!empty($search_str)) {
      $search_str = trim($search_str);
      $results[] = array(
        'label' => 'Seach for ' . $search_str,
        'url' => '/search/?q=' . url_title($search_str),
        'value' => 'Search'
      );
      $search_str_wc = '%' . trim($search_str) . '%';
      // Artists search.
      $sql = "SELECT " . TBL_artist . ".`id` as artist_id,
                     " . TBL_artist . ".`artist_name`,
                     (CASE WHEN " . TBL_artist . ".`artist_name` LIKE ? THEN 0 ELSE 1 END) AS `artist_relevance`
              FROM " . TBL_artist . "
              WHERE " . TBL_artist . ".`artist_name` LIKE ?
              COLLATE utf8_swedish_ci
              ORDER BY `artist_relevance`
              LIMIT 0, " . $this->db->escape_str($limit);
      $query = $this->db->query($sql, array($search_str, $search_str_wc));
      if ($query->num_rows() > 0) {
        $results[] = array(
          'label' => '<span class="title">Artists</span>',
          'value' => ''
        );
        foreach ($query->result() as $row) {
          $results[] = array(
            'artist_name' => $row->artist_name,
            'img' => getArtistImg(array('artist_id' => $row->artist_id, 'size' => 64)),
            'label' => $row->artist_name,
            'type' => 'artist',
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
              LIMIT 0, " . $this->db->escape_str($limit);
      $query = $this->db->query($sql, array($search_str, $search_str_wc));
      if ($query->num_rows() > 0) {
        $results[] = array(
          'label' => '<span class="title">Albums</span>',
          'value' => '',
        );
        foreach ($query->result() as $row) {
          $results[] = array(
            'album_name' => $row->album_name,
            'artist_name' =>  $row->artist_name,
            'img' => getAlbumImg(array('album_id' => $row->album_id, 'size' => 64)),
            'label' => $row->album_name,
            'type' => 'album',
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
              LIMIT 0, " . $this->db->escape_str($limit);
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
            'type' => 'genre',
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
              LIMIT 0, " . $this->db->escape_str($limit);
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
            'type' => 'keyword',
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
              LIMIT 0, " . $this->db->escape_str($limit);
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
            'type' => 'nationality',
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
              LIMIT 0, " . $this->db->escape_str($limit);
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
            'type' => 'year',
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
              LIMIT 0, " . $this->db->escape_str($limit);
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
            'type' => 'user',
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
