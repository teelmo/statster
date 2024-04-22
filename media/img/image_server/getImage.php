<?php
include('./constants.php');

if ($data['type'] === FALSE or $data['id'] === FALSE or in_array($data['size'], IMAGE_SIZES) === FALSE) {
  header('HTTP/1.1 400 Bad Request');
  exit('No direct script access allowed');
}

/**
  * Get path to art.
  *
  * @param array $opts.
  *          'size'      => Desired image size
  *          'id'        => Artist ID
  *
  * @return string Absolute path to image file.
  */
if (!function_exists('getImgPath')) {
  function getImgPath($opts = array()) {
    $filename = $opts['type'] . '_img/' . $opts['size'] . '/' . $opts['id'] . '.jpg';
    if (read_file('./' . $filename)) {
      header('HTTP/1.1 200 Ok');
      return site_url() . $filename;
    }
    else {
       header('HTTP/1.1 404 Not Found');
       return '';
    }
  }
}

switch ($data['type']) {
  case 'album':
    echo getImgPath($data);
    break;
  case 'artist':
    echo getImgPath($data);
    break;
  case 'user':
    echo getImgPath($data);
    break;
  default:
    header('HTTP/1.1 400 Bad Request');
    exit('No direct script access allowed');
}
?>