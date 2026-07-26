<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Get path to album's art.
  *
  * @param array $opts.
  *          'size'      => Desired image size
  *          'album_id'  => Album ID
  *
  * @return string Absolute path to image file.
  */
if (!function_exists('getAlbumImg')) {
  function getAlbumImg($opts = array()) {
    $opts['id'] = $opts['album_id'];
    $filename = getImagePath($opts, 'album');
    if (empty($filename)) {
      // $ci=& get_instance();
      // $ci->load->helper('metadata_helper');
      // $data = fetchAlbumInfo($opts, array('image'));
      // if (!empty($data['image_uri'])) {
      //   return $data['image_uri'];
      // }
      return IMAGE_SERVER . 'album_img/' . $opts['size'] . '/0.jpg';
    }
    else {
      return $filename;
    }
  }
}

/**
  * Get path to artist's art.
  *
  * @param array $opts.
  *          'size'        => Desired image size
  *          'artist_id'   => Artist ID
  *
  * @return string Absolute path to image file.
  */
if (!function_exists('getArtistImg')) {
  function getArtistImg($opts = array()) {
    $opts['id'] = $opts['artist_id'];
    $filename = getImagePath($opts, 'artist');
    if (empty($filename)) {
      // $ci=& get_instance();
      // $ci->load->helper('metadata_helper');
      // $data = fetchArtistInfo($opts, array('image'));
      // if ($data['image_uri'] !== '') {
      //   return $data['image_uri'];
      // }
      return IMAGE_SERVER . 'artist_img/' . $opts['size'] . '/0.jpg';
    }
    else {
      return $filename;
    }
  }
}

/**
  * Get path to user's profile image.
  *
  * @param array $opts.
  *          'size'     => Desired image size
  *          'user_id'  => User ID
  *
  * @return string Absolute path to image file.
  */
if (!function_exists('getUserImg')) {
  function getUserImg($opts = array()) {
    $opts['id'] = $opts['user_id'];
    $filename = getImagePath($opts, 'user');
    if (empty($filename)) {
      return IMAGE_SERVER . 'user_img/' . $opts['size'] . '/0.jpg';
    }
    else {
      return $filename;
    }
  }
}

/**
  * Collection function for getting the listening's
  * format or format type information
  *
  * @param array $opts.
  *          'listening_id'  => Listening ID
  *
  * @return string Absolute path to image file.
  */
if (!function_exists('getListeningImg')) {
  function getListeningImg($opts = array()) {
    $format_img = getListeningFormatImg($opts);
    $format_type_img = getListeningFormatTypeImg($opts);
    if ($format_type_img != FALSE) {
      return $format_type_img;
    }
    elseif ($format_img != FALSE) {
      return $format_img;
    }
    else {
      return array('filename' => site_url() . '/media/img/format_img/format_icons/empty.png', 'name' => '');
    }
  }
}

/**
  * Batch version of getListeningImg() for rendering a list of listenings -
  * two queries total instead of up to four (format + format type) per row.
  *
  * @param array $listening_ids.
  *
  * @return array Map of listening_id => array('filename' => .., 'name' => ..).
  */
if (!function_exists('getListeningImgsForListenings')) {
  function getListeningImgsForListenings($listening_ids = array()) {
    $ci = &get_instance();
    $ci->load->database();

    $listening_ids = array_unique(array_filter($listening_ids));
    if (empty($listening_ids)) {
      return array();
    }

    $placeholders = implode(',', array_fill(0, count($listening_ids), '?'));

    $format_sql = "SELECT " . TBL_listening_formats . ".`listening_id`,
                          " . TBL_listening_format . ".`img`,
                          " . TBL_listening_format . ".`name`
                   FROM " . TBL_listening_format . ",
                        " . TBL_listening_formats . "
                   WHERE " . TBL_listening_format . ".`id` = " . TBL_listening_formats . ".`listening_format_id`
                     AND " . TBL_listening_formats . ".`listening_id` IN (" . $placeholders . ")";
    $format_query = $ci->db->query($format_sql, array_values($listening_ids));

    $format_type_sql = "SELECT " . TBL_listening_format_types . ".`listening_id`,
                                " . TBL_listening_format_type . ".`img`,
                                " . TBL_listening_format_type . ".`name`
                         FROM " . TBL_listening_format_type . ",
                              " . TBL_listening_format_types . "
                         WHERE " . TBL_listening_format_type . ".`id` = " . TBL_listening_format_types . ".`listening_format_type_id`
                           AND " . TBL_listening_format_types . ".`listening_id` IN (" . $placeholders . ")";
    $format_type_query = $ci->db->query($format_type_sql, array_values($listening_ids));

    $ci->load->helper('file');
    $icon_exists = array();
    $resolve_icon = function($img, $name) use (&$icon_exists) {
      if (!array_key_exists($img, $icon_exists)) {
        $icon_exists[$img] = file_exists('./media/img/format_img/format_icons/' . $img . '.png');
      }
      return $icon_exists[$img] ? array('filename' => site_url() . 'media/img/format_img/format_icons/' . $img . '.png', 'name' => $name) : FALSE;
    };

    $result = array();
    // Format first, then let format type overwrite - format type takes
    // priority, matching getListeningImg()'s precedence.
    foreach ($format_query->result_array() as $row) {
      $icon = $resolve_icon($row['img'], $row['name']);
      if ($icon !== FALSE) {
        $result[$row['listening_id']] = $icon;
      }
    }
    foreach ($format_type_query->result_array() as $row) {
      $icon = $resolve_icon($row['img'], $row['name']);
      if ($icon !== FALSE) {
        $result[$row['listening_id']] = $icon;
      }
    }

    return $result;
  }
}

/**
  * Get listening's format image.
  *
  * @param array $opts.
  *          'listening_id'  => Listening ID
  *
  * @return string Absolute path to image file.
  */
if (!function_exists('getListeningFormatImg')) {
  function getListeningFormatImg($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $listening_id = isset($opts['listening_id']) ? $opts['listening_id'] : '';
    $sql = "SELECT " . TBL_listening_format . ".`img`, " . TBL_listening_format . ".`name`
            FROM " . TBL_listening_format . ", " . TBL_listening_formats . ", " . TBL_listening . "
            WHERE " . TBL_listening_format . ".`id` = " . TBL_listening_formats . ".`listening_format_id`
              AND " . TBL_listening . ".`id` = " . TBL_listening_formats . ".`listening_id`
              AND " . TBL_listening . ".`id` = ?";
    $query = $ci->db->query($sql, array($listening_id));
    if ($query->num_rows() > 0) {
      $result = $query->result();
      $ci->load->helper('file');
      $filename = 'media/img/format_img/format_icons/' . $result[0]->img . '.png';
      return (file_exists('./' . $filename)) ? array('filename' => site_url() . $filename, 'name' => $result[0]->name, 'empty' => FALSE) : FALSE;
    }
    else {
      return FALSE;
    }
  }
}

/**
  * Get listening's format type image.
  *
  * @param array $opts.
  *          'listening_id'  => Listening ID
  *
  * @return string Absolute path to image file.
  */
if (!function_exists('getListeningFormatTypeImg')) {
  function getListeningFormatTypeImg($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $listening_id = isset($opts['listening_id']) ? $opts['listening_id'] : '';
    $sql = "SELECT " . TBL_listening_format_type . ".`img`, " . TBL_listening_format_type . ".`name`
            FROM " . TBL_listening_format_type . ", " . TBL_listening_format_types . ", " . TBL_listening . "
            WHERE " . TBL_listening_format_type . ".`id` = " . TBL_listening_format_types . ".`listening_format_type_id`
              AND " . TBL_listening . ".`id` = " . TBL_listening_format_types . ".`listening_id`
              AND " . TBL_listening . ".`id` = ?";
    $query = $ci->db->query($sql, array($listening_id));
    if ($query->num_rows() > 0) {
      $result = $query->result();
      $ci->load->helper('file');
      $filename = 'media/img/format_img/format_icons/' . $result[0]->img . '.png';
      return (file_exists('./' . $filename)) ? array('filename' => site_url() . $filename, 'name' => $result[0]->name, 'empty' => FALSE) : FALSE;
    }
    else {
      return FALSE;
    }
  }
}

/**
  * Get format's ID.
  *
  * @param array $opts.
  *          'format'  => Format name
  *
  * @return int Format ID or boolean FALSE.
  */
if (!function_exists('getFormatImg')) {
  function getFormatImg($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $format = isset($opts['format']) ? $opts['format'] : '';
    $sql = "SELECT " . TBL_listening_format . ".`img`
            FROM " . TBL_listening_format . "
            WHERE " . TBL_listening_format . ".`name` = ?
            LIMIT 1";
    $query = $ci->db->query($sql, array($format));
    return ($query->num_rows() > 0) ? $query->result()[0]->img : FALSE;
  }
}

/**
  * Get format type's ID.
  *
  * @param array $opts.
  *          'format_type'  => Format type name
  *
  * @return int Format type ID or boolean FALSE.
  */
if (!function_exists('getFormatTypeImg')) {
  function getFormatTypeImg($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $format_type = isset($opts['format_type']) ? $opts['format_type'] : '';
    $sql = "SELECT " . TBL_listening_format_type . ".`img`
            FROM " . TBL_listening_format_type . "
            WHERE " . TBL_listening_format_type . ".`name` = ?
            LIMIT 1";
    $query = $ci->db->query($sql, array($format_type));
    return ($query->num_rows() > 0) ? $query->result()[0]->img : FALSE;
  }
}

/**
  * Shared cache backing getImagePath()/prefetchImagePaths(), keyed by
  * "type:size:id". A plain function-static array can't be shared across
  * two different functions, so both reach it through this reference.
  */
if (!function_exists('_imagePathCache')) {
  function &_imagePathCache() {
    static $cache = array();
    return $cache;
  }
}

if (!function_exists('getImagePath')) {
  function getImagePath($opts, $type) {
    if (ENVIRONMENT === 'production' or ENVIRONMENT === 'development') {
      // Same (type, size, id) is looked up repeatedly rendering a listing
      // (e.g. one user's avatar on every row of their own listening
      // history, or a replayed album's cover on every re-listen) - each
      // lookup is a real network round-trip to IMAGE_SERVER, so memoize
      // per request rather than refetching an identical URL. Rows that
      // don't repeat still benefit if prefetchImagePaths() warmed this
      // same cache with a parallel batch beforehand.
      $cache = &_imagePathCache();
      $key = $type . ':' . $opts['size'] . ':' . $opts['id'];
      if (!array_key_exists($key, $cache)) {
        // trim() guards against IMAGE_SERVER leaking stray whitespace (e.g. a
        // trailing newline after its closing PHP tag) into the URL - a
        // background-image: url(...) containing a raw newline is silently
        // rejected by the browser, so the image never even loads instead of
        // just looking odd.
        // A stream timeout matches prefetchImagePaths()'s curl timeout - with
        // none set here, an unresponsive IMAGE_SERVER would hang this request
        // for PHP's default_socket_timeout (60s) instead of failing fast.
        $context = stream_context_create(array('http' => array('timeout' => 3)));
        $cache[$key] = trim((string) @file_get_contents(IMAGE_SERVER . 'getImage.php?size=' . $opts['size'] . '&type=' . $type . '&id=' . $opts['id'], FALSE, $context));
      }
      return $cache[$key];
    }
    else {
      // If you want to use local files.
      $ci=& get_instance();
      $ci->load->helper('file');
      $filename = 'media/img/' . $type . '_img/' . $opts['size'] . '/' . $opts['id'] . '.jpg';
      if (file_exists('./' . $filename)) {
        return site_url() . $filename;
      }
      else {
        return site_url() . 'media/img/' . $type . '_img/' . $opts['size'] . '/0.jpg';
      }
    }
  }
}

/**
  * Warms getImagePath()'s cache for a batch of images in parallel, instead
  * of leaving each getAlbumImg()/getArtistImg()/getUserImg() call in a
  * rendering loop to block on its own sequential IMAGE_SERVER round-trip.
  * Memoization alone (getImagePath's cache) only helps when the same image
  * repeats within a request - a listing spanning many different users/
  * albums has few repeats, so most lookups still need a real fetch; doing
  * those concurrently is what actually cuts the wall-clock time down.
  *
  * @param array $requests. Each item: array('type' => .., 'size' => .., 'id' => ..).
  */
if (!function_exists('prefetchImagePaths')) {
  function prefetchImagePaths($requests = array()) {
    if (!(ENVIRONMENT === 'production' or ENVIRONMENT === 'development')) {
      return;
    }

    $cache = &_imagePathCache();
    $unique = array();
    foreach ($requests as $request) {
      if (empty($request['type']) || empty($request['size']) || !isset($request['id'])) {
        continue;
      }
      $key = $request['type'] . ':' . $request['size'] . ':' . $request['id'];
      if (!array_key_exists($key, $cache)) {
        $unique[$key] = $request;
      }
    }
    if (empty($unique)) {
      return;
    }

    // IMAGE_SERVER appears to rate-limit bursts above ~10 simultaneous
    // connections from one IP (observed: batches of 12+ intermittently
    // jump from ~0.1s to 1-3s, batches of 10 or fewer stay fast) - cap
    // concurrency per chunk instead of firing every request at once.
    $chunk_size = 8;
    foreach (array_chunk($unique, $chunk_size, TRUE) as $chunk) {
      $multi = curl_multi_init();
      $handles = array();
      foreach ($chunk as $key => $request) {
        $url = IMAGE_SERVER . 'getImage.php?size=' . $request['size'] . '&type=' . $request['type'] . '&id=' . $request['id'];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_multi_add_handle($multi, $ch);
        $handles[$key] = $ch;
      }

      $running = NULL;
      do {
        curl_multi_exec($multi, $running);
        curl_multi_select($multi);
      } while ($running > 0);

      foreach ($handles as $key => $ch) {
        $cache[$key] = trim((string) curl_multi_getcontent($ch));
        curl_multi_remove_handle($multi, $ch);
      }
      curl_multi_close($multi);
    }
  }
}

if (!function_exists('fetchImages')) {
  function fetchImages($opts, $type) {
    if (ENVIRONMENT === 'production') {
      if ($type === 'album') {
        $data = array(
          'id' => $opts['album_id'],
          'type' => 'album',
          'uri' => $opts['image_uri']
        );
      }
      else if ($type === 'artist') {
        $data = array(
          'id' => $opts['artist_id'],
          'type' => 'artist',
          'uri' => $opts['image_uri']
        );
      }
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, IMAGE_SERVER . 'addImage.php');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      $result = curl_exec($ch);
      curl_close($ch);
      return $result;
    }
  }
}
?>
