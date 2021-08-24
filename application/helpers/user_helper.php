<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Handles user login form posts.
  *
  * @param array $opts.
  *          'username'  => Username
  *          'password'  => Password
  *
  * @return array user session
  */
if (!function_exists('loginUser')) {
  function loginUser($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $username = trim($opts['username']);
    $password = md5(trim($opts['password']));

    $sql = "SELECT " . TBL_user . ".`id` as `user_id`,
                   " . TBL_user . ".`username`,
                   " . TBL_user . ".`created`,
                   " . TBL_user . ".`last_login`,
                   " . TBL_user . ".`last_access`,
                   " . TBL_user_info . ".`email`,
                   " . TBL_user_info . ".`real_name`,
                   " . TBL_user_info . ".`lastfm_name`,
                   " . TBL_user_info . ".`gender`,
                   " . TBL_user_info . ".`listening_formats`,
                   " . TBL_user_info . ".`time_interval_settings`
            FROM " . TBL_user . ",
                 " . TBL_user_info . "
            WHERE " . TBL_user . ".`id` = " . TBL_user_info . ".`user_id`
              AND " . TBL_user . ".`username` = ?
              AND " . TBL_user . ".`password` = ?";
    $query = $ci->db->query($sql, array($username, $password));
    if ($query->num_rows() === 1) {
      $result = $query->result()[0];
      // https://codeigniter.com/userguide3/libraries/sessions.html
      $ci->session->set_userdata(array(
        'user_id'      => $result->user_id,
        'username'     => $result->username,
        'get_username' => $result->username,
        'email'        => $result->email,
        'real_name'    => $result->real_name,
        'lastfm_name'  => $result->lastfm_name,
        'gender'       => $result->gender,
        'created'      => $result->created,
        'last_login'   => $result->last_login,
        'last_access'  => $result->last_access,
        'formats'      => $result->listening_formats,
        'intervals'    => $result->time_interval_settings,
        'user_image'   => getUserImg(array('user_id' => $result->user_id, 'size' => 64)),
        'logged_in'    => TRUE
      ));
      return TRUE;
    }
    else {
      return json_encode(array('error' => array('msg' => ERR_INCORRECT_CREDENTIALS)));
    }
  }
}

/**
  * Handles user register form posts.
  *
  * @param array $opts.
  *          'username'  => Username
  *          'password'  => Password
  *          'email'     => Email
  *
  * @return int user ID or boolean FALSE.
  */
if (!function_exists('registerUser')) {
  function registerUser($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $username = trim($opts['username']);
    $password = md5(trim($opts['password']));
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
  *
  * @return array containing user information
  *
  */
if (!function_exists('getUser')) {
  function getUser($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $username = !empty($opts['username']) ? $opts['username'] : '';

    $sql = "SELECT " . TBL_user . ".`id` as `user_id`,
                   " . TBL_user . ".`username`,
                   " . TBL_user_info . ".`real_name`,
                   " . TBL_user_info . ".`lastfm_name`,
                   " . TBL_user_info . ".`homepage`,
                   " . TBL_user_info . ".`blog`,
                   " . TBL_user_info . ".`email`,
                   " . TBL_user_info . ".`gender`,
                   " . TBL_user_info . ".`height`,
                   " . TBL_user_info . ".`birthday`,
                   " . TBL_user_info . ".`about`,
                   " . TBL_user_info . ".`email_annotations`,
                   " . TBL_user_info . ".`bulletin_settings`,
                   " . TBL_user_info . ".`privacy_settings`,
                   " . TBL_user_info . ".`social_media_settings`,
                   " . TBL_user_info . ".`listening_formats`,
                   " . TBL_user_info . ".`time_interval_settings`,
                   YEAR(" . TBL_user . ".`created`) as `joined_year`,
                   " . TBL_user . ".`created`,
                   " . TBL_user . ".`last_login`,
                   " . TBL_user . ".`last_access`
            FROM " . TBL_user . ",
                 " . TBL_user_info . "
            WHERE " . TBL_user . ".`id` = " . TBL_user_info . ".`user_id`
              AND " . TBL_user . ".`username` LIKE ?";
    $query = $ci->db->query($sql, array($username));
    return ($query->num_rows() > 0) ? $query->result_array()[0] : FALSE;
  }
}

/**
  * Returns user data for requested user.
  * @param array $opts.
  *             about         => About
  *             email         => Email
  *             gender        => Gender
  *             homepage      => Homepage
  *             lastfm_name   => Lastfm name
  *             real_name     => Real name
  *             user_id       => User ID
  *
  * @return boolean TRUE or FALSE.
  *
  */
if (!function_exists('updateUser')) {
  function updateUser($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $about = !empty($opts['about']) ? $opts['about'] : '';
    $bulletin_settings = !empty($opts['bulletin_settings']) ? serialize($opts['bulletin_settings']) : 'a:1:{s:6:"shouts";s:1:"0";}';
    $email = !empty($opts['email']) ? $opts['email'] : '';
    $email_annotations = !empty($opts['email_annotations']) ? serialize($opts['email_annotations']) : 'a:4:{s:9:"bulletins";s:1:"0";s:6:"shares";s:1:"0";s:7:"notifys";s:1:"0";s:13:"notifications";s:1:"0";}';
    $gender = !empty($opts['gender']) ? $opts['gender'] : '';
    $homepage = !empty($opts['homepage']) ? $opts['homepage'] : '';
    $lastfm_name = !empty($opts['lastfm_name']) ? $opts['lastfm_name'] : '';
    $listening_formats = !empty($opts['listening_formats']) ? serialize($opts['listening_formats']) : 'a:4:{i:0;s:1:"3";i:1;s:4:"6:17";i:2;s:4:"6:26";i:3;s:4:"7:31";}';
    $privacy_settings = !empty($opts['privacy_settings']) ? serialize($opts['privacy_settings']) : 'a:2:{s:6:"online";s:1:"0";s:5:"login";s:1:"0";}';
    $real_name = !empty($opts['real_name']) ? $opts['real_name'] : '';
    $social_media_settings = !empty($opts['social_media_settings']) ? serialize($opts['social_media_settings']) : 'a:2:{s:8:"facebook";s:1:"0";s:7:"twitter";s:1:"0";}';
    $user_id = !empty($opts['user_id']) ? $opts['user_id'] : '';

    $sql = "UPDATE " . TBL_user_info . "
              SET " . TBL_user_info . ".`real_name` = ?,
                  " . TBL_user_info . ".`gender` = ?,
                  " . TBL_user_info . ".`about` = ?,
                  " . TBL_user_info . ".`email` = ?,
                  " . TBL_user_info . ".`homepage` = ?,
                  " . TBL_user_info . ".`lastfm_name` = ?,
                  " . TBL_user_info . ".`privacy_settings` = ?,
                  " . TBL_user_info . ".`social_media_settings` = ?,
                  " . TBL_user_info . ".`email_annotations` = ?,
                  " . TBL_user_info . ".`bulletin_settings` = ?,
                  " . TBL_user_info . ".`listening_formats` = ?,
                  " . TBL_user_info . ".`updated` = NOW()
              WHERE " . TBL_user_info . ".`user_id` = ?";

    $ci->session->set_userdata(array(
      'user_id'      => $user_id,
      'email'        => $email,
      'real_name'    => $real_name,
      'lastfm_name'  => $lastfm_name,
      'gender'       => $gender,
      'formats'      => $listening_formats
    ));

    $query = $ci->db->query($sql, array($real_name, $gender, $about, $email, $homepage, $lastfm_name, $privacy_settings, $social_media_settings, $email_annotations, $bulletin_settings, $listening_formats, $user_id));
    return ($ci->db->affected_rows() === 1) ? TRUE : FALSE;
  }
}

/**
  * Returns user data for requested user.
  * @param array $opts.
  *             name          => Interval namel
  *             user_id       => User ID
  *             value         => Interval value
  *
  * @return boolean TRUE or FALSE.
  *
  */
if (!function_exists('updateIntervals')) {
  function updateIntervals($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    if ($user_id = $ci->session->userdata('user_id')) {
      $name = !empty($opts['name']) ? $opts['name'] : '';
      $value = !empty($opts['value']) ? $opts['value'] : '';
     
      $time_interval_settings = unserialize($ci->session->userdata('intervals'));
      $time_interval_settings[$name] = $value;
      $time_interval_settings = serialize($time_interval_settings);

      $sql = "UPDATE " . TBL_user_info . "
                SET " . TBL_user_info . ".`time_interval_settings` = ?,
                    " . TBL_user_info . ".`updated` = NOW()
                WHERE " . TBL_user_info . ".`user_id` = ?";

      $ci->session->set_userdata(array(
        'intervals' => $time_interval_settings
      ));
      $query = $ci->db->query($sql, array($time_interval_settings, $user_id));
      return ($ci->db->affected_rows() === 1) ? header('HTTP/1.1 204 No Content') : header('HTTP/1.1 400 Bad Request');
    }
    else {
      return header('HTTP/1.1 204 No Content');
    }
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
    if (!empty($tags_array[0]) || !empty($tags_array[1])) {
      $data = array();
      foreach ($tags_array as $idx => $tags) {
        foreach ($tags as $idx => $tag) {
          $data['tags'][] = $tag;
        }
      }
      uasort($data, '_tagsSortByCount');
      $data['tags'] = array_slice($data['tags'], 0, empty($opts['limit']) ? 6 : $opts['limit']);
      return $data;
    }
    return array('tags' => array());
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
            FROM " . TBL_genre . ",
                 " . TBL_album . ",
                 " . TBL_listening . ",
                 (SELECT " . TBL_genres . ".`genre_id`,
                         " . TBL_genres . ".`album_id`
                  FROM " . TBL_genres . "
                  GROUP BY " . TBL_genres . ".`genre_id`, " . TBL_genres . ".`album_id`) as " . TBL_genres . "
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
            FROM " . TBL_keyword . ",
                 " . TBL_album . ",
                 " . TBL_listening . ",
                 (SELECT " . TBL_keywords . ".`keyword_id`,
                         " . TBL_keywords . ".`album_id`
                  FROM " . TBL_keywords . "
                  GROUP BY " . TBL_keywords . ".`keyword_id`, " . TBL_keywords . ".`album_id`) as " . TBL_keywords . "
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