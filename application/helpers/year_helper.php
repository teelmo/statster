<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Returns top years for the given user.
  *
  * @param array $opts.
  *          'album'           => Album name
  *          'artist'          => Artist name
  *          'group_by'        => Group by argument
  *          'human_readable'  => Output format
  *          'limit'           => Limit
  *          'lower_limit'     => Lower date limit in yyyy-mm-dd format
  *          'order_by'        => Order by argument
  *          'tag_id'          => Year
  *          'upper_limit'     => Upper date limit in yyyy-mm-dd format
  *          'username'        => Username
  *          'where'           => Where
  *
  * @return string JSON encoded the data.
  */
if (!function_exists('getYears')) {
  function getYears($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    
    $album_name = isset($opts['album_name']) ? $opts['album_name'] : '%';
    $artist_name = isset($opts['artist_name']) ? $opts['artist_name'] : '%';
    $group_by = !empty($opts['group_by']) ? $opts['group_by'] : TBL_album . '.`year`';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC';
    $select = !empty($opts['select']) ? ', ' . $opts['select'] : '';
    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '%';
    $upper_limit = !empty($opts['upper_limit']) ? $opts['upper_limit'] : date('Y-m-d');
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $where = !empty($opts['where']) ? 'AND ' . $opts['where'] : '';
    $sql = "SELECT count(*) AS `count`,
                   " . TBL_album . ".`year`,
                   " . TBL_album . ".`year` AS `name`,
                   'year' AS `type`
                   " . $ci->db->escape_str($select) . "
            FROM " . TBL_album . ",
                 " . TBL_artist . ",
                 " . TBL_listening . ",
                 " . TBL_user . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_artist . ".`artist_name` LIKE ?
              AND " . TBL_album . ".`album_name` LIKE ?
              AND " . TBL_album . ".`year` LIKE ?
              AND " . TBL_user . ".`username` LIKE ?
              " . $where . "
              GROUP BY " . $ci->db->escape_str($group_by) . "
              ORDER BY " . $ci->db->escape_str($order_by) . "
              LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $artist_name, $album_name, $tag_id, $username));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Returns all years.
  * @param array $opts.
  *          'human_readable'  => Output format
  *
  * @return string JSON encoded the data
  */
if (!function_exists('getAllYears')) {
  function getAllYears($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $sql = "SELECT DISTINCT 'year' AS `type`,
                   " . TBL_album . ".`year`
            FROM " . TBL_album . "
            WHERE 1
            ORDER BY " . TBL_album . ".`year`";
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
if (!function_exists('getYearsCumulative')) {
  function getYearsCumulative($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '%';
    $username = !empty($opts['username']) ? $opts['username'] : '%';
    $sql = "SELECT DATE_FORMAT(`date`, '%Y%m') AS `line_date`,
                   (SELECT COUNT(*) 
                    FROM " . TBL_listening . ",
                         " . TBL_user . ",
                         " . TBL_album . "
                   WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
                      AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
                      AND " . TBL_album . ".`year` = ?
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
  * Gets year's bio.
  *
  * @param array $opts.
  *          'tag_id'  => Year
  *
  * @return array Year bio
  */
if (!function_exists('getYearBio')) {
  function getYearBio($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $year = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    $sql = "SELECT " . TBL_year_biography . ".`id` AS `biography_id`,
                   " . TBL_year_biography . ".`summary` AS `bio_summary`, 
                   " . TBL_year_biography . ".`text` AS `bio_content`, 
                   " . TBL_year_biography . ".`updated` AS `bio_updated`,
                   'false' AS `update_bio`
            FROM " . TBL_year_biography . "
            WHERE " . TBL_year_biography . ".`year` = ?";
    $query = $ci->db->query($sql, array($year));
    return ($query->num_rows() > 0) ? $query->result_array()[0] : array('update_bio' => false);
  }
}

/**
  * Add nationality's bio.
  *
  * @param array $opts.
  *          'tag_id'       => Year
  *          'bio_summary'  => Bio summary
  *          'bio_content'  => Bio content
  *
  * @return retun boolean TRUE or FALSE
  */
if (!function_exists('addYearBio')) {
  function addYearBio($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $year = !empty($opts['tag_id']) ? $opts['tag_id'] : '';
    $summary = !empty($opts['bio_summary']) ? $opts['bio_summary'] : '';
    $text = !empty($opts['bio_content']) ? $opts['bio_content'] : '';

    $sql = "SELECT  " . TBL_year_biography . ".`id`
            FROM " . TBL_year_biography . "
            WHERE " . TBL_year_biography . ".`year` = ?";
    $query = $ci->db->query($sql, array($year));
    if ($query->num_rows() === 1) {
      $sql = "UPDATE " . TBL_year_biography . "
                SET " . TBL_year_biography . ".`summary` = ?,
                    " . TBL_year_biography . ".`text` = ?,
                    " . TBL_year_biography . ".`updated` = NOW()
                WHERE " . TBL_year_biography . ".`year` = ?";
      $query = $ci->db->query($sql, array($summary, $text, $year));
    }
    else {
      $sql = "INSERT
                INTO " . TBL_year_biography . " (`year`, `summary`, `text`)
                VALUES (?, ?, ?)";
      $query = $ci->db->query($sql, array($year, $summary, $text));
    }
    return ($ci->db->affected_rows() === 1);
  }
}

/**
   * Gets year's listenings.
   *
   * @param array $opts.
   *          'tag_id'      => Year
   *          'user_id'     => User ID
   *
   * @return array Listening information.
   *
   */
if (!function_exists('getYearListenings')) {
  function getYearListenings($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $count_type = empty($opts['user_id']) ? 'total_count' : 'user_count';
    $tag_id = empty($opts['tag_id']) ? '%' : $opts['tag_id'];
    $user_id = empty($opts['user_id']) ? '%' : $opts['user_id'];
    $sql = "SELECT count(*) AS `" . $count_type . "`
            FROM " . TBL_album . ",
                 " . TBL_listening . "
            WHERE " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` LIKE ?
              AND " . TBL_album . ".`year` = ?";
    $query = $ci->db->query($sql, array($user_id, $tag_id));
    return ($query->num_rows() > 0) ? $query->result_array()[0] : array($count_type => 0);
  }
}

/**
  * Returns top music for given year.
  *
  * @param array $opts.
  *          'group_by'        => Group by argument
  *          'human_readable'  => Output format
  *          'limit'           => Limit
  *          'order_by'        => Order by argument
  *          'tag_id'          => Year
  *
  * @return string JSON encoded data containing album information.
  *
  **/
if (!function_exists('getMusicByYear')) {
  function getMusicByYear($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $group_by = !empty($opts['group_by']) ? $opts['group_by'] :  '`album_id`';
    $limit = !empty($opts['limit']) ? $opts['limit'] : 10;
    $lower_limit = !empty($opts['lower_limit']) ? $opts['lower_limit'] : date('Y-m-d', time() - (31 * 24 * 60 * 60));
    $order_by = !empty($opts['order_by']) ? $opts['order_by'] : '`count` DESC, ' . TBL_album . '.`album_name` ASC';
    $tag_id = !empty($opts['tag_id']) ? $opts['tag_id'] : '%';
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
                 " . TBL_user . "
            WHERE " . TBL_artist . ".`id` = " . TBL_album . ".`artist_id` 
              AND " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
              AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
              AND " . TBL_listening . ".`date` BETWEEN ? AND ?
              AND " . TBL_user . ".`username` LIKE ?
              AND " . TBL_album . ".`year` LIKE ?
            GROUP BY " . $ci->db->escape_str($group_by) . "
            ORDER BY " . $ci->db->escape_str($order_by) . " 
            LIMIT " . $ci->db->escape_str($limit);
    $query = $ci->db->query($sql, array($lower_limit, $upper_limit, $username, $tag_id));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}

/**
  * Returns top album for each year.
  *
  * @param array $opts.
  *
  * @return string JSON encoded data containing album information.
  *
  **/
if (!function_exists('getTopAlbumByYear')) {
  function getTopAlbumByYear($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $username = !empty($opts['username']) ? $opts['username'] : '%';

    $sql = "SELECT * 
            FROM (SELECT count(*) AS `count`,
                        " . TBL_artist . ".`artist_name`,
                        " . TBL_artist . ".`id` AS `artist_id`,
                        " . TBL_album . ".`album_name`,
                        " . TBL_album . ".`id` AS `album_id`,
                        " . TBL_album . ".`year`
                  FROM " . TBL_listening . ",
                       " . TBL_album . ",
                       " . TBL_user . ",
                       " . TBL_artist . "
                  WHERE " . TBL_listening . ".`album_id` = " . TBL_album . ".`id`
                    AND " . TBL_album . ".`artist_id` = " . TBL_artist . ".id
                    AND " . TBL_listening . ".`user_id` = " . TBL_user . ".`id`
                    AND " . TBL_user . ".`username` LIKE ?
                  GROUP BY " . TBL_album . ".`id`
                  ORDER by " . TBL_album . ".`year` DESC, `count` DESC) AS `result`
            GROUP BY `result`.`year`
            ORDER BY `result`.`year` DESC";
    $query = $ci->db->query($sql, array($username));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}
