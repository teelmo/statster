<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  *
  * Returns top nationalities for the given user.
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
  */
if (!function_exists('getNationalities')) {
  function getNationalities($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $album_name = !empty($opts['album_name']) ? $opts['album_name'] : '%';
    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : TBL_nationality . '.`id`';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC';
    $select = !empty($opts['select']) ? ', ' . $opts['select'] : '';
    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '%';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT count(*) as `count`,
                   'nationality' as `type`,
                   " . TBL_nationality . ".`country` as `name`,
                   " . TBL_nationality . ".`id` as `tag_id`
                   " . $ci->db->escape_str($select) . "
            FROM " . TBL_album . ",
                 " . TBL_artist . ",
                 " . TBL_listening . ",
                 " . TBL_user . ",
                 " . TBL_nationality . ",
                 (SELECT " . TBL_nationalities . ".`nationality_id`,
                         " . TBL_nationalities . ".`album_id`
                  FROM " . TBL_nationalities . "
                  GROUP BY " . TBL_nationalities . ".`nationality_id`, " . TBL_nationalities . ".`album_id`) as " . TBL_nationalities . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_album . ".`id` = " . TBL_nationalities . ".`album_id`
              AND " . TBL_nationality . ".`id` = " . TBL_nationalities . ".`nationality_id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_artist . ".`artist_name` LIKE ?
              AND " . TBL_album . ".`album_name` LIKE ?
              AND " . TBL_nationalities . ".`nationality_id` LIKE ?
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
  * Returns all nationalities.
  * @param array $opts.
  *          'human_readable'  => Output format
  *
  * @return string JSON encoded the data
  */
if (!function_exists('getAllNationalities')) {
  function getAllNationalities($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $sql = "SELECT 'nationality' as `type`,
                   " . TBL_nationality . ".`country` as `name`,
                   " . TBL_nationality . ".`id` as `tag_id`
            FROM " . TBL_nationality . "
            WHERE 1
            ORDER BY " . TBL_nationality . ".`country`";
    $query = $ci->db->query($sql, array());

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Returns cumulative listeners for given nationality.
  *
  * @param array $opts.
  *          'tag id'          => Tag ID
  *          'username'        => Username
  *
  * @return string JSON encoded data containing album information.
  */
if (!function_exists('getNationalitiesCumulative')) {
  function getNationalitiesCumulative($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '%';
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT DATE_FORMAT(`date`, '%Y%m') as `line_date`,
                   (SELECT COUNT(*) 
                    FROM " . TBL_listening . ",
                         " . TBL_user . ",
                         " . TBL_album . ",
                         " . TBL_nationality . ",
                         (SELECT " . TBL_nationalities . ".`nationality_id`,
                                 " . TBL_nationalities . ".`album_id`
                          FROM " . TBL_nationalities . "
                          GROUP BY " . TBL_nationalities . ".`nationality_id`, " . TBL_nationalities . ".`album_id`) as " . TBL_nationalities . "
                   WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
                      AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
                      AND " . TBL_album . ".`id` = " . TBL_nationalities . ".`album_id`
                      AND " . TBL_nationality . ".`id` = " . TBL_nationalities . ".`nationality_id`
                      AND " . TBL_nationalities . ".`nationality_id` LIKE ?
                      AND " . TBL_user . ".`username` LIKE ?
                      AND `date` <= MAX(a.`date`)) as `cumulative_count`
            FROM " . TBL_listening . " as a
            WHERE MONTH(a.`date`) <> 0
            GROUP BY `line_date`
            ORDER BY `line_date` ASC";
    $query = $ci->db->query($sql, array($tag_id, $username));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Gets nationality's bio.
  *
  * @param array $opts.
  *          'tag_id'  => Nationality ID
  *
  * @return array Nationality bio
  */
if (!function_exists('getNationalityBio')) {
  function getNationalityBio($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $nationality_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    $sql = "SELECT " . TBL_nationality_biography . ".`id` as `biography_id`,
                   " . TBL_nationality_biography . ".`summary` as `bio_summary`, 
                   " . TBL_nationality_biography . ".`text` as `bio_content`, 
                   " . TBL_nationality_biography . ".`updated` as `bio_updated`,
                   'false' as `update_bio`
            FROM " . TBL_nationality_biography . "
            WHERE " . TBL_nationality_biography . ".`nationality_id` = ?";
    $query = $ci->db->query($sql, array($nationality_id));
    return ($query->num_rows() > 0) ? $query->result_array()[0] : array('update_bio' => false);
  }
}

/**
  * Add nationality's bio.
  *
  * @param array $opts.
  *          'tag_id'       => Nationality ID
  *          'bio_summary'  => Bio summary
  *          'bio_content'  => Bio content
  *
  * @return retun boolean TRUE or FALSE
  */
if (!function_exists('addNationalityBio')) {
  function addNationalityBio($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $nationality_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    $summary = !empty($opts['bio_summary']) ? $opts['bio_summary'] : '';
    $text = !empty($opts['bio_content']) ? $opts['bio_content'] : '';

    $sql = "SELECT  " . TBL_nationality_biography . ".`id`
            FROM " . TBL_nationality_biography . "
            WHERE " . TBL_nationality_biography . ".`nationality_id` = ?";
    $query = $ci->db->query($sql, array($nationality_id));
    if ($query->num_rows() === 1) {
      $sql = "UPDATE " . TBL_nationality_biography . "
                SET " . TBL_nationality_biography . ".`summary` = ?,
                    " . TBL_nationality_biography . ".`text` = ?,
                    " . TBL_nationality_biography . ".`updated` = NOW()
                WHERE " . TBL_nationality_biography . ".`nationality_id` = ?";
      $query = $ci->db->query($sql, array($summary, $text, $nationality_id));
    }
    else {
      $sql = "INSERT
                INTO " . TBL_nationality_biography . " (`nationality_id`, `summary`, `text`)
                VALUES (?, ?, ?)";
      $query = $ci->db->query($sql, array($nationality_id, $summary, $text));
    }
    return ($ci->db->affected_rows() === 1);
  }
}

/**
  * Add album nationality data.
  *
  * @param array $opts.
  *
  * @return string JSON.
  */
if (!function_exists('addAlbumNationality')) {
  function addAlbumNationality($opts = array()) {
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
  
    // Add nationality data to DB.
    $sql = "INSERT
              INTO " . TBL_nationalities . " (`album_id`, `nationality_id`, `user_id`)
              VALUES (?, ?, ?)";
    $query = $ci->db->query($sql, array($data['album_id'], $data['tag_id'], $data['user_id']));

    if ($ci->db->affected_rows() === 1) {
      header('HTTP/1.1 201 Created');
      // Remove unknown.
      if ($data['tag_id'] != '242') {
        $sql = "DELETE 
                  FROM " . TBL_nationalities . "
                  WHERE " . TBL_nationalities . ".`album_id` = ?
                    AND " . TBL_nationalities . ".`nationality_id` = '242'";
        $query = $ci->db->query($sql, array($data['album_id']));
      }
      return json_encode(array('success' => array('msg' => $data)));
    }
    else {
      header('HTTP/1.1 400 Bad Request');
      return json_encode(array('error' => array('msg' => ERR_GENERAL)));
    }
  }
}

/**
   * Gets nationality's listenings.
   *
   * @param array $opts.
   *          'tag_id'      => Nationality ID
   *          'user_id'     => User ID
   *
   * @return array Listening information.
   *
   */
if (!function_exists('getNationalityListenings')) {
  function getNationalityListenings($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $count_type = empty($opts['user_id']) ? 'total_count' : 'user_count';
    $tag_id = empty($opts['tag_id']) ? '%' : $opts['tag_id'];
    $user_id = empty($opts['user_id']) ? '%' : $opts['user_id'];
    $sql = "SELECT count(*) as `" . $count_type . "`
            FROM " . TBL_album . ",
                 " . TBL_listening . ",
                 (SELECT " . TBL_nationalities . ".`nationality_id`,
                         " . TBL_nationalities . ".`album_id`
                  FROM " . TBL_nationalities . "
                  GROUP BY " . TBL_nationalities . ".`nationality_id`, " . TBL_nationalities . ".`album_id`) as " . TBL_nationalities . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_nationalities . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`user_id` LIKE ?
              AND " . TBL_nationalities . ".`nationality_id` = ?";
    $query = $ci->db->query($sql, array($user_id, $tag_id));
    return ($query->num_rows() > 0) ? $query->result_array()[0] : array($count_type => 0);
  }
}

/**
  * Returns top music for given nationality.
  *
  * @param array $opts.
  *          'group_by'        => Group by argument
  *          'human_readable'  => Output format
  *          'limit'           => Limit
  *          'order_by'        => Order by argument
  *          'tag_id'          => Tag id
  *
  * @return string JSON encoded data containing album information.
  *
  **/
if (!function_exists('getMusicByNationality')) {
  function getMusicByNationality($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : '`album_id`';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, ' . TBL_album . '.`album_name` ASC';
    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';

    $sql = "SELECT count(*) as 'count',
                   " . TBL_artist . ".`artist_name`,
                   " . TBL_artist . ".`id` as `artist_id`,
                   " . TBL_album . ".`album_name`,
                   " . TBL_album . ".`id` as `album_id`,
                   " . TBL_album . ".`year`
            FROM " . TBL_artist . ",
                 " . TBL_album . ",
                 " . TBL_listening . ",
                 " . TBL_user . ",
                 (SELECT " . TBL_nationalities . ".`nationality_id`,
                         " . TBL_nationalities . ".`album_id`
                  FROM " . TBL_nationalities . "
                  GROUP BY " . TBL_nationalities . ".`nationality_id`, " . TBL_nationalities . ".`album_id`) as " . TBL_nationalities . "
            WHERE " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id` 
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_nationalities . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_user . ".`username` LIKE ?
              AND " . TBL_nationalities . ".`nationality_id` = ?
            GROUP BY " . $ci->db->escape_str($group_by) . "
            ORDER BY " . $ci->db->escape_str($order_by) . "
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $username, $tag_id));
            
    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Delete album nationality data.
  *
  * @param array $opts.
  *          'album_id'   => Album ID
  *          'tag_id'     => Nationality ID
  *
  * @return string JSON.
  *
  **/
if (!function_exists('deleteAlbumNationality')) {
  function deleteAlbumNationality($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    if (!$user_id = $ci->session->userdata('user_id')) {
      header('HTTP/1.1 401 Unauthorized');
      return json_encode(array('error' => array('msg' => $ops)));
    }

    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    $album_id = !empty($opts['album_id']) ? $opts['album_id'] : '';

    $sql = "DELETE 
              FROM " . TBL_nationalities . "
              WHERE " . TBL_nationalities . ".`album_id` = ?
                AND " . TBL_nationalities . ".`nationality_id` = ?
                AND " . TBL_nationalities . ".`user_id` = ?";
    $query = $ci->db->query($sql, array($album_id, $tag_id, $user_id));

    if ($ci->db->affected_rows() === 1) {
      header('HTTP/1.1 200 OK');
      return json_encode(array());
    }
    else {
      header('HTTP/1.1 401 Unauthorized');
      return json_encode(array('error' => array('msg' => $opts, 'affected' => $ci->db->affected_rows())));
    }
  }
}

/*
 Get albums with out nationality
select nationalities.nationality_id, nationalities.album_id, album_name

from nationalities right join album 

on nationalities.album_id = album.id  
*/

