<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * A helper function for returning music helper's responces.
 *
 * @param object $query.
 *
 * @param string $human_readable.
 *
 * @return string JSON encoded data containing album information or boolean   FALSE.
 */
if (!function_exists('_json_return_helper')) {
  function _json_return_helper($query, $human_readable) {
    if ($query->num_rows() > 0) {
      if (!empty($human_readable) && $human_readable != 'false') {
        $ci=& get_instance();
        $ci->load->database();

        // Load helpers
        $ci->load->helper(array('text_helper'));
        return indentJSON(json_encode($query->result()));
      }
      else {
        return json_encode($query->result());
      }
      return FALSE;
    }
    else {
      if (!empty($human_readable) && $human_readable != 'false') {
        $ci=& get_instance();
        $ci->load->database();

        // Load helpers
        $ci->load->helper(array('text_helper'));
        return '<pre>' . indent(json_encode(array('error' => array('msg' => ERR_NO_RESULTS)))) . '</pre>';
      }
      else {
        return json_encode(array('error' => array('msg' => ERR_NO_RESULTS)));
      }
      return FALSE;
    }
  }
}
?>