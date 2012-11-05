<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * Gets artist's similar artists from Last.fm
 *
 * @param array $opts.
 *          'artist'   => Artist name
 *
 * @return array Artist's similar artists.
 *
 */
if (!function_exists('getArtistsSimilar')) {
  function getArtistsSimilar($opts = array()) {
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : FALSE;
    $limit = !empty($opts['limit']) ? $opts['limit'] : 4;
    $format = !empty($opts['format']) ? $opts['format'] : 'json';
    if ($artist_name !== FALSE) {
      $data = array();
      $similar_artists = json_decode(file_get_contents('http://ws.audioscrobbler.com/2.0/?method=artist.getsimilar&artist=' . urlencode($artist_name) . '&api_key=' . LASTFM_API_KEY . '&format=' . $format . '&limit=' . $limit), TRUE);
      $similar_artists = $similar_artists['similarartists']['artist'];
      foreach ($similar_artists as $idx => $similar_artist) {
        if ($artist_info = getArtistInfo(array('artist_name' => $similar_artist['name']))) {
          $data[] = $artist_info;
        }
        else {
          $data[] = array('artist_id' => 0, 'artist_name' => $similar_artist['name']);
        }
      }
      return json_encode($data);  
    }
    return json_encode(array('error' => array('msg' => ERR_NO_ARTIST)));
  }
}

/**
 * Gets artist's events from Last.fm
 *
 * @param array $opts.
 *          'artist'   => Artist name
 *
 * @return array Artist's events.
 *
 */
if (!function_exists('getArtistsEvents')) {
  function getArtistsEvents($opts = array()) {
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : FALSE;
    $limit = !empty($opts['limit']) ? $opts['limit'] : 4;
    $format = !empty($opts['format']) ? $opts['format'] : 'json';
    if ($artist_name !== FALSE) {
      $data = array();
      return json_encode($data);  
    }
    return json_encode(array('error' => array('msg' => ERR_NO_ARTIST)));
  }
}

/**
 * Gets artist's biography from Last.fm
 *
 * @param array $opts.
 *          'artist'   => Artist name
 *
 * @return string Artist's bio.
 *
 */
if (!function_exists('getArtistsBio')) {
  function getArtistsBio($opts = array()) {
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : FALSE;
    $format = !empty($opts['format']) ? $opts['format'] : 'json';
    if ($artist_name !== FALSE) {
      $data = array();
      $artist_bio = json_decode(file_get_contents('http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist=' . urlencode($artist_name) . '&api_key=' . LASTFM_API_KEY . '&format=' . $format), TRUE);
      $data['bio_summary'] = $artist_bio['artist']['bio']['summary']; 
      $data['bio_content'] = $artist_bio['artist']['bio']['content'];
      return json_encode($data); 
    }
    return json_encode(array('error' => array('msg' => ERR_NO_ARTIST)));
  }
}
?>