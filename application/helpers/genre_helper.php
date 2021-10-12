<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Returns top genres for the given user.
  *
  * @param array $opts.
  *          'album_name'      => Album name
  *          'artist_name'     => Artist name
  *          'group_by'        => Group by argument
  *          'human_readable'  => Output format
  *          'limit'           => Limit
  *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
  *          'order_by'        => Order by argument
  *          'tag_id'          => Tag id
  *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
  *          'username'        => Username
  *
  * @return string JSON encoded the data
  */
if (!function_exists('getGenres')) {
  function getGenres($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $album_name = isset($opts['album_name']) ? $opts['album_name'] : '%';
    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : '%';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : TBL_genre . '.`id`';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC';
    $select = !empty($opts['select']) ? ', ' . $opts['select'] : '';
    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '%';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT count(*) AS `count`,
                   'genre' AS `type`,
                   " . TBL_genre . ".`name`,
                   " . TBL_genre . ".`id` AS `tag_id`
                   " . $ci->db->escape_str($select) . "
            FROM " . TBL_album . ",
                 " . TBL_artist . ",
                 " . TBL_listening . ",
                 " . TBL_user . ",
                 " . TBL_genre . ",
                 (SELECT " . TBL_genres . ".`genre_id`,
                         " . TBL_genres . ".`album_id`
                  FROM " . TBL_genres . "
                  GROUP BY " . TBL_genres . ".`genre_id`, " . TBL_genres . ".`album_id`) AS " . TBL_genres . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_album . ".`id` = " . TBL_genres . ".`album_id`
              AND " . TBL_genre . ".`id` = " . TBL_genres . ".`genre_id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_artist . ".`artist_name` LIKE ?
              AND " . TBL_album . ".`album_name` LIKE ?
              AND " . TBL_genres . ".`genre_id` LIKE ?
              AND " . TBL_user . ".`username` LIKE ?
              " . $ci->db->escape_str($where) . "
            GROUP BY " . $ci->db->escape_str($group_by) . "
            ORDER BY " . $ci->db->escape_str($order_by) . "
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $artist_name, $album_name, $tag_id, $username));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Returns all genres.
  * @param array $opts.
  *          'human_readable'  => Output format
  *
  * @return string JSON encoded the data
  */
if (!function_exists('getAllGenres')) {
  function getAllGenres($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $sql = "SELECT 'genre' AS `type`,
                   " . TBL_genre . ".`name`,
                   " . TBL_genre . ".`id` AS `tag_id`
            FROM " . TBL_genre . "
            WHERE 1
            ORDER BY " . TBL_genre . ".`name`";
    $query = $ci->db->query($sql, array());

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Returns cumulative listeners for given genre.
  *
  * @param array $opts.
  *          'tag id'          => Tag ID
  *          'username'        => Username
  *
  * @return string JSON encoded data containing album information.
  */
if (!function_exists('getGenresCumulative')) {
  function getGenresCumulative($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '%';
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT DATE_FORMAT(`date`, '%Y%m') AS `line_date`,
                   (SELECT COUNT(*) 
                    FROM " . TBL_listening . ",
                         " . TBL_user . ",
                         " . TBL_album . ",
                         " . TBL_genre . ",
                         (SELECT " . TBL_genres . ".`genre_id`,
                                 " . TBL_genres . ".`album_id`
                          FROM " . TBL_genres . "
                          GROUP BY " . TBL_genres . ".`genre_id`, " . TBL_genres . ".`album_id`) AS " . TBL_genres . "
                   WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
                      AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
                      AND " . TBL_album . ".`id` = " . TBL_genres . ".`album_id`
                      AND " . TBL_genre . ".`id` = " . TBL_genres . ".`genre_id`
                      AND " . TBL_genres . ".`genre_id` LIKE ?
                      AND " . TBL_user . ".`username` LIKE ?
                      AND `date` <= MAX(a.`date`)) AS `cumulative_count`
            FROM " . TBL_listening . " AS a
            WHERE MONTH(a.`date`) <> 0
            GROUP BY `line_date`
            ORDER BY `line_date` ASC";
    $query = $ci->db->query($sql, array($tag_id, $username));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Gets genre's bio.
  *
  * @param array $opts.
  *          'tag_id'  => Genre ID
  *
  * @return array Genre bio
  */
if (!function_exists('getGenreBio')) {
  function getGenreBio($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $genre_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    $sql = "SELECT " . TBL_genre_biography . ".`id` AS `biography_id`,
                   " . TBL_genre_biography . ".`summary` AS `bio_summary`, 
                   " . TBL_genre_biography . ".`text` AS `bio_content`, 
                   " . TBL_genre_biography . ".`updated` AS `bio_updated`,
                   'false' AS `update_bio`
            FROM " . TBL_genre_biography . "
            WHERE " . TBL_genre_biography . ".`genre_id` = ?";
    $query = $ci->db->query($sql, array($genre_id));
    return ($query->num_rows() > 0) ? $query->result_array()[0] : array('update_bio' => false);
  }
}

/**
  * Add genre's bio.
  *
  * @param array $opts.
  *          'tag_id'       => Genre ID
  *          'bio_summary'  => Bio summary
  *          'bio_content'  => Bio content
  *
  * @return retun boolean TRUE or FALSE
  */
if (!function_exists('addGenreBio')) {
  function addGenreBio($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $genre_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    $summary = !empty($opts['bio_summary']) ? $opts['bio_summary'] : '';
    $text = !empty($opts['bio_content']) ? $opts['bio_content'] : '';

    $sql = "SELECT  " . TBL_genre_biography . ".`id`
            FROM " . TBL_genre_biography . "
            WHERE " . TBL_genre_biography . ".`genre_id` = ?";
    $query = $ci->db->query($sql, array($genre_id));
    if ($query->num_rows() === 1) {
      $sql = "UPDATE " . TBL_genre_biography . "
                SET " . TBL_genre_biography . ".`summary` = ?,
                    " . TBL_genre_biography . ".`text` = ?,
                    " . TBL_genre_biography . ".`updated` = NOW()
                WHERE " . TBL_genre_biography . ".`genre_id` = ?";
      $query = $ci->db->query($sql, array($summary, $text, $genre_id));
    }
    else {
      $sql = "INSERT
                INTO " . TBL_genre_biography . " (`genre_id`, `summary`, `text`)
                VALUES (?, ?, ?)";
      $query = $ci->db->query($sql, array($genre_id, $summary, $text));
    }
    return ($ci->db->affected_rows() === 1);
  }
}

/**
  * Add genre.
  *
  * @param array $opts.
  *
  * @return string JSON.
  */
if (!function_exists('addGenre')) {
  function addGenre($opts = array()) {
    if (empty($opts)) {
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
  
    // Add genre data to DB.
    $sql = "INSERT
              INTO " . TBL_genre . " (`name`, `user_id`)
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

/**
  * Add album genre data.
  *
  * @param array $opts.
  *
  * @return string JSON.
  */
if (!function_exists('addAlbumGenre')) {
  function addAlbumGenre($opts = array()) {
    if (empty($opts)) {
      header('HTTP/1.1 400 Bad Request');
      return json_encode(array('error' => array('msg' => ERR_BAD_REQUEST)));
    }
    $ci=& get_instance();
    $ci->load->database();
    
    $data = array();
    // Get user id from session.
    if (!$data['user_id'] = $ci->session->userdata('user_id')) {
      header('HTTP/1.1 401 Unauthorized');
      return json_encode(array('error' => array('msg' => $data)));
    }
    $data += $opts;

    // Add genre data to DB.
    $sql = "INSERT
              INTO " . TBL_genres . " (`album_id`, `genre_id`, `user_id`)
              VALUES (?, ?, ?)";
    $query = $ci->db->query($sql, array($data['album_id'], $data['tag_id'], $data['user_id']));
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

/**
   * Gets genres's listenings.
   *
   * @param array $opts.
   *          'tag_id'   => Genre ID
   *          'user_id'  => User ID
   *
   * @return array Listening information.
   *
   */
if (!function_exists('getGenreListenings')) {
  function getGenreListenings($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $count_type = empty($opts['user_id']) ? 'total_count' : 'user_count';
    $tag_id = empty($opts['tag_id']) ? '%' : $opts['tag_id'];
    $user_id = empty($opts['user_id']) ? '%' : $opts['user_id'];
    $sql = "SELECT count(*) AS `" . $count_type . "`
            FROM " . TBL_album . ",
                 " . TBL_listening . ",
                 (SELECT " . TBL_genres . ".`genre_id`,
                         " . TBL_genres . ".`album_id`
                  FROM " . TBL_genres . "
                  GROUP BY " . TBL_genres . ".`genre_id`, " . TBL_genres . ".`album_id`) AS " . TBL_genres . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_genres . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`user_id` LIKE ?
              AND " . TBL_genres . ".`genre_id` = ?";
    $query = $ci->db->query($sql, array($user_id, $tag_id));
    return ($query->num_rows() > 0) ? $query->result_array()[0] : array($count_type => 0);
  }
}

/**
  * Returns top music for given genre.
  *
  * @param array $opts.
  *          'group_by'        => Group by argument
  *          'human_readable'  => Output format
  *          'limit'           => Limit
  *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
  *          'order_by'        => Order by argument
  *          'tag_id'          => Tag id
  *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
  *
  * @return string JSON encoded data containing album information.
  *
  **/
if (!function_exists('getMusicByGenre')) {
  function getMusicByGenre($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

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
                 (SELECT " . TBL_genres . ".`genre_id`,
                         " . TBL_genres . ".`album_id`
                  FROM " . TBL_genres . "
                  GROUP BY " . TBL_genres . ".`genre_id`, 
                           " . TBL_genres . ".`album_id`) AS " . TBL_genres . "
            WHERE " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id` 
              AND " . TBL_genres . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_user . ".`username` LIKE ?
              AND " . TBL_genres . ".`genre_id` = ?
            GROUP BY " . $ci->db->escape_str($group_by) . "
            ORDER BY " . $ci->db->escape_str($order_by) . " 
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $username, $tag_id));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Delete genre data.
  *
  * @param array $opts.
  *          'album_id'   => Album ID
  *          'tag_id'     => Genre ID
  *
  * @return string JSON.
  *
  **/
if (!function_exists('deleteAlbumGenre')) {
  function deleteAlbumGenre($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    if (!$user_id = $ci->session->userdata('user_id')) {
      header('HTTP/1.1 401 Unauthorized');
      return json_encode(array('error' => array('msg' => $ops)));
    }

    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : '';

    $sql = "DELETE 
              FROM " . TBL_genres . "
              WHERE " . TBL_genres . ".`album_id` = ?
                AND " . TBL_genres . ".`genre_id` = ?
                AND " . TBL_genres . ".`user_id` = ?";
    $query = $ci->db->query($sql, array($album_id, $tag_id, $user_id));

    if ($ci->db->affected_rows() === 1) {
      header('HTTP/1.1 200 OK');
      return json_encode(array());
    }
    else if (in_array($user_id, ADMIN_USERS)) {
       $sql = "DELETE 
              FROM " . TBL_genres . "
              WHERE " . TBL_genres . ".`album_id` = ?
                AND " . TBL_genres . ".`genre_id` = ?";
      $query = $ci->db->query($sql, array($album_id, $tag_id));
    }
    else {
      header('HTTP/1.1 401 Unauthorized');
      return json_encode(array('error' => array('msg' => $opts, 'affected' => $ci->db->affected_rows())));
    }
  }
}