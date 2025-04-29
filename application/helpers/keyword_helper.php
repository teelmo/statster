<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Returns top keywords for the given user.
  *
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
  * @return string JSON encoded the data.
  */
if (!function_exists('getKeywords')) {
  function getKeywords($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    
    $album_name = isset($opts['album_name']) ? $opts['album_name'] : '%';
    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : '%';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : TBL_keyword . '.`id`';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC';
    $select = !empty($opts['select']) ? ', ' . $opts['select'] : '';
    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '%';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT count(*) AS `count`,
                   'keyword' AS `type`,
                   " . TBL_keyword . ".`name`,
                   " . TBL_keyword . ".`id` AS `tag_id`
                   " . $ci->db->escape_str($select) . "
            FROM " . TBL_album . ",
                 " . TBL_artist . ",
                 " . TBL_listening . ",
                 " . TBL_user . ",
                 " . TBL_keyword . ",
                 (SELECT " . TBL_keywords . ".`keyword_id`,
                         " . TBL_keywords . ".`album_id`
                  FROM " . TBL_keywords . "
                  GROUP BY " . TBL_keywords . ".`keyword_id`, " . TBL_keywords . ".`album_id`) AS " . TBL_keywords . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_album . ".`id` = " . TBL_keywords . ".`album_id`
              AND " . TBL_keyword . ".`id` = " . TBL_keywords . ".`keyword_id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_artist . ".`artist_name` LIKE ?
              AND " . TBL_album . ".`album_name` LIKE ?
              AND " . TBL_keywords . ".`keyword_id` LIKE ?
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

/**
  * Returns all keywords.
  * @param array $opts.
  *          'no_content'  => Output format
  *
  * @return string JSON encoded the data
  */
if (!function_exists('getAllKeywords')) {
  function getAllKeywords($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $sql = "SELECT 'keyword' AS `type`,
                   " . TBL_keyword . ".`name`,
                   " . TBL_keyword . ".`id` AS `tag_id`
            FROM " . TBL_keyword . "
            WHERE 1
            ORDER BY " . TBL_keyword . ".`name`";
    $query = $ci->db->query($sql, array());

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}

/**
  * Returns cumulative listeners for given keywrod.
  *
  * @param array $opts.
  *          'tag id'          => Tag ID
  *          'username'        => Username
  *
  * @return string JSON encoded data containing album information.
  */
if (!function_exists('getKeywordsCumulative')) {
  function getKeywordsCumulative($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '%';
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT DATE_FORMAT(`date`, '%Y%m') AS `line_date`,
                   (SELECT COUNT(*) 
                    FROM " . TBL_listening . ",
                         " . TBL_user . ",
                         " . TBL_album . ",
                         " . TBL_keyword . ",
                         (SELECT " . TBL_keywords . ".`keyword_id`,
                                 " . TBL_keywords . ".`album_id`
                          FROM " . TBL_keywords . "
                          GROUP BY " . TBL_keywords . ".`keyword_id`, " . TBL_keywords . ".`album_id`) AS " . TBL_keywords . "
                   WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
                      AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
                      AND " . TBL_album . ".`id` = " . TBL_keywords . ".`album_id`
                      AND " . TBL_keyword . ".`id` = " . TBL_keywords . ".`keyword_id`
                      AND " . TBL_keywords . ".`keyword_id` LIKE ?
                      AND " . TBL_user . ".`username` LIKE ?
                      AND `date` <= MAX(a.`date`)) AS `cumulative_count`
            FROM " . TBL_listening . " AS a
            WHERE MONTH(a.`date`) <> 0
            GROUP BY `line_date`
            ORDER BY `line_date` ASC";
    $query = $ci->db->query($sql, array($tag_id, $username));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}

/**
  * Gets keyword's bio.
  *
  * @param array $opts.
  *          'tag_id'  => Keyword ID
  *
  * @return array Keyword bio
  */
if (!function_exists('getKeywordBio')) {
  function getKeywordBio($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $keyword_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    $sql = "SELECT " . TBL_keyword_biography . ".`id` AS `biography_id`,
                   " . TBL_keyword_biography . ".`summary` AS `bio_summary`, 
                   " . TBL_keyword_biography . ".`text` AS `bio_content`, 
                   " . TBL_keyword_biography . ".`updated` AS `bio_updated`,
                   'false' AS `update_bio`
            FROM " . TBL_keyword_biography . "
            WHERE " . TBL_keyword_biography . ".`keyword_id` = ?";
    $query = $ci->db->query($sql, array($keyword_id));
    return ($query->num_rows() > 0) ? $query->result_array()[0] : array('update_bio' => false);
  }
}

/**
  * Add keyword's bio.
  *
  * @param array $opts.
  *          'tag_id'       => Keyword ID
  *          'bio_summary'  => Bio summary
  *          'bio_content'  => Bio content
  *
  * @return retun boolean TRUE or FALSE
  */
if (!function_exists('addKeywordBio')) {
  function addKeywordBio($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $keyword_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    $summary = !empty($opts['bio_summary']) ? $opts['bio_summary'] : '';
    $text = !empty($opts['bio_content']) ? $opts['bio_content'] : '';

    $sql = "SELECT  " . TBL_keyword_biography . ".`id`
            FROM " . TBL_keyword_biography . "
            WHERE " . TBL_keyword_biography . ".`keyword_id` = ?";
    $query = $ci->db->query($sql, array($keyword_id));
    if ($query->num_rows() === 1) {
      $sql = "UPDATE " . TBL_keyword_biography . "
                SET " . TBL_keyword_biography . ".`summary` = ?,
                    " . TBL_keyword_biography . ".`text` = ?,
                    " . TBL_keyword_biography . ".`updated` = NOW()
                WHERE " . TBL_keyword_biography . ".`keyword_id` = ?";
      $query = $ci->db->query($sql, array($summary, $text, $keyword_id));
    }
    else {
      $sql = "INSERT
                INTO " . TBL_keyword_biography . " (`keyword_id`, `summary`, `text`)
                VALUES (?, ?, ?)";
      $query = $ci->db->query($sql, array($keyword_id, $summary, $text));
    }
    return ($ci->db->affected_rows() === 1);
  }
}

/**
  * Add keyword.
  *
  * @param array $opts.
  *
  * @return string JSON.
  */
if (!function_exists('addKeyword')) {
  function addKeyword($opts = array()) {
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
  
    // Add keyword data to DB.
    $sql = "INSERT
              INTO " . TBL_keyword . " (`name`, `user_id`)
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
  * Add album keyword data.
  *
  * @param array $opts.
  *
  * @return string JSON.
  */
if (!function_exists('addAlbumKeyword')) {
  function addAlbumKeyword($opts = array()) {
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
  
    // Add keyword data to DB.
    $sql = "SELECT " . TBL_keywords . ".`album_id`,
                   " . TBL_keywords . ".`keyword_id`,
                   " . TBL_keywords . ".`user_id`
            FROM " . TBL_keywords . "
            WHERE " . TBL_keywords . ".`album_id` = ?
              AND " . TBL_keywords . ".`keyword_id` = ?
              AND " . TBL_keywords . ".`user_id` = ?";
    $query = $ci->db->query($sql, array($data['album_id'], $data['tag_id'], $data['user_id']));
    if ($query->num_rows() === 0) {
      $sql = "INSERT
                INTO " . TBL_keywords . " (`album_id`, `keyword_id`, `user_id`)
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
    else {
      header('HTTP/1.1 409 Conflict');
      return json_encode(array('error' => array('msg' => ERR_CONFLICT)));
    }
  }
}

/**
   * Gets keyword's listenings.
   *
   * @param array $opts.
   *          'tag_id'      => Keyword ID
   *          'user_id'     => User ID
   *
   * @return array Listening information.
   *
   */
if (!function_exists('getKeywordListenings')) {
  function getKeywordListenings($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $count_type = empty($opts['user_id']) ? 'total_count' : 'user_count';
    $tag_id = empty($opts['tag_id']) ? '%' : $opts['tag_id'];
    $user_id = empty($opts['user_id']) ? '%' : $opts['user_id'];
    $sql = "SELECT count(*) AS `" . $count_type . "`
            FROM " . TBL_album . ",
                 " . TBL_listening . ",
                 (SELECT " . TBL_keywords . ".`keyword_id`,
                         " . TBL_keywords . ".`album_id`
                  FROM " . TBL_keywords . "
                  GROUP BY " . TBL_keywords . ".`keyword_id`, " . TBL_keywords . ".`album_id`) AS " . TBL_keywords . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_keywords . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`user_id` LIKE ?
              AND " . TBL_keywords . ".`keyword_id` = ?";
    $query = $ci->db->query($sql, array($user_id, $tag_id));
    return ($query->num_rows() > 0) ? $query->result_array()[0] : array($count_type => 0);
  }
}

/**
  * Returns top music for given keyword.
  *
  * @param array $opts.
  *          'group_by'        => Group by argument
  *          'no_content'  => Output format
  *          'limit'           => Limit
  *          'order_by'        => Order by argument
  *          'tag_id'          => Keyword id
  *
  * @return string JSON encoded data containing album information.
  *
  **/
if (!function_exists('getMusicByKeyword')) {
  function getMusicByKeyword($opts = array()) {
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
                 (SELECT " . TBL_keywords . ".`keyword_id`,
                         " . TBL_keywords . ".`album_id`
                  FROM " . TBL_keywords . "
                  GROUP BY " . TBL_keywords . ".`keyword_id`,
                           " . TBL_keywords . ".`album_id`) AS " . TBL_keywords . "
            WHERE " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id` 
              AND " . TBL_keywords . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_user . ".`username` LIKE ?
              AND " . TBL_keywords . ".`keyword_id` LIKE ?
            GROUP BY " . $ci->db->escape_str($group_by) . "
            ORDER BY " . $ci->db->escape_str($order_by) . " 
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $username, $tag_id));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}

/**
  * Returns top music for given keyword.
  *
  * @param array $opts.
  *          'limit'  => Limit
  *          'tag_id' => Tag id
  *
  * @return string JSON encoded data containing keyword information.
  *
  **/
if (!function_exists('getRelatedKeywords')) {
  function getRelatedKeywords($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $limit = !empty($opts['limit']) ? $opts['limit'] : 5;
    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    
    $sql = "WITH `albums_with_tag` AS (
            SELECT " . TBL_keywords . ".`album_id`
            FROM " . TBL_keywords . "
            WHERE " . TBL_keywords . ".`keyword_id` = ?
          ),
          `album_listenings` AS (
            SELECT " . TBL_listening . ".`album_id`, COUNT(*) AS `listening_count`
            FROM " . TBL_listening . "
            WHERE " . TBL_listening . ".`album_id` IN (SELECT `album_id` FROM `albums_with_tag`)
            GROUP BY " . TBL_listening . ".`album_id`
          ),
          `related_keywords` AS (
            SELECT " . TBL_keywords . ".`keyword_id`,
                  SUM(`album_listenings`.`listening_count`) AS `total_listenings`
            FROM " . TBL_keywords . "
            JOIN `album_listenings` ON " . TBL_keywords . ".`album_id` = `album_listenings`.`album_id`
            WHERE " . TBL_keywords . ".`keyword_id` != ?
            GROUP BY " . TBL_keywords . ".`keyword_id`
          )
          SELECT `related_keywords`.`keyword_id`,
                 " . TBL_keyword . ".`name`
          FROM `related_keywords`,
               " . TBL_keyword . "
          WHERE `related_keywords`.`keyword_id` = ". TBL_keyword . ".`id`
          ORDER BY `related_keywords`.`total_listenings` DESC
          LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($tag_id, $tag_id));

    $no_content = isset($opts['no_content']) ? $opts['no_content'] : TRUE;
    return _json_return_helper($query, $no_content);
  }
}

/**
  * Delete album keyword data.
  *
  * @param array $opts.
  *          'album_id'   => Album ID
  *          'tag_id'     => Keyword ID
  *
  * @return string JSON.
  *
  **/
if (!function_exists('deleteAlbumKeyword')) {
  function deleteAlbumKeyword($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    if (!$user_id = $ci->session->userdata('user_id')) {
      header('HTTP/1.1 401 Unauthorized');
      return json_encode(array('error' => array('msg' => $ops)));
    }

    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : '';

    $sql = "DELETE 
              FROM " . TBL_keywords . "
              WHERE " . TBL_keywords . ".`album_id` = ?
                AND " . TBL_keywords . ".`keyword_id` = ?
                AND " . TBL_keywords . ".`user_id` = ?";
    $query = $ci->db->query($sql, array($album_id, $tag_id, $user_id));

    if ($ci->db->affected_rows() === 1) {
      header('HTTP/1.1 200 OK');
      return json_encode(array());
    }
    else if (in_array($user_id, ADMIN_USERS)) {
       $sql = "DELETE 
              FROM " . TBL_keywords . "
              WHERE " . TBL_keywords . ".`album_id` = ?
                AND " . TBL_keywords . ".`keyword_id` = ?";
      $query = $ci->db->query($sql, array($album_id, $tag_id));
    }
    else {
      header('HTTP/1.1 401 Unauthorized');
      return json_encode(array('error' => array('msg' => $opts, 'affected' => $ci->db->affected_rows())));
    }
  }
}