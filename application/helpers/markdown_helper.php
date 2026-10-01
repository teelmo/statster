<?php
if (!defined('BASEPATH')) exit ('No direct script access allowed');

if (!function_exists('renderMarkdown')) {
  /**
    * Renders Markdown text to safe HTML via Parsedown.
    *
    * Safe mode is always on: Parsedown allows raw HTML passthrough by
    * default, which would reopen the exact XSS gap this whole message
    * storage format change exists to close the moment a future compose
    * feature writes live user-submitted Markdown. Safe mode escapes any
    * raw HTML tags found in the source instead of passing them through.
    *
    * @param string $text Markdown source.
    *
    * @return string Rendered HTML.
    */
  function renderMarkdown($text) {
    $ci=& get_instance();
    $ci->load->library('parsedown');
    $ci->parsedown->setSafeMode(TRUE);
    return $ci->parsedown->text($text);
  }
}
?>
