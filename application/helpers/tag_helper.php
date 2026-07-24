<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Per-tag-type table/column config, shared by every function below.
  * 'genre' and 'keyword' are structurally identical. 'nationality' differs:
  * its name column is `country` (not `name`), it has no standalone
  * add-tag function (nationalities are a fixed reference list, not
  * user-created), it has an extra `unknown_id` cleanup on tagging, and
  * an extra `country_code` column surfaced by getRelatedTags().
  */
if (!function_exists('_tagTypeConfig')) {
  function _tagTypeConfig($type) {
    $config = array(
      'genre' => array(
        'entity_table' => TBL_genre,
        'junction_table' => TBL_genres,
        'junction_id_column' => 'genre_id',
        'biography_table' => TBL_genre_biography,
        'name_column' => 'name',
        'has_standalone_add' => TRUE,
        'unknown_id' => NULL,
      ),
      'keyword' => array(
        'entity_table' => TBL_keyword,
        'junction_table' => TBL_keywords,
        'junction_id_column' => 'keyword_id',
        'biography_table' => TBL_keyword_biography,
        'name_column' => 'name',
        'has_standalone_add' => TRUE,
        'unknown_id' => NULL,
      ),
      'nationality' => array(
        'entity_table' => TBL_nationality,
        'junction_table' => TBL_nationalities,
        'junction_id_column' => 'nationality_id',
        'biography_table' => TBL_nationality_biography,
        'name_column' => 'country',
        'has_standalone_add' => FALSE,
        'unknown_id' => '242',
      ),
    );
    return $config[$type];
  }
}

/**
  * Returns top tags of the given type for the given user.
  *
  * @param string $type 'genre', 'keyword', or 'nationality'.
  * @param array $opts.
  *          'album_name'      => Album name
  *          'artist_name'     => Artist name
  *          'group_by'        => Group by argument
  *          'no_content'  => Output format
  *          'limit'           => Limit
  *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
  *          'order_by'        => Order by argument
  *          'tag_id'          => Tag id
  *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
  *          'username'        => Username
  *
  * @return string JSON encoded the data
  */
if (!function_exists('getTags')) {
  function getTags($type, $opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $t = _tagTypeConfig($type);

    $album_name = isset($opts['album_name']) ? $opts['album_name'] : '%';
    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : '%';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : $t['entity_table'] . '.`id`';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC';
    $select = !empty($opts['select']) ? ', ' . $opts['select'] : '';
    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '%';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT count(*) AS `count`,
                   '" . $type . "' AS `type`,
                   " . $t['entity_table'] . ".`" . $t['name_column'] . "` AS `name`,
                   " . $t['entity_table'] . ".`id` AS `tag_id`
                   " . $ci->db->escape_str($select) . "
            FROM " . TBL_album . ",
                 " . TBL_artist . ",
                 " . TBL_listening . ",
                 " . TBL_user . ",
                 " . $t['entity_table'] . ",
                 (SELECT " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "`,
                         " . $t['junction_table'] . ".`album_id`
                  FROM " . $t['junction_table'] . "
                  GROUP BY " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "`, " . $t['junction_table'] . ".`album_id`) AS " . $t['junction_table'] . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_album . ".`id` = " . $t['junction_table'] . ".`album_id`
              AND " . $t['entity_table'] . ".`id` = " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_artist . ".`artist_name` LIKE ?
              AND " . TBL_album . ".`album_name` LIKE ?
              AND " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "` LIKE ?
              AND " . TBL_user . ".`username` LIKE ?
              " . $ci->db->escape_str($where) . "
            GROUP BY " . $ci->db->escape_str($group_by) . "
            ORDER BY " . $ci->db->escape_str($order_by) . "
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $artist_name, $album_name, $tag_id, $username));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}
if (!function_exists('getGenres')) { function getGenres($opts = array()) { return getTags('genre', $opts); } }
if (!function_exists('getKeywords')) { function getKeywords($opts = array()) { return getTags('keyword', $opts); } }
if (!function_exists('getNationalities')) { function getNationalities($opts = array()) { return getTags('nationality', $opts); } }

/**
  * Returns all tags of the given type.
  *
  * @param string $type 'genre', 'keyword', or 'nationality'.
  * @param array $opts.
  *          'no_content'  => Output format
  *
  * @return string JSON encoded the data
  */
if (!function_exists('getAllTags')) {
  function getAllTags($type, $opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $t = _tagTypeConfig($type);

    $sql = "SELECT '" . $type . "' AS `type`,
                   " . $t['entity_table'] . ".`" . $t['name_column'] . "` AS `name`,
                   " . $t['entity_table'] . ".`id` AS `tag_id`
            FROM " . $t['entity_table'] . "
            WHERE 1
            ORDER BY " . $t['entity_table'] . ".`" . $t['name_column'] . "`";
    $query = $ci->db->query($sql, array());

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}
if (!function_exists('getAllGenres')) { function getAllGenres($opts = array()) { return getAllTags('genre', $opts); } }
if (!function_exists('getAllKeywords')) { function getAllKeywords($opts = array()) { return getAllTags('keyword', $opts); } }
if (!function_exists('getAllNationalities')) { function getAllNationalities($opts = array()) { return getAllTags('nationality', $opts); } }

/**
  * Returns cumulative listeners for the given tag.
  *
  * @param string $type 'genre', 'keyword', or 'nationality'.
  * @param array $opts.
  *          'tag_id'          => Tag ID
  *          'username'        => Username
  *
  * @return string JSON encoded data containing album information.
  */
if (!function_exists('getTagsCumulative')) {
  function getTagsCumulative($type, $opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $t = _tagTypeConfig($type);

    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '%';
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT `line_date`,
                   SUM(`month_count`) OVER (ORDER BY `line_date` ASC) AS `cumulative_count`
            FROM (
              SELECT DATE_FORMAT(" . TBL_listening . ".`date`, '%Y%m') AS `line_date`,
                     COUNT(*) AS `month_count`
              FROM " . TBL_listening . ",
                   " . TBL_user . ",
                   " . TBL_album . ",
                   " . $t['entity_table'] . ",
                   (SELECT " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "`,
                           " . $t['junction_table'] . ".`album_id`
                    FROM " . $t['junction_table'] . "
                    GROUP BY " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "`, " . $t['junction_table'] . ".`album_id`) AS " . $t['junction_table'] . "
              WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
                AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
                AND " . TBL_album . ".`id` = " . $t['junction_table'] . ".`album_id`
                AND " . $t['entity_table'] . ".`id` = " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "`
                AND " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "` LIKE ?
                AND " . TBL_user . ".`username` LIKE ?
                AND MONTH(" . TBL_listening . ".`date`) <> 0
              GROUP BY `line_date`
            ) AS `monthly`
            ORDER BY `line_date` ASC";
    $query = $ci->db->query($sql, array($tag_id, $username));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}
if (!function_exists('getGenresCumulative')) { function getGenresCumulative($opts = array()) { return getTagsCumulative('genre', $opts); } }
if (!function_exists('getKeywordsCumulative')) { function getKeywordsCumulative($opts = array()) { return getTagsCumulative('keyword', $opts); } }
if (!function_exists('getNationalitiesCumulative')) { function getNationalitiesCumulative($opts = array()) { return getTagsCumulative('nationality', $opts); } }

/**
  * Gets a tag's bio.
  *
  * @param string $type 'genre', 'keyword', or 'nationality'.
  * @param array $opts.
  *          'tag_id'  => Tag ID
  *
  * @return array Tag bio
  */
if (!function_exists('getTagBio')) {
  function getTagBio($type, $opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $t = _tagTypeConfig($type);

    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    $sql = "SELECT " . $t['biography_table'] . ".`id` AS `biography_id`,
                   " . $t['biography_table'] . ".`summary` AS `bio_summary`,
                   " . $t['biography_table'] . ".`text` AS `bio_content`,
                   " . $t['biography_table'] . ".`updated` AS `bio_updated`,
                   'false' AS `update_bio`
            FROM " . $t['biography_table'] . "
            WHERE " . $t['biography_table'] . ".`" . $t['junction_id_column'] . "` = ?";
    $query = $ci->db->query($sql, array($tag_id));
    return ($query->num_rows() > 0) ? $query->result_array()[0] : array('update_bio' => false);
  }
}
if (!function_exists('getGenreBio')) { function getGenreBio($opts = array()) { return getTagBio('genre', $opts); } }
if (!function_exists('getKeywordBio')) { function getKeywordBio($opts = array()) { return getTagBio('keyword', $opts); } }
if (!function_exists('getNationalityBio')) { function getNationalityBio($opts = array()) { return getTagBio('nationality', $opts); } }

/**
  * Add or update a tag's bio.
  *
  * @param string $type 'genre', 'keyword', or 'nationality'.
  * @param array $opts.
  *          'tag_id'       => Tag ID
  *          'bio_summary'  => Bio summary
  *          'bio_content'  => Bio content
  *
  * @return boolean TRUE or FALSE
  */
if (!function_exists('addTagBio')) {
  function addTagBio($type, $opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $t = _tagTypeConfig($type);

    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    $summary = !empty($opts['bio_summary']) ? $opts['bio_summary'] : '';
    $text = !empty($opts['bio_content']) ? $opts['bio_content'] : '';

    $sql = "SELECT  " . $t['biography_table'] . ".`id`
            FROM " . $t['biography_table'] . "
            WHERE " . $t['biography_table'] . ".`" . $t['junction_id_column'] . "` = ?";
    $query = $ci->db->query($sql, array($tag_id));
    if ($query->num_rows() === 1) {
      $sql = "UPDATE " . $t['biography_table'] . "
                SET " . $t['biography_table'] . ".`summary` = ?,
                    " . $t['biography_table'] . ".`text` = ?,
                    " . $t['biography_table'] . ".`updated` = NOW()
                WHERE " . $t['biography_table'] . ".`" . $t['junction_id_column'] . "` = ?";
      $query = $ci->db->query($sql, array($summary, $text, $tag_id));
    }
    else {
      $sql = "INSERT
                INTO " . $t['biography_table'] . " (`" . $t['junction_id_column'] . "`, `summary`, `text`)
                VALUES (?, ?, ?)";
      $query = $ci->db->query($sql, array($tag_id, $summary, $text));
    }
    return ($ci->db->affected_rows() === 1);
  }
}
if (!function_exists('addGenreBio')) { function addGenreBio($opts = array()) { return addTagBio('genre', $opts); } }
if (!function_exists('addKeywordBio')) { function addKeywordBio($opts = array()) { return addTagBio('keyword', $opts); } }
if (!function_exists('addNationalityBio')) { function addNationalityBio($opts = array()) { return addTagBio('nationality', $opts); } }

/**
  * Add a new tag. Genre and keyword only - nationalities are a fixed
  * reference list, not user-created.
  *
  * @param string $type 'genre' or 'keyword'.
  * @param array $opts.
  *
  * @return string JSON.
  */
if (!function_exists('addTag')) {
  function addTag($type, $opts = array()) {
    if (empty($opts)) {
      header('HTTP/1.1 400 Bad Request');
      return json_encode(array('error' => array('msg' => ERR_BAD_REQUEST)));
    }
    $t = _tagTypeConfig($type);
    if (!$t['has_standalone_add']) {
      header('HTTP/1.1 400 Bad Request');
      return json_encode(array('error' => array('msg' => ERR_BAD_REQUEST)));
    }

    $ci=& get_instance();
    $ci->load->database();

    $data = array();

    // Get user id from session.
    if (!$data['user_id'] = $ci->session->userdata('user_id') && in_array($ci->session->userdata['user_id'], ADMIN_USERS)) {
      header('HTTP/1.1 401 Unauthorized');
      return json_encode(array('error' => array('msg' => $data)));
    }
    $data += $opts;

    // Add tag data to DB.
    $sql = "INSERT
              INTO " . $t['entity_table'] . " (`name`, `user_id`)
              VALUES (?, ?)";
    $query = $ci->db->query($sql, array($data['name'], $data['user_id']));
    if ($ci->db->affected_rows() === 1) {
      header('HTTP/1.1 201 Created');
      return json_encode(array('success' => array('msg' => $data)));
    }
    else {
      header('HTTP/1.1 400 Bad Request');
      return json_encode(array('error' => array('msg' => ERR_GENERAL)));
    }
  }
}
if (!function_exists('addGenre')) { function addGenre($opts = array()) { return addTag('genre', $opts); } }
if (!function_exists('addKeyword')) { function addKeyword($opts = array()) { return addTag('keyword', $opts); } }

/**
  * Add album tag data.
  *
  * @param string $type 'genre', 'keyword', or 'nationality'.
  * @param array $opts.
  *
  * @return string JSON.
  */
if (!function_exists('addAlbumTag')) {
  function addAlbumTag($type, $opts = array()) {
    if (empty($opts)) {
      header('HTTP/1.1 400 Bad Request');
      return json_encode(array('error' => array('msg' => ERR_BAD_REQUEST)));
    }
    $t = _tagTypeConfig($type);

    $ci=& get_instance();
    $ci->load->database();

    $data = array();

    // Get user id from session.
    if (!$data['user_id'] = $ci->session->userdata('user_id')) {
      header('HTTP/1.1 401 Unauthorized');
      return json_encode(array('error' => array('msg' => $data)));
    }
    $data += $opts;

    // Add tag data to DB.
    $sql = "SELECT " . $t['junction_table'] . ".`album_id`,
                   " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "`,
                   " . $t['junction_table'] . ".`user_id`
            FROM " . $t['junction_table'] . "
            WHERE " . $t['junction_table'] . ".`album_id` = ?
              AND " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "` = ?
              AND " . $t['junction_table'] . ".`user_id` = ?";
    $query = $ci->db->query($sql, array($data['album_id'], $data['tag_id'], $data['user_id']));
    if ($query->num_rows() === 0) {
      $sql = "INSERT
                INTO " . $t['junction_table'] . " (`album_id`, `" . $t['junction_id_column'] . "`, `user_id`)
                VALUES (?, ?, ?)";
      $query = $ci->db->query($sql, array($data['album_id'], $data['tag_id'], $data['user_id']));

      if ($ci->db->affected_rows() === 1) {
        header('HTTP/1.1 201 Created');
        // Remove the "unknown" placeholder once a real tag is set (nationality only).
        if ($t['unknown_id'] !== NULL && $data['tag_id'] != $t['unknown_id']) {
          $sql = "DELETE
                    FROM " . $t['junction_table'] . "
                    WHERE " . $t['junction_table'] . ".`album_id` = ?
                      AND " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "` = '" . $t['unknown_id'] . "'";
          $query = $ci->db->query($sql, array($data['album_id']));
        }
        return json_encode(array('success' => array('msg' => $data)));
      }
      else {
        header('HTTP/1.1 400 Bad Request');
        return json_encode(array('error' => array('msg' => ERR_GENERAL)));
      }
    }
    else {
      header('HTTP/1.1 409 Conflict');
      return json_encode(array('error' => array('msg' => ERR_CONFLICT)));
    }
  }
}
if (!function_exists('addAlbumGenre')) { function addAlbumGenre($opts = array()) { return addAlbumTag('genre', $opts); } }
if (!function_exists('addAlbumKeyword')) { function addAlbumKeyword($opts = array()) { return addAlbumTag('keyword', $opts); } }
if (!function_exists('addAlbumNationality')) { function addAlbumNationality($opts = array()) { return addAlbumTag('nationality', $opts); } }

/**
  * Gets a tag's listening counts.
  *
  * @param string $type 'genre', 'keyword', or 'nationality'.
  * @param array $opts.
  *          'tag_id'   => Tag ID
  *          'user_id'  => User ID
  *
  * @return array Listening information.
  */
if (!function_exists('getTagListenings')) {
  function getTagListenings($type, $opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $t = _tagTypeConfig($type);

    $count_type = empty($opts['user_id']) ? 'total_count' : 'user_count';
    $tag_id = empty($opts['tag_id']) ? '%' : $opts['tag_id'];
    $user_id = empty($opts['user_id']) ? '%' : $opts['user_id'];
    $sql = "SELECT count(*) AS `" . $count_type . "`
            FROM " . TBL_album . ",
                 " . TBL_listening . ",
                 (SELECT " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "`,
                         " . $t['junction_table'] . ".`album_id`
                  FROM " . $t['junction_table'] . "
                  GROUP BY " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "`, " . $t['junction_table'] . ".`album_id`) AS " . $t['junction_table'] . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . $t['junction_table'] . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`user_id` LIKE ?
              AND " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "` = ?";
    $query = $ci->db->query($sql, array($user_id, $tag_id));
    return ($query->num_rows() > 0) ? $query->result_array()[0] : array($count_type => 0);
  }
}
if (!function_exists('getGenreListenings')) { function getGenreListenings($opts = array()) { return getTagListenings('genre', $opts); } }
if (!function_exists('getKeywordListenings')) { function getKeywordListenings($opts = array()) { return getTagListenings('keyword', $opts); } }
if (!function_exists('getNationalityListenings')) { function getNationalityListenings($opts = array()) { return getTagListenings('nationality', $opts); } }

/**
  * Returns top music for the given tag.
  *
  * @param string $type 'genre', 'keyword', or 'nationality'.
  * @param array $opts.
  *          'group_by'        => Group by argument
  *          'no_content'      => Output format
  *          'limit'           => Limit
  *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
  *          'order_by'        => Order by argument
  *          'tag_id'          => Tag id
  *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
  *
  * @return string JSON encoded data containing album information.
  */
if (!function_exists('getMusicByTag')) {
  function getMusicByTag($type, $opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $t = _tagTypeConfig($type);

    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : '`album_id`';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, ' . TBL_album . '.`album_name` ASC';
    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';

    $sql = "SELECT count(*) AS 'count',
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_artist . ".`id` AS `artist_id`,
                   " . TBL_album . ".`album_name`,
                   " . TBL_album . ".`id` AS `album_id`,
                   " . TBL_album . ".`year`
            FROM " . TBL_artist . ",
                 " . TBL_album . ",
                 " . TBL_listening . ",
                 " . TBL_user . ",
                 (SELECT " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "`,
                         " . $t['junction_table'] . ".`album_id`
                  FROM " . $t['junction_table'] . "
                  GROUP BY " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "`,
                           " . $t['junction_table'] . ".`album_id`) AS " . $t['junction_table'] . "
            WHERE " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id`
              AND " . $t['junction_table'] . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_user . ".`username` LIKE ?
              AND " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "` = ?
            GROUP BY " . $ci->db->escape_str($group_by) . "
            ORDER BY " . $ci->db->escape_str($order_by) . "
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $username, $tag_id));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}
if (!function_exists('getMusicByGenre')) { function getMusicByGenre($opts = array()) { return getMusicByTag('genre', $opts); } }
if (!function_exists('getMusicByKeyword')) { function getMusicByKeyword($opts = array()) { return getMusicByTag('keyword', $opts); } }
if (!function_exists('getMusicByNationality')) { function getMusicByNationality($opts = array()) { return getMusicByTag('nationality', $opts); } }

/**
  * Returns tags related to the given tag, ranked by shared album listenings.
  *
  * @param string $type 'genre', 'keyword', or 'nationality'.
  * @param array $opts.
  *          'limit'  => Limit
  *          'tag_id' => Tag id
  *
  * @return string JSON encoded data containing tag information.
  */
if (!function_exists('getRelatedTags')) {
  function getRelatedTags($type, $opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $t = _tagTypeConfig($type);

    $limit = !empty($opts['limit']) ? $opts['limit'] : 5;
    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    $extra_select = ($type === 'nationality') ? (",\n                 " . TBL_nationality . '.`country_code`') : '';

    $sql = "WITH `albums_with_tag` AS (
            SELECT " . $t['junction_table'] . ".`album_id`
            FROM " . $t['junction_table'] . "
            WHERE " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "` = ?
          ),
          `album_listenings` AS (
            SELECT " . TBL_listening . ".`album_id`, COUNT(*) AS `listening_count`
            FROM " . TBL_listening . "
            WHERE " . TBL_listening . ".`album_id` IN (SELECT `album_id` FROM `albums_with_tag`)
            GROUP BY " . TBL_listening . ".`album_id`
          ),
          `related_tags` AS (
            SELECT " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "`,
                  SUM(`album_listenings`.`listening_count`) AS `total_listenings`
            FROM " . $t['junction_table'] . "
            JOIN `album_listenings` ON " . $t['junction_table'] . ".`album_id` = `album_listenings`.`album_id`
            WHERE " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "` != ?
            GROUP BY " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "`
          )
          SELECT `related_tags`.`" . $t['junction_id_column'] . "`,
                 " . $t['entity_table'] . ".`" . $t['name_column'] . "` AS `name`" . $extra_select . "
          FROM `related_tags`,
               " . $t['entity_table'] . "
          WHERE `related_tags`.`" . $t['junction_id_column'] . "` = " . $t['entity_table'] . ".`id`
          ORDER BY `related_tags`.`total_listenings` DESC
          LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($tag_id, $tag_id));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}
if (!function_exists('getRelatedGenres')) { function getRelatedGenres($opts = array()) { return getRelatedTags('genre', $opts); } }
if (!function_exists('getRelatedKeywords')) { function getRelatedKeywords($opts = array()) { return getRelatedTags('keyword', $opts); } }
if (!function_exists('getRelatedNationalities')) { function getRelatedNationalities($opts = array()) { return getRelatedTags('nationality', $opts); } }

/**
  * Delete album tag data.
  *
  * @param string $type 'genre', 'keyword', or 'nationality'.
  * @param array $opts.
  *          'album_id'   => Album ID
  *          'tag_id'     => Tag ID
  *
  * @return string JSON.
  */
if (!function_exists('deleteAlbumTag')) {
  function deleteAlbumTag($type, $opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $t = _tagTypeConfig($type);

    if (!$user_id = $ci->session->userdata('user_id')) {
      header('HTTP/1.1 401 Unauthorized');
      return json_encode(array('error' => array('msg' => $opts)));
    }

    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : '';

    $sql = "DELETE
              FROM " . $t['junction_table'] . "
              WHERE " . $t['junction_table'] . ".`album_id` = ?
                AND " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "` = ?
                AND " . $t['junction_table'] . ".`user_id` = ?";
    $query = $ci->db->query($sql, array($album_id, $tag_id, $user_id));

    if ($ci->db->affected_rows() === 1) {
      header('HTTP/1.1 200 OK');
      return json_encode(array());
    }
    else if (in_array($user_id, ADMIN_USERS)) {
       $sql = "DELETE
              FROM " . $t['junction_table'] . "
              WHERE " . $t['junction_table'] . ".`album_id` = ?
                AND " . $t['junction_table'] . ".`" . $t['junction_id_column'] . "` = ?";
      $query = $ci->db->query($sql, array($album_id, $tag_id));
    }
    else {
      header('HTTP/1.1 401 Unauthorized');
      return json_encode(array('error' => array('msg' => $opts, 'affected' => $ci->db->affected_rows())));
    }
  }
}
if (!function_exists('deleteAlbumGenre')) { function deleteAlbumGenre($opts = array()) { return deleteAlbumTag('genre', $opts); } }
if (!function_exists('deleteAlbumKeyword')) { function deleteAlbumKeyword($opts = array()) { return deleteAlbumTag('keyword', $opts); } }
if (!function_exists('deleteAlbumNationality')) { function deleteAlbumNationality($opts = array()) { return deleteAlbumTag('nationality', $opts); } }

/**
  * Returns top artists for each nationality. Nationality-specific - genre
  * and keyword have no equivalent.
  *
  * @param array $opts.
  *
  * @return string JSON encoded data containing artist information.
  */
if (!function_exists('getTopArtistByNationality')) {
  function getTopArtistByNationality($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';

    $sql = "SELECT *
            FROM (SELECT count(*) AS `count`,
                        " . TBL_artist . ".`artist_name`,
                        " . TBL_artist . ".`id` AS `artist_id`,
                        " . TBL_album . ".`album_name`,
                        " . TBL_album . ".`id` AS `album_id`,
                        " . TBL_album . ".`year`,
                        " . TBL_nationality . ".`country`,
                        " . TBL_nationality . ".`country_code`
                  FROM " . TBL_listening . ",
                       " . TBL_album . ",
                       " . TBL_nationalities . ",
                       " . TBL_nationality . ",
                       " . TBL_user . ",
                       " . TBL_artist . "
                  WHERE " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
                    AND " . TBL_listening . ".`date` BETWEEN ? AND ?
                    AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
                    AND " . TBL_album . ".`id` = " . TBL_nationalities . ".`album_id`
                    AND " . TBL_nationality . ".`id` = " . TBL_nationalities . ".`nationality_id`
                    AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
                    AND " . TBL_user . ".`username` LIKE ?
                  GROUP BY " . TBL_artist . ".`id`
                  ORDER by " . TBL_nationality . ".`country` DESC, `count` DESC) AS `result`
            GROUP BY `result`.`country`
            ORDER BY `result`.`country` ASC";
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $username));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}

/*
 Get albums with out nationality
select nationalities.nationality_id, nationalities.album_id, album_name

from nationalities right join album

on nationalities.album_id = album.id
*/
