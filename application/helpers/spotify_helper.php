<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

if (!function_exists('getSpotifyResourceId')) {
  function getSpotifyResourceId($data) {
    if (!empty($data['album_name'])) {
      $q = str_replace('%2F', '+', urlencode($data['artist_name'] . ' '  . $data['album_name']));
      $type = urlencode('album,track');
    }
    else {
      $q = str_replace('%2F', '+', urlencode($data['artist_name']));
      $type = urlencode('artist');
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials' ); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode(SPOTIFY_CLIENT_ID . ':' . SPOTIFY_CLIENT_SECRET)));
    $result = curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.spotify.com/v1/search?q=' . $q . '&type=' . $type . '&limit=1');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . json_decode($result)->access_token)); 
    $result = curl_exec($ch);
    curl_close($ch);

    // pr($result);
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