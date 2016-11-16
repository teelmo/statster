<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
  * Gets bulletins info.
  *
  * @param array $opts.
  *          'path'     => path
  *          'user_id'  => User ID
  *
  * @return array Artist information or boolean FALSE.
  */
if (!function_exists('getBulletins')) {
  function getBulletins($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $artist_name = !empty($opts['artist_name']) ? $opts['artist_name'] : '%';
    $sql = "SELECT " . TBL_bulletin . ".`id`,
                   " . TBL_bulletin . ".`user_id`,
                   " . TBL_bulletin . ".`sender_id`,
                   " . TBL_bulletin . ".`subject`,
                   " . TBL_bulletin . ".`message`,
                   " . TBL_bulletin . ".`state`,
                   " . TBL_bulletin . ".`path`,
                   " . TBL_bulletin . ".`localizable`,
                   " . TBL_bulletin . ".`date`
          FROM " . TBL_bulletin . "
          WHERE " . TBL_bulletin . ".`path` = ?
            AND " . TBL_bulletin . ".`user_id` = ?
          ORDER BY " . TBL_bulletin . ".`date` DESC";
    $query = $ci->db->query($sql, array($data['path'], $data['user_id']));
    return ($query->num_rows() > 0) ? $query->result_array()[0] : FALSE;
  }
}
?>