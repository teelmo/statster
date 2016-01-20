<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * Handles user login form posts.
 *
 * @param array $opts.
 *          'artist'  => Artist name
 *
 * @return int artist ID or boolean FALSE.
 */
if (!function_exists('loginUser')) {
  function loginUser($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $username = trim($opts['username']);
    $password = md5(trim($opts['password'])); 

    $sql = "SELECT " . TBL_user . ".`id` as `user_id`,
                   " . TBL_user . ".`username`,
                   " . TBL_user_info . ".`email`,
                   " . TBL_user_info . ".`real_name`,
                   " . TBL_user_info . ".`lastfm_name`,
                   " . TBL_user_info . ".`gender`,
                   " . TBL_user . ".`created`,
                   " . TBL_user . ".`last_login`,
                   " . TBL_user . ".`last_access`
            FROM " . TBL_user . ",
                 " . TBL_user_info . "
            WHERE " . TBL_user . ".`id` = " . TBL_user_info . ".`user_id`
              AND " . TBL_user . ".`username` = ?
              AND " . TBL_user . ".`password` = ?";
    $query = $ci->db->query($sql, array($username, $password));
    if ($query->num_rows() === 1) {
      $result = ${!${false}=$query->result()}[0];
      // http://codeigniter.com/user_guide/libraries/sessions.html
      $userdata = array(
                   'user_id'      => $result->user_id,
                   'username'     => $result->username,
                   'email'        => $result->email,
                   'real_name'    => $result->real_name,
                   'lastfm_name'  => $result->lastfm_name,
                   'gender'       => $result->gender,
                   'created'      => $result->created,
                   'last_login'   => $result->last_login,
                   'last_access'  => $result->last_access,
                   'user_image'   => getUserImg(array('user_id' => $result->user_id, 'size' => 64)),
                   'logged_in'    => TRUE
               );
      $ci->session->set_userdata($userdata);
    }
    else {
      return json_encode(array('error' => array('msg' => ERR_INCORRECT_CREDENTIALS)));
    }
  }
}

/**
   * Handles user logout.
   *
   * @param array $opts.
   *
   * @return boolean TRUE
   */
if (!function_exists('logoutUser')) {
  function logoutUser($opts = array()) {
    $ci=& get_instance();

    $ci->session->sess_destroy();
    return TRUE;
  }
}
/**
   * Returns all users in the system and their information.
   *
   * @param array $opts.
   *          'excluded_username'  => array of usernames
   *
   * @return array containing user information
   *
   */
if (!function_exists('getUsers')) {
  function getUsers($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $exclude_username = !empty($opts['exclude_username']) ? explode(',', $opts['exclude_username']) : 'admin,testi,admin,quest';
    $sql = "SELECT " . TBL_user . ".`id` as `user_id`,
                   " . TBL_user . ".`username`,
                   " . TBL_user_info . ".`email`,
                   " . TBL_user_info . ".`homepage`,
                   " . TBL_user_info . ".`real_name`,
                   " . TBL_user_info . ".`lastfm_name`,
                   " . TBL_user_info . ".`gender`,
                   " . TBL_user_info . ".`birthday`,
                   " . TBL_user_info . ".`about`,
                   " . TBL_user . ".`created`,
                   " . TBL_user . ".`last_login`,
                   " . TBL_user . ".`last_access`
            FROM " . TBL_user . ",
                 " . TBL_user_info . "
            WHERE " . TBL_user . ".`id` = " . TBL_user_info . ".`user_id`
              AND FIND_IN_SET(" . TBL_user. ".`username`, ?) = 0";
    $query = $ci->db->query($sql, array($exclude_username));

    $human_readable = !empty($opts['human_readable']) ? $opts['human_readable'] : FALSE;
    return _json_return_helper($query, $human_readable);
  }
}
/**
  * Returns user data for requested user.
  * @param array $opts.
  *          'username' => username
  */
if (!function_exists('getUserData')) {
  function getUser($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $username = !empty($opts['username']) ? $opts['username'] : '';
    $sql = "SELECT " . TBL_user . ".`id` as `user_id`,
                   " . TBL_user . ".`username`,
                   " . TBL_user_info . ".`email`,
                   " . TBL_user_info . ".`homepage`,
                   " . TBL_user_info . ".`real_name`,
                   " . TBL_user_info . ".`lastfm_name`,
                   " . TBL_user_info . ".`gender`,
                   " . TBL_user_info . ".`birthday`,
                   " . TBL_user_info . ".`about`,
                   YEAR(" . TBL_user . ".`created`) as `joined_year`,
                   " . TBL_user . ".`created`,
                   " . TBL_user . ".`last_login`,
                   " . TBL_user . ".`last_access`
            FROM " . TBL_user . ",
                 " . TBL_user_info . "
            WHERE " . TBL_user . ".`id` = " . TBL_user_info . ".`user_id`
              AND " . TBL_user . ".`username` LIKE ?";
    $query = $ci->db->query($sql, array($username));
    return ($query->num_rows() > 0) ? ${!${false}=$query->result_array()}[0] : FALSE;
  }
}

/**
   * Gets user's tags (genres and keywords).
   *
   * @param array $opts.
   *          'album_id'  => Album ID
   *          'user_id'  => User ID
   *
   * @return array User information.
   *
   */
if (!function_exists('getUserTags')) {
  function getUserTags($opts = array()) {
    $tags_array = array();
    $tags_array[] = getUserGenres($opts);
    $tags_array[] = getUserKeywords($opts);
    if (is_array($tags_array)) {
      $data = array();
      foreach ($tags_array as $idx => $tags) {
        foreach ($tags as $idx => $tag) {
          $data['tags'][] = $tag;
        }
      }
      uasort($data, '_tagsSortByCount');
      $data['tags'] = array_slice($data['tags'], 0, empty($opts['limit']) ? 8 : $opts['limit']);
      return $data;
    }
    return array();
  }
}

/**
   * Gets user's genres.
   *
   * @param array $opts.
   *          'user_id'  => User ID
   *
   * @return array user's Keyword information.
   *
   */
if (!function_exists('getUserGenres')) {
  function getUserGenres($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $user_id = empty($opts['user_id']) ? '%' : $opts['user_id'];
    $sql = "SELECT " . TBL_genre . ".`name`, count(" . TBL_genre . ".`id`) as `count`, 'genre' as `type`
            FROM " . TBL_genre . ", " . TBL_genres . ", " . TBL_album . ", " . TBL_listening . "
            WHERE " . TBL_album . ".`id` = " . TBL_genres . ".`album_id`
              AND " . TBL_genre . ".`id` = " . TBL_genres . ".`genre_id`
              AND " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` = ?
            GROUP BY " . TBL_genre . ".`id`
            ORDER BY count(" . TBL_genre . ".`id`) DESC";
    $query = $ci->db->query($sql, array($user_id));
    return ($query->num_rows() > 0) ? $query->result_array() : array();
  }
}

/**
   * Gets user's keywords.
   *
   * @param array $opts.
   *          'user_id'  => User ID
   *
   * @return array user's Keyword information.
   *
   */
if (!function_exists('getUserKeywords')) {
  function getUserKeywords($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $opts['user_id'] = empty($opts['user_id']) ? '%' : $opts['user_id'];
    $sql = "SELECT " . TBL_keyword . ".`name`, count(" . TBL_keyword . ".`id`) as `count`, 'keyword' as `type`
            FROM " . TBL_keyword . ", " . TBL_keywords . ", " . TBL_album . ", " . TBL_listening . "
            WHERE " . TBL_album . ".`id` = " . TBL_keywords . ".`album_id`
              AND " . TBL_keyword . ".`id` = " . TBL_keywords . ".`keyword_id`
              AND " . TBL_album . ".`id` = " . TBL_listening . ".`album_id`
              AND " . TBL_listening . ".`user_id` = " . $opts['user_id'] . "
            GROUP BY " . TBL_keyword . ".`id`
            ORDER BY count(" . TBL_keyword . ".`id`) DESC";
    $query = $ci->db->query($sql);
    return ($query->num_rows() > 0) ? $query->result_array() : array();
  }
}

?>