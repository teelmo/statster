<?php 
if (!defined('BASEPATH')) exit ('No direct script access allowed');

if (!function_exists('indentJSON')) {
  /**
  * Indents a flat JSON string to make it more human-readable.
  * @see http://recursive-design.com/blog/2008/03/11/format-json-with-php/
  *
  * @param string $json The original JSON string to process.
  *
  * @return string Indented version of the original JSON string.
  */
  function indentJSON($json) {
    $result      = '';
    $pos         = 0;
    $strLen      = strlen($json);
    $indentStr   = '  ';
    $newLine     = "\n";
    $prevChar    = '';
    $outOfQuotes = true;

    for ($ii = 0; $ii <= $strLen; $ii++) {
      // Grab the next character in the string.
      $char = substr($json, $ii, 1);

      // Are we inside a quoted string?
      if ($char == '"' && $prevChar != '\\') {
        $outOfQuotes = !$outOfQuotes;
        // If this character is the end of an element, 
        // output a new line and indent the next line.
      } 
      elseif (($char == '}' || $char == ']') && $outOfQuotes) {
        $result .= $newLine;
        $pos--;
        for ($jj = 0; $jj < $pos; $jj++) {
          $result .= $indentStr;
        }
      }
      
      // Add the character to the result string.
      $result .= $char;

      // If the last character was the beginning of an element, 
      // output a new line and indent the next line.
      if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
        $result .= $newLine;
        if ($char == '{' || $char == '[') {
          $pos ++;
        }
        for ($jj = 0; $jj < $pos; $jj++) {
          $result .= $indentStr;
        }
      }
      
      $prevChar = $char;
    }

    return '<pre>' . $result . '</pre>';
  }
}

if (!function_exists('timeAgo')) {
  /**
  * Converts datetimes to timeago strings
  * @see http://tutorialfeed.net/development/timeago-style-php-timestamps
  *
  * @param string $ptime Datetime.
  *
  * @return string Time ago style formatted version of the given datetime.
  */
  function timeAgo($ptime, $cur_date = false) {
    $cur_date = ($cur_date) ? $cur_date : date('Y-m-d');
    $etime = (new DateTime($cur_date . ' 12:00:00 GMT'))->format('U') - (new DateTime(substr($ptime, 0, 10) . ' 12:00:00 GMT'))->format('U');
    if ($etime === 0) {
      return '<span title="' . $ptime . '">today</span>';
    }
    else {
      $a = array(
        12 * 30.5 * 24 * 60 * 60 => 'year',
        30.5 * 24 * 60 * 60 => 'month',
        14.5 * 24 * 60 * 60 => 'week',
        24 * 60 * 60 => 'day',
        60 * 60 => 'today',
      );
      foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
          $r = round($d);
          if ($etime < (48 * 60 * 60)) {
            return '<span title="' . $ptime . '">yesterday</span>';
          }
          else {
            return '<span title="' . $ptime . '"><span class="number">' . $r . '</span> ' . $str . ($r > 1 ? 's' : '') . ' ago</span>';
          }
        }
      }
    }
  }
}

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

        // Load helpers
        $ci->load->helper(array('text_helper'));
        header('HTTP/1.1 200 OK');
        return indentJSON(json_encode($query->result()));
      }
      else {
        header('HTTP/1.1 200 OK');
        return json_encode($query->result());
      }
      header('HTTP/1.1 400 Bad Request');
      return json_encode(array('error' => array('msg' => ERR_GENERAL)));
    }
    else {
      return header('HTTP/1.1 204 No Content');
    }
  }
}

/**
  * A helper function for cutting long strings.
  *
  * @param string $text.
  *
  * @param int $maxchar.
  *
  * @param string $end.
  *
  * @return string.
  */
function substrwords($text, $maxchar = 35, $end = '…') {
  if (strlen($text) > $maxchar || $text == '') {
    $words = preg_split('/\s/', $text);      
    $output = '';
    $i = 0;
    while (1) {
      $length = strlen($output) + strlen($words[$i]);
      if ($length > $maxchar) {
        break;
      } 
      else {
        $output .= ' ' . $words[$i];
        ++$i;
      }
    }
    $output = substr($output, 1);
    $output .= $end;
  } 
  else {
    $output = $text;
  }
  return $output;
}

if (!function_exists('cal_days_in_month')) { 
  function cal_days_in_month($calendar, $month, $year) { 
    return date('t', mktime(0, 0, 0, $month, 1, $year)); 
  } 
} 
if (!defined('CAL_GREGORIAN')) {
  define('CAL_GREGORIAN', 1);   
}
