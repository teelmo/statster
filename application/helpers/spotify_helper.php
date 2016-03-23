<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

if (!function_exists('getSpotifyResourceId')) {
  function getSpotifyResourceId($data) {
    $curl = curl_init();
    if (!empty($data['album_name'])) {
      $q = str_replace('%2F', '+', urlencode($data['artist_name'] . ' '  . $data['album_name']));
      $type = urlencode('album,track');
    }
    else {
      $q = str_replace('%2F', '+', urlencode($data['artist_name']));
      $type = urlencode('artist');
    }
    curl_setopt($curl, CURLOPT_URL, 'https://api.spotify.com/v1/search?q=' . $q . '&type=' . $type . '&limit=1');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    if (!empty($data['album_name'])) {
      $spotify_uri = (json_decode($result)->albums->total !== 0) ? json_decode($result)->albums->items[0]->uri : FALSE; 
    }
    else {
      $spotify_uri = (json_decode($result)->artists->total !== 0) ? json_decode($result)->artists->items[0]->uri : FALSE;
    }
    if ($spotify_uri !== FALSE) {
      addSpotifyResourceId($data, $spotify_uri);
    }
    return $spotify_uri;
  }
}

if (!function_exists('addSpotifyResourceId')) {
  function addSpotifyResourceId($data, $spotify_uri) {
    $ci=& get_instance();
    $ci->load->database();

    if (!empty($data['album_name'])) {
      $sql = "UPDATE " . TBL_album . "
                SET " . TBL_album . ".`spotify_uri` = ?
                WHERE " . TBL_album . ".`id` = ?";
      $query = $ci->db->query($sql, array($spotify_uri, $data['album_id']));
    }
    else {
      $sql = "UPDATE " . TBL_artist . "
                SET " . TBL_artist . ".`spotify_uri` = ?
                WHERE " . TBL_artist . ".`id` = ?";
      $query = $ci->db->query($sql, array($spotify_uri, $data['artist_id']));
    }
  }
}
?>