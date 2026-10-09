<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Gets the formats (from listening_format) that make sense to mark an
  * album as owned in - excludes Stream/Live, which describe how something
  * was listened to, not a format you can physically or digitally own a
  * copy of. Not Chosen is kept (it's also what the legacy pre-2026 rows
  * were backfilled to, so it needs to stay selectable) - ordered by id
  * rather than name so it sorts first, matching its id of 1.
  *
  * "File" is relabeled to "Digital" for display here only - this is the
  * shared listening_format taxonomy used elsewhere (format stats pages,
  * the add-listening dropdown), so the underlying name/routes are left
  * untouched; only what's shown in this picker changes.
  *
  * @return array Rows: id, name, img.
  */
if (!function_exists('getOwnableFormats')) {
  function getOwnableFormats() {
    $ci=& get_instance();
    $ci->load->database();

    $sql = "SELECT " . TBL_listening_format . ".`id`,
                   " . TBL_listening_format . ".`name`,
                   " . TBL_listening_format . ".`img`
            FROM " . TBL_listening_format . "
            WHERE " . TBL_listening_format . ".`name` NOT IN ('Stream', 'Live')
            ORDER BY " . TBL_listening_format . ".`id` ASC";
    $query = $ci->db->query($sql);
    $formats = $query->result_array();
    foreach ($formats as &$format) {
      if ($format['name'] === 'File') {
        $format['name'] = 'Digital';
      }
    }
    return $formats;
  }
}

/**
  * Tells which format(s) the given album is owned in by the given user.
  *
  * @param array $opts.
  *          'album_id'  => Album ID
  *          'user_id'   => User ID
  *
  * @return string JSON.
  */
if (!function_exists('getOwnedAlbum')) {
  function getOwnedAlbum($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : 0;
    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : 0;

    $sql = "SELECT " . TBL_user_album . ".`listening_format_id`
            FROM " . TBL_user_album . "
            WHERE " . TBL_user_album . ".`record_id` = ?
              AND " . TBL_user_album . ".`user_id` = ?";
    $query = $ci->db->query($sql, array($album_id, $user_id));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}

/**
  * Marks an album as owned, in a specific format, by the logged-in user.
  *
  * @param int $album_id Album ID.
  * @param int $format_id listening_format ID.
  *
  * @return string JSON.
  */
if (!function_exists('addOwnedAlbum')) {
  function addOwnedAlbum($album_id, $format_id) {
    $ci=& get_instance();
    $ci->load->database();

    // Get user id from session
    if (!$user_id = $ci->session->userdata('user_id')) {
      return header('HTTP/1.1 401 Unauthorized');
    }

    $sql = "INSERT
              INTO " . TBL_user_album . " (`user_id`, `record_id`, `listening_format_id`)
              VALUES (?, ?, ?)";
    $query = $ci->db->query($sql, array($user_id, $album_id, $format_id));
    if ($ci->db->affected_rows() === 1) {
      header('HTTP/1.1 201 Created');
      return json_encode(array('success' => array('msg' => $ci->db->insert_id())));
    }
    return header('HTTP/1.1 400 Bad Request');
  }
}

/**
  * Unmarks an album as owned in a specific format by the logged-in user.
  *
  * @param int $album_id Album ID.
  * @param int $format_id listening_format ID.
  *
  * @return string JSON.
  */
if (!function_exists('deleteOwnedAlbum')) {
  function deleteOwnedAlbum($album_id, $format_id) {
    $ci=& get_instance();
    $ci->load->database();

    // Get user id from session
    if (!$user_id = $ci->session->userdata('user_id')) {
      return header('HTTP/1.1 401 Unauthorized');
    }

    $ci->db->delete(TBL_user_album, array(
      'user_id' => $user_id,
      'record_id' => $album_id,
      'listening_format_id' => $format_id
    ));
    if ($ci->db->affected_rows() === 1) {
      return header('HTTP/1.1 204 No Content');
    }
    return header('HTTP/1.1 400 Bad Request');
  }
}

/**
  * Gets which format(s) a batch of albums are owned in by the given user,
  * in a single query - for rendering owned-format icons across a list of
  * album tiles without one query per tile.
  *
  * @param int   $user_id   User ID.
  * @param array $album_ids Album IDs.
  *
  * @return array Map of album_id => array of {id, name, icon} rows, icon
  *               being a resolved, existence-checked icon URL (formats
  *               whose icon file is missing are silently excluded, same
  *               precedent as getListeningImgsForListenings()).
  */
if (!function_exists('getOwnedFormatsForAlbums')) {
  function getOwnedFormatsForAlbums($user_id, $album_ids = array()) {
    $ci = &get_instance();
    $ci->load->database();

    $album_ids = array_unique(array_filter($album_ids));
    if (empty($user_id) || empty($album_ids)) {
      return array();
    }

    $placeholders = implode(',', array_fill(0, count($album_ids), '?'));
    $sql = "SELECT " . TBL_user_album . ".`record_id` AS `album_id`,
                   " . TBL_listening_format . ".`id`,
                   " . TBL_listening_format . ".`name`,
                   " . TBL_listening_format . ".`img`
            FROM " . TBL_user_album . ",
                 " . TBL_listening_format . "
            WHERE " . TBL_listening_format . ".`id` = " . TBL_user_album . ".`listening_format_id`
              AND " . TBL_user_album . ".`user_id` = ?
              AND " . TBL_user_album . ".`record_id` IN (" . $placeholders . ")";
    $query = $ci->db->query($sql, array_merge(array($user_id), array_values($album_ids)));

    $icon_exists = array();
    $resolve_icon = function($img) use (&$icon_exists) {
      if (!array_key_exists($img, $icon_exists)) {
        $icon_exists[$img] = file_exists('./media/img/format_img/format_icons/' . $img . '.png');
      }
      return $icon_exists[$img] ? site_url() . 'media/img/format_img/format_icons/' . $img . '.png' : FALSE;
    };

    $result = array();
    foreach ($query->result_array() as $row) {
      $icon = $resolve_icon($row['img']);
      if ($icon !== FALSE) {
        $result[$row['album_id']][] = array('id' => $row['id'], 'name' => $row['name'], 'icon' => $icon);
      }
    }

    return $result;
  }
}

/**
  * Gets every album a user owns, grouped by artist for a "my collection"
  * style listing page - an album with more than one artist is grouped
  * once, under its first (alphabetically) artist only, same as how
  * getAlbumsArtists() already orders artists for a single album's display.
  *
  * @param int $user_id User ID.
  *
  * @return array List of {artist_id, artist_name, albums}, albums being a
  *               list of {album_id, album_name, year, artists, formats},
  *               sorted by artist_name then album_name.
  */
if (!function_exists('getOwnedAlbumsForUser')) {
  function getOwnedAlbumsForUser($user_id) {
    $ci = &get_instance();
    $ci->load->database();

    if (empty($user_id)) {
      return array();
    }

    $album_id_query = $ci->db->query(
      "SELECT DISTINCT `record_id` AS `album_id` FROM " . TBL_user_album . " WHERE `user_id` = ?",
      array($user_id)
    );
    $album_ids = array_column($album_id_query->result_array(), 'album_id');
    if (empty($album_ids)) {
      return array();
    }

    $placeholders = implode(',', array_fill(0, count($album_ids), '?'));
    $album_query = $ci->db->query(
      "SELECT `id` AS `album_id`, `album_name`, `year` FROM " . TBL_album . " WHERE `id` IN (" . $placeholders . ")",
      array_values($album_ids)
    );

    $ci->load->helper('music_helper');
    $album_artists = getAlbumsArtists($album_ids);
    $owned_formats = getOwnedFormatsForAlbums($user_id, $album_ids);

    $groups = array();
    foreach ($album_query->result_array() as $album) {
      if (empty($album_artists[$album['album_id']])) {
        continue;
      }
      $artist = $album_artists[$album['album_id']][0];
      $album['artists'] = $album_artists[$album['album_id']];
      $album['formats'] = isset($owned_formats[$album['album_id']]) ? $owned_formats[$album['album_id']] : array();
      if (!isset($groups[$artist['id']])) {
        $groups[$artist['id']] = array('artist_id' => $artist['id'], 'artist_name' => $artist['artist_name'], 'albums' => array());
      }
      $groups[$artist['id']]['albums'][] = $album;
    }

    $groups = array_values($groups);
    usort($groups, function($a, $b) {
      return strcasecmp($a['artist_name'], $b['artist_name']);
    });
    foreach ($groups as &$group) {
      usort($group['albums'], function($a, $b) {
        return strcasecmp($a['album_name'], $b['album_name']);
      });
    }

    return $groups;
  }
}
?>