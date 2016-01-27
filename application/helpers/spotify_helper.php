<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

if (!function_exists('getSpotifyResourceId')) {
  function getSpotifyResourceId($artist, $album) {
    $curl = curl_init();
    if ($album) {
      $q = urlencode($album);
      $type = 'album';
    }
    else {
      $q = urlencode($artist);
      $type = 'artist';
    }
    curl_setopt($curl, CURLOPT_URL, 'https://api.spotify.com/v1/search?q=' . $q . '&type=' . $type . '&limit=1');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    if ($album) {
      return (json_decode($result)->albums->total !== 0) ? json_decode($result)->albums->items[0]->uri : FALSE; 
    }
    else {
      return (json_decode($result)->artists->total !== 0) ? json_decode($result)->artists->items[0]->uri : FALSE; 
    }
  }
}
?>