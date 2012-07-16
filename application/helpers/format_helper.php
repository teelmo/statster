<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('addListeningFormat')) {
  function addListeningFormat($data = array()) {
    $ci=& get_instance();
    $ci->load->database();
    $ci->load->helper(array('id_helper'));

    $data['format_id'] = getFormatID($data);
    $data['format_type_id'] = getFormatTypeID($data);
    
    $data['formats_id'] = addListeningFormats($data);
    $data['format_types_id'] = addListeningFormatTypes($data);

    return TRUE;
  }
}

if (!function_exists('addListeningFormats')) {
  function addListeningFormats($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $format_id = isset($opts['format_id']) ? $opts['format_id'] : '';
    $listening_id = $opts['listening_id'];
    $user_id = $opts['user_id'];
    
    $sql = "INSERT
              INTO " . TBL_listening_formats . " (`listening_id`, `listening_format_id`, `user_id`) 
              VALUES (" . $ci->db->escape($listening_id) . ", " . $ci->db->escape($format_id) . ", " . $ci->db->escape($user_id) . ")";
    $query = $ci->db->query($sql);
    if($ci->db->affected_rows() == 1) {
      return $ci->db->insert_id();
    }
    else {
      return FALSE;
    }
  }
}

if (!function_exists('addListeningFormatTypes')) {
  function addListeningFormatTypes($opts = array()) {
    $ci=& get_instance();
    $ci->load->database();

    $format_type_id = isset($opts['format_type_id']) ? $opts['format_type_id'] : '';
    $listening_id = $opts['listening_id'];
    $user_id = $opts['user_id'];

    $sql = "INSERT
              INTO " . TBL_listening_format_types . " (`listening_id`, `listening_format_type_id`, `user_id`)
              VALUES (" . $ci->db->escape($listening_id) . ", " . $ci->db->escape($format_type_id) . ", " . $ci->db->escape($user_id) . ")";
    $query = $ci->db->query($sql);
    if($ci->db->affected_rows() == 1) {
      return $ci->db->insert_id();
    }
    else {
      return FALSE;
    }
  }
}

?>