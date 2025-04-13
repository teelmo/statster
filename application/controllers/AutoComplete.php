<?php
class AutoComplete extends CI_Controller {

  public function index() {
    exit ('No direct script access allowed');
  }

  public function addListening() {
    // Load helpers.
    $this->load->helper(array('music_helper', 'img_helper'));
    
    $results = array();
    $search_str = $_GET['term'];
    if (!empty($search_str)) {
      $search_str = preg_replace('/\s+/u', ' ', $search_str);
      if (strpos($search_str, DASH)) {
        list($data['artist'], $data['album']) = explode(DASH, $search_str);
        $search_str_db_artist = trim($data['artist']);
        $search_str_db_artist_wc = '%' . trim($data['artist']) . '%';
        $search_str_db_album = trim($data['album']). '%';
        $search_str_db_album_wc = '%' . trim($data['album']) . '%';
        $sql = "SELECT " . TBL_artists . ".`artist_id`,
                       " . TBL_artists . ".`album_id`,
                       " . TBL_artist . ".`artist_name`,
                       " . TBL_album . ".`album_name`,
                       " . TBL_album . ".`year`,
                       (CASE WHEN " . TBL_artist . ".`artist_name` LIKE ? THEN 0 ELSE 1 END) AS `artist_relevance`,
                       (CASE WHEN " . TBL_album . ".`album_name` LIKE ? THEN 0 ELSE 1 END) AS `album_relevance`
                FROM " . TBL_album . ",
                     " . TBL_artist . ",
                     (SELECT " . TBL_artists . ".`artist_id`,
                             " . TBL_artists . ".`album_id`
                      FROM " . TBL_artists . "
                      GROUP BY " . TBL_artists . ".`album_id`) AS " . TBL_artists . "
                WHERE " . TBL_artists . ".`album_id` = " . TBL_album . ".`id`
                  AND " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
                  AND (" . TBL_artist . ".`artist_name` LIKE ?
                    AND " . TBL_album . ".`album_name` LIKE ?)
                GROUP BY " . TBL_artists . ".`album_id`
                ORDER BY 
                  `artist_relevance` ASC,
                  " . TBL_album . ".`year` DESC,
                  `album_relevance` ASC,
                  " . TBL_artist . ".`artist_name` ASC,
                  " . TBL_album . ".`album_name` ASC
                LIMIT 0, 20";
      }
      else {
        $search_str_db_artist = $search_str_db_album = trim($search_str) . '%';
        $search_str_db_artist_wc = $search_str_db_album_wc = '%' . trim($search_str) . '%';
        $sql = "SELECT " . TBL_artists . ".`artist_id`,
                       " . TBL_artists . ".`album_id`,
                       " . TBL_artist . ".`artist_name`,
                       " . TBL_album . ".`album_name`,
                       " . TBL_album . ".`year`,
                       (CASE WHEN " . TBL_artist . ".`artist_name` LIKE ? THEN 0 ELSE 1 END) AS `artist_relevance`,
                       (CASE WHEN " . TBL_album . ".`album_name` LIKE ? THEN 0 ELSE 1 END) AS `album_relevance`
                FROM " . TBL_album . ",
                     " . TBL_artist . ",
                     (SELECT " . TBL_artists . ".`artist_id`,
                             " . TBL_artists . ".`album_id`
                      FROM " . TBL_artists . ") AS " . TBL_artists . "
                WHERE " . TBL_artists . ".`album_id` = " . TBL_album . ".`id`
                  AND " . TBL_artists . ".`artist_id` = " . TBL_artist . ".`id`
                  AND (" . TBL_artist . ".`artist_name` LIKE ?
                    OR " . TBL_album . ".`album_name` LIKE ?)
                GROUP BY " . TBL_artists . ".`album_id`
                ORDER BY 
                  `artist_relevance` ASC,
                  " . TBL_album . ".`year` DESC,
                  `album_relevance` ASC,
                  " . TBL_artist . ".`artist_name` ASC,
                  " . TBL_album . ".`album_name` ASC
                LIMIT 0, 20";
      }
      $query = $this->db->query($sql, array($search_str_db_artist, $search_str_db_album, $search_str_db_artist_wc, $search_str_db_album_wc));
      if ($query->num_rows() > 0) {
        if ($query->result()[0]->album_relevance > 0) {
          $results[] = array(
            'album_id' => FALSE,
            'artist_names' => FALSE,
            'img' => '',
            'label' => $query->result()[0]->artist_name . ' ' . DASH . ' ',
            'value' => $query->result()[0]->artist_name . ' ' . DASH . ' '
          );
        }
        foreach ($query->result() as $row) {
          $results[] = array(
            'album_id' => $row->album_id,
            'artist_ids' => implode(', ', array_map(function($artist) { return $artist['id'];}, getAlbumArtists((array)$row))) . ' ' . DASH . ' ' . $row->album_name . ' (' . $row->year . ')',
            'image_server_ip' => IMAGE_SERVER_IP,
            'image_server_protocol' => IMAGE_SERVER_PROTOCOL,
            'img' => str_replace(IMAGE_SERVER, '', getAlbumImg(array('album_id' => $row->album_id, 'size' => 64))),
            'label' => implode(', ', array_map(function($artist) { return $artist['artist_name'];}, getAlbumArtists((array)$row))) . ' ' . DASH . ' ' . $row->album_name . ' (' . $row->year . ')',
            'value' => implode(', ', array_map(function($artist) { return $artist['artist_name'];}, getAlbumArtists((array)$row))) . ' ' . DASH . ' ' . $row->album_name
          );
        }
      }
      else {
        if (!strpos($search_str, DASH)) {
          $results[] = array(
            'album_id' => FALSE,
            'artist_names' => FALSE,
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

  public function artist() {
    // Load helpers.
    $this->load->helper(array('img_helper'));
    
    $results = array();
    $search_str = trim($_GET['term']) . '%';
    if (!empty($search_str)) {
      $sql = "SELECT " . TBL_artist . ".`id`,
                     " . TBL_artist . ".`artist_name`
              FROM " . TBL_artist . "
              WHERE " . TBL_artist . ".`artist_name` LIKE ?
              ORDER BY " . TBL_artist . ".`artist_name`
              LIMIT 0, 20";
      $query = $this->db->query($sql, array($search_str));
      if ($query->num_rows() > 0) {
        foreach ($query->result() as $row) {
          $results[] = array(
            'id' => $row->id,
            'image_server_ip' => IMAGE_SERVER_IP,
            'image_server_protocol' => IMAGE_SERVER_PROTOCOL,
            'img' => str_replace(IMAGE_SERVER, '', getArtistImg(array('artist_id' => $row->id, 'size' => 64))),
            'label' => $row->artist_name,
            'value' => $row->artist_name
          );
        }
      }
      else {
        $results[] = array(
          'img' => '',
          'label' => 'No results',
          'value' => $search_str
        );
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
}
?>
