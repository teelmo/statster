<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

if (!function_exists('getSpotifyResourceId')) {
  function getSpotifyResourceId($data) {
    if (!empty($data['album_name'])) {
      $q = str_replace('%2F', '+', urlencode('artist:' . $data['artist_name'] . ' '  . 'album:' . substrwords($data['album_name'], 100, '')));
      $type = urlencode('album,track');
    }
    else {
      $q = str_replace('%2F', '+', urlencode('artist:' . $data['artist_name']));
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
    @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . json_decode($result)->access_token));
    $result = curl_exec($ch);
    curl_close($ch);

    if (!empty($data['album_name'])) {
      $spotify_id = (json_decode($result)->albums->total !== 0) ? json_decode($result)->albums->items[0]->id : FALSE;
    }
    else {
      $spotify_id = (json_decode($result) !== NULL && json_decode($result)->artists->total !== 0) ? json_decode($result)->artists->items[0]->id : FALSE;
    }
    if ($spotify_id !== FALSE) {
      addSpotifyResourceId($data, $spotify_id);
    }
    return $spotify_id;
  }
}

if (!function_exists('addSpotifyResourceId')) {
  function addSpotifyResourceId($data, $spotify_id) {
    $ci=& get_instance();
    $ci->load->database();

    if (!empty($data['album_name'])) {
      $sql = "UPDATE " . TBL_album . "
                SET " . TBL_album . ".`spotify_id` = ?
                WHERE " . TBL_album . ".`id` = ?";
      $query = $ci->db->query($sql, array($spotify_id, $data['album_id']));
    }
    else {
      $sql = "UPDATE " . TBL_artist . "
                SET " . TBL_artist . ".`spotify_id` = ?
                WHERE " . TBL_artist . ".`id` = ?";
      $query = $ci->db->query($sql, array($spotify_id, $data['artist_id']));
    }
  }
}
?>