<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * A helper function for returning music helper's responces.
 *
 * @param object $query.
 *
 * @param string $human_readable.
 *
 * @return string JSON.
 */
if (!function_exists('_json_return_helper')) {
  function _json_return_helper($query, $human_readable) {
    if ($query->num_rows() > 0) {
      if (!empty($human_readable) && $human_readable != 'false') {
        $ci=& get_instance();
        $ci->load->database();

        // Load helpers
        $ci->load->helper(array('text_helper'));
        header("HTTP/1.1 200 OK");
        return indentJSON(json_encode($query->result()));
      }
      else {
        header("HTTP/1.1 200 OK");
        return json_encode($query->result());
      }
      header("HTTP/1.1 400 Bad Request");
      return json_encode(array('error' => array('msg' => ERR_GENERAL)));
    }
    else {
      if (!empty($human_readable) && $human_readable != 'false') {
        $ci=& get_instance();
        $ci->load->database();

        // Load helpers
        $ci->load->helper(array('text_helper'));
        header("HTTP/1.1 204 No Content");
        exit ();
      }
      else {
        header("HTTP/1.1 204 No Content");
        exit ();
      }
      header("HTTP/1.1 400 Bad Request");
      return json_encode(array('error' => array('msg' => ERR_GENERAL)));
    }
  }
}
?>