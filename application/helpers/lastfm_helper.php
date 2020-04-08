<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Gets album's info from Last.fm
  *
  * @param array $opts.
  *          'album_id'         => Album ID
  *          'format'           => Format
  *
  * @return array Album's bio.
  *
  * http://www.last.fm/api/show/album.getInfo
  *
  */
if (!function_exists('fetchAlbumInfo')) {
  function fetchAlbumInfo($opts = array(), $get = array()) {
    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : FALSE;
    $album_name = isset($opts['album_name']) ? $opts['album_name'] : FALSE;
    $format = !empty($opts['format']) ? $opts['format'] : 'json';
    if ($artist_name !== FALSE && $album_name !== FALSE) {
      $data = array();
      if ($lastfm_data = json_decode(file_get_contents('http://ws.audioscrobbler.com/2.0/?method=album.getinfo&artist=' . urlencode($artist_name) . '&album=' . urlencode($album_name) . '&api_key=' . LASTFM_API_KEY . '&format=' . $format), TRUE)) {
        if (empty($lastfm_data['error'])) {
          $lastfm_data = $lastfm_data['album'];
          // Get biography.
          if (!empty($lastfm_data['wiki']) && in_array('bio', $get)) {
            $data['bio_summary'] = $lastfm_data['wiki']['summary']; 
            $data['bio_content'] = $lastfm_data['wiki']['content'];
          } 
          // Get image.
          if (!empty(end($lastfm_data['image'])['#text']) && in_array('image', $get)) {
            $url = explode('/', end($lastfm_data['image'])['#text']);
            $opts['image_uri'] = end($url);
            if (!empty($opts['image_uri'])) {
              $ci=& get_instance();
              $ci->load->helper(array('img_helper'));
              fetchImages($opts, 'album');
            }
          }
        }
      }
      return $data;
    }
    return array();
  }
}

/**
  * Gets artist's biography from Last.fm
  *
  * @param array $opts.
  *          'artist'           => Artist name
  *          'format'           => Format
  *
  * @return array Artist's bio.
  *
  * http://www.last.fm/api/show/artist.getInfo
  *
  */
if (!function_exists('fetchArtistInfo')) {
  function fetchArtistInfo($opts = array(), $get = array()) {
    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : FALSE;
    $format = !empty($opts['format']) ? $opts['format'] : 'json';
    if ($artist_name !== FALSE) {
      $data = array();
      if (in_array('bio', $get)) {
        if ($lastfm_data = json_decode(file_get_contents('http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist=' . urlencode($artist_name) . '&api_key=' . LASTFM_API_KEY . '&format=' . $format), TRUE)) {
          if (empty($lastfm_data['error'])) {
            $lastfm_data = $lastfm_data['artist'];
            // Get biography.
            if (!empty($lastfm_data['bio'])) {
              $data['bio_summary'] = $lastfm_data['bio']['summary']; 
              $data['bio_content'] = $lastfm_data['bio']['content'];
            }
          }
        }
      }
      if (in_array('image', $get)) {
        if ($napster_data = json_decode(file_get_contents('http://api.napster.com/v2.2/artists/' . urlencode(strtolower($artist_name)) . '?apikey='. NAPSTER_API_KEY), TRUE)) {
          if (!empty($napster_data['artists'])) {
            //Get image.
            if (!empty($napster_data['artists'][0]['id'])) {
              $ci=& get_instance();
              $ci->load->helper(array('img_helper'));
              $opts['image_uri'] = 'https://api.napster.com/imageserver/v2/artists/' . $napster_data['artists'][0]['id'] . '/images/633x422.jpg';
              fetchImages($opts, 'artist');
            }
          }
        }
      }
      return $data;
    }
    return array();
  }
}

/**
  * Gets tag's info from Last.fm
  *
  * @param array $opts.
  *          'tag_name'         => Artist name
  *          'format'           => Format
  *
  * @return string Tag's bio.
  *
  * http://www.last.fm/api/show/tag.getInfo
  *
  */
if (!function_exists('fetchTagBio')) {
  function fetchTagBio($opts = array()) {
    $tag_name = !empty($opts['tag_name']) ? $opts['tag_name'] : FALSE;
    $format = !empty($opts['format']) ? $opts['format'] : 'json';
    if ($tag_name !== FALSE) {
      $data = array();
      $lastfm_data = json_decode(file_get_contents('http://ws.audioscrobbler.com/2.0/?method=tag.getinfo&tag=' . urlencode($tag_name) . '&api_key=' . LASTFM_API_KEY . '&format=' . $format), TRUE);
      $data['bio_summary'] = $lastfm_data['tag']['wiki']['summary']; 
      $data['bio_content'] = $lastfm_data['tag']['wiki']['content'];
      return $data; 
    }
    return json_encode(array('error' => array('msg' => ERR_NO_TAG)));
  }
}

/**
  * Gets artist's similar artists from Last.fm
  *
  * @param array $opts.
  *          'artist_name'      => Artist name
  *          'format'           => Format
  *          'limit'            => Limit
  *
  * @return array Artist's similar artists.
  *
  * http://www.last.fm/api/show/artist.getSimilar
*/

if (!function_exists('fetchSimilar')) {
  function fetchSimilar($opts = array()) {
    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : FALSE;
    $limit = !empty($opts['limit']) ? $opts['limit'] : 4;
    $format = !empty($opts['format']) ? $opts['format'] : 'json';
    if ($artist_name !== FALSE) {
      $data = array();
      $lastfm_data = json_decode(file_get_contents('http://ws.audioscrobbler.com/2.0/?method=artist.getsimilar&artist=' . urlencode($artist_name) . '&api_key=' . LASTFM_API_KEY . '&format=' . $format . '&limit=' . $limit), TRUE);
      if (!empty($lastfm_data['similarartists'])) {
        $similar_artists = $lastfm_data['similarartists']['artist'];
        foreach ($similar_artists as $idx => $similar_artist) {
          if ($artist_info = getArtistInfo(array('artist_name' => $similar_artist['name']))) {
            $data[] = $artist_info;
          }
          else {
            $data[] = array('artist_id' => 0, 'artist_name' => $similar_artist['name']);
          }
        }
      }
      else {
        return json_encode(array('error' => array('msg' => ERR_NO_DATA)));
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
 
if (!function_exists('getEvents')) {
  function getEvents($opts = array()) {
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : FALSE;
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
*/
?>