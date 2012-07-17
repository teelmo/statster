<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

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
        $pos --;
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