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
if (!function_exists('getSimilar')) {
  function getSimilar($opts = array()) {
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : FALSE;
    $limit = !empty($opts['limit']) ? $opts['limit'] : 4;
    $format = !empty($opts['format']) ? $opts['format'] : 'json';
    if ($artist_name !== FALSE) {
      $data = array();
      $lastfm_data = json_decode(file_get_contents('http://ws.audioscrobbler.com/2.0/?method=artist.getsimilar&artist=' . urlencode($artist_name) . '&api_key=' . LASTFM_API_KEY . '&format=' . $format . '&limit=' . $limit), TRUE);
      $similar_artists = $lastfm_data['similarartists']['artist'];
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
if (!function_exists('getEvents')) {
  function getEvents($opts = array()) {
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : FALSE;
    $limit = !empty($opts['limit']) ? $opts['limit'] : 8;
    $format = !empty($opts['format']) ? $opts['format'] : 'json';
    if ($artist_name !== FALSE) {
      $data = array();
      $lastfm_data = json_decode(@file_get_contents('http://ws.audioscrobbler.com/2.0/?method=artist.getevents&artist=' . urlencode($artist_name) . '&api_key=' . LASTFM_API_KEY . '&format=' . $format . '&limit=' . $limit), TRUE);
      if (!empty($lastfm_data['events']['event'])) {
        $events_tmp = $lastfm_data['events']['event'];
        if (empty($events_tmp[0])) {
          $events[0] = $events_tmp; 
        }
        else {
          $events = $events_tmp;
        }
        foreach ($events as $idx => $event) {
          $data[] = array('name' => $event['venue']['name'],
                          'city' => $event['venue']['location']['city'],
                          'country' => $event['venue']['location']['country'],
                          'lat' => $event['venue']['location']['geo:point']['geo:lat'],
                          'lng' => $event['venue']['location']['geo:point']['geo:long'],
                          'date' => $event['startDate'],
                          'url' => $event['url']);
        }
        return json_encode($data);
      }
      return json_encode(array('error' => array('msg' => ERR_NO_EVENT)));
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
if (!function_exists('getBio')) {
  function getBio($opts = array()) {
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : FALSE;
    $format = !empty($opts['format']) ? $opts['format'] : 'json';
    if ($artist_name !== FALSE) {
      $data = array();
      $lastfm_data = json_decode(file_get_contents('http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist=' . urlencode($artist_name) . '&api_key=' . LASTFM_API_KEY . '&format=' . $format), TRUE);
      $data['bio_summary'] = $lastfm_data['artist']['bio']['summary']; 
      $data['bio_content'] = $lastfm_data['artist']['bio']['content'];
      return json_encode($data); 
    }
    return json_encode(array('error' => array('msg' => ERR_NO_ARTIST)));
  }
}
?>