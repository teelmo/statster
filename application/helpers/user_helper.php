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

    $sql = "SELECT " . TBL_user . ".`id`,
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
              AND " . TBL_user . ".`username` = " . $ci->db->escape($username) . "
              AND " . TBL_user . ".`password` = '" . $password . "'";
    $query = $ci->db->query($sql);
    if ($query->num_rows() == 1) {
      $ci->load->helper(array('img_helper'));
      $result = $query->result();
      $result = $result[0];
      // http://codeigniter.com/user_guide/libraries/sessions.html
      $userdata = array(
                   'user_id'      => $result->id,
                   'username'     => $result->username,
                   'email'        => $result->email,
                   'real_name'    => $result->real_name,
                   'lastfm_name'  => $result->lastfm_name,
                   'gender'       => $result->gender,
                   'created'      => $result->created,
                   'last_login'   => $result->last_login,
                   'last_access'  => $result->last_access,
                   'user_image'   => getUserImg(array('user_id' => $result->id, 'size'    => 32)),
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

    $userdata = array('logged_in' => FALSE);
    $ci->session->set_userdata($userdata);
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

    $exclude_username = explode(',', $opts['exclude_username']);
    $sql = "SELECT " . TBL_user . ".`id`,
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
              AND " . TBL_username. ".`username` NOT IN (" . $exclude_username . ")";
    $query = $ci->db->query($sql);
  }
}  
?>