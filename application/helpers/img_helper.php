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

/**
  * Maps an image type to its persistent cache table/FK column. One table
  * per type (rather than a single polymorphic table with a `type` column)
  * so each can carry a real FK back to its parent (album/artist/user),
  * matching every other relationship in this schema - deleting a parent
  * row cascades and removes its cached path automatically.
  */
if (!function_exists('_imagePathCacheTables')) {
  function _imagePathCacheTables() {
    return array(
      'album' => array('table' => TBL_album_image_path_cache, 'column' => 'album_id'),
      'artist' => array('table' => TBL_artist_image_path_cache, 'column' => 'artist_id'),
      'user' => array('table' => TBL_user_image_path_cache, 'column' => 'user_id')
    );
  }
}

/**
  * Batched persistent-cache lookup for one type/size pair. No-ops (no
  * query) for a size outside IMAGE_PATH_CACHE_SIZES, an unknown type, or
  * an empty id list, so callers can call this unconditionally.
  *
  * @return array Map of id => path for whatever was found.
  */
if (!function_exists('_imagePathCacheFetch')) {
  function _imagePathCacheFetch($type, $size, $ids) {
    $tables = _imagePathCacheTables();
    $ids = array_values(array_unique(array_filter($ids, function($id) { return $id !== NULL && $id !== ''; })));
    if (empty($ids) || !in_array($size, IMAGE_PATH_CACHE_SIZES, TRUE) || !isset($tables[$type])) {
      return array();
    }
    $table = $tables[$type]['table'];
    $column = $tables[$type]['column'];

    $ci=& get_instance();
    $ci->load->database();
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sql = "SELECT `" . $column . "`, `path` FROM " . $table . "
            WHERE `size` = ? AND `" . $column . "` IN (" . $placeholders . ")";
    $query = $ci->db->query($sql, array_merge(array($size), $ids));

    $result = array();
    foreach ($query->result() as $row) {
      $result[$row->$column] = $row->path;
    }
    return $result;
  }
}

/**
  * Batched persistent-cache write. Misses (empty path) and sizes outside
  * IMAGE_PATH_CACHE_SIZES are filtered out - a miss must never be
  * persisted, since a later upload for that id should still be picked up
  * on the next lookup - so callers can pass every resolved row
  * unconditionally.
  *
  * @param array $rows. Each item: array('type' => .., 'size' => .., 'id' => .., 'path' => ..).
  */
if (!function_exists('_imagePathCacheStore')) {
  function _imagePathCacheStore($rows) {
    $tables = _imagePathCacheTables();
    $by_type = array();
    foreach ($rows as $row) {
      if ($row['path'] === '' || !in_array($row['size'], IMAGE_PATH_CACHE_SIZES, TRUE) || !isset($tables[$row['type']])) {
        continue;
      }
      $by_type[$row['type']][] = $row;
    }
    if (empty($by_type)) {
      return;
    }

    $ci=& get_instance();
    $ci->load->database();
    foreach ($by_type as $type => $type_rows) {
      $table = $tables[$type]['table'];
      $column = $tables[$type]['column'];
      $value_sql = implode(',', array_fill(0, count($type_rows), '(?,?,?)'));
      $params = array();
      foreach ($type_rows as $row) {
        array_push($params, $row['id'], $row['size'], $row['path']);
      }
      $sql = "INSERT INTO " . $table . " (`" . $column . "`, `size`, `path`)
              VALUES " . $value_sql . "
              ON DUPLICATE KEY UPDATE `path` = VALUES(`path`)";
      $ci->db->query($sql, $params);
    }
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
        // Persistent cache (album/artist/user_image_path_cache) skips the
        // network round-trip entirely for anything already resolved before -
        // see _imagePathCacheFetch()'s doc comment. Only checked/populated
        // for sizes in IMAGE_PATH_CACHE_SIZES; every other size falls straight
        // through to the network call exactly as before.
        $persistent_hit = _imagePathCacheFetch($type, $opts['size'], array($opts['id']));
        if (array_key_exists($opts['id'], $persistent_hit)) {
          $cache[$key] = $persistent_hit[$opts['id']];
        }
        else {
          // trim() guards against IMAGE_SERVER leaking stray whitespace (e.g. a
          // trailing newline after its closing PHP tag) into the URL - a
          // background-image: url(...) containing a raw newline is silently
          // rejected by the browser, so the image never even loads instead of
          // just looking odd.
          // A stream timeout matches prefetchImagePaths()'s curl timeout - with
          // none set here, an unresponsive IMAGE_SERVER would hang this request
          // for PHP's default_socket_timeout (60s) instead of failing fast.
          $context = stream_context_create(array('http' => array('timeout' => 3)));
          $path = trim((string) @file_get_contents(IMAGE_SERVER . 'getImage.php?size=' . $opts['size'] . '&type=' . $type . '&id=' . $opts['id'], FALSE, $context));
          $cache[$key] = $path;
          _imagePathCacheStore(array(array('type' => $type, 'size' => $opts['size'], 'id' => $opts['id'], 'path' => $path)));
        }
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

    // Persistent cache pass, grouped by (type, size) so each pair present
    // needs at most one batched SELECT - see _imagePathCacheFetch(). Only
    // sizes in IMAGE_PATH_CACHE_SIZES are ever found here; everything else
    // falls through to the curl_multi burst below exactly as before.
    $by_type_size = array();
    foreach ($unique as $key => $request) {
      $by_type_size[$request['type']][$request['size']][] = $request['id'];
    }
    foreach ($by_type_size as $type => $sizes) {
      foreach ($sizes as $size => $ids) {
        foreach (_imagePathCacheFetch($type, $size, $ids) as $id => $path) {
          $cache[$type . ':' . $size . ':' . $id] = $path;
          unset($unique[$type . ':' . $size . ':' . $id]);
        }
      }
    }
    if (empty($unique)) {
      return;
    }

    // IMAGE_SERVER used to rate-limit bursts above ~10 simultaneous
    // connections from one IP (batches of 12+ intermittently jumping from
    // ~0.1s to 1-3s) under its old mod_php+prefork setup - fixed by
    // migrating it to php-fpm+mpm_event (2026-08-25, pool sized for 20
    // concurrent workers), confirmed live with a 12-wide burst completing
    // in 125ms. Still cap concurrency per chunk rather than firing every
    // request at once, since a page can need more images than that pool
    // has workers, and other requests share the same pool concurrently.
    $chunk_size = 16;
    // Per-handle CURLOPT_TIMEOUT only bounds a single chunk; if IMAGE_SERVER
    // ever degrades again (network issues, unrelated load), several chunks
    // hitting the full timeout back to back could still exceed PHP's
    // max_execution_time and fatal-error the whole request (this is what
    // happened in practice on add-listening search before the migration
    // above). Cap total wall-clock time across all chunks as a backstop -
    // once the budget's spent, stop prefetching and let the remaining rows
    // fall back to the default placeholder image (getImagePath() treats a
    // cached '' as "no image").
    $deadline = microtime(TRUE) + 5;
    $to_persist = array();
    foreach (array_chunk($unique, $chunk_size, TRUE) as $chunk) {
      if (microtime(TRUE) >= $deadline) {
        foreach ($chunk as $key => $request) {
          $cache[$key] = '';
        }
        continue;
      }
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
        $path = trim((string) curl_multi_getcontent($ch));
        $cache[$key] = $path;
        $to_persist[] = array('type' => $chunk[$key]['type'], 'size' => $chunk[$key]['size'], 'id' => $chunk[$key]['id'], 'path' => $path);
        curl_multi_remove_handle($multi, $ch);
      }
      curl_multi_close($multi);
    }
    _imagePathCacheStore($to_persist);
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
      if (curl_errno($ch)) {
        log_message('error', 'Image upload to ' . IMAGE_SERVER . 'addImage.php failed: ' . curl_error($ch));
        curl_close($ch);
        return FALSE;
      }
      curl_close($ch);
      return $result;
    }
  }
}
?>
