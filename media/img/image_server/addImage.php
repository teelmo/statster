<?php
include('./constants.php');

if ($data['type'] === FALSE || $data['id'] === FALSE || $data['uri'] === FALSE) {
  header('HTTP/1.1 400 Bad Request');
  exit('No direct script access allowed');
}

if (!function_exists('fetchImages')) {
  function fetchImages($opts, $target_folder) {
    foreach (IMAGE_SIZES as $key => $imagesize) {
      if ($target_folder === 'album_img') {
        imageResize($opts['uri'], getcwd() . '/' . $target_folder . '/' . $imagesize . '/' . $opts['id'] . '.jpg', $imagesize);
      }
      else if ($target_folder === 'artist_img') {
        imageResize($opts['uri'], getcwd() . '/' . $target_folder . '/' . $imagesize . '/' . $opts['id'] . '.jpg', $imagesize);
      }
    }
  }
}

if (!function_exists('imageResize')) {
  function imageResize($src, $dst, $dst_width) {
    if (!list($src_width, $src_height, $type, $attr) = getimagesize($src)) return 'Unsupported picture type!';
    switch ($type) {
      case 18: $img = imagecreatefromwebp($src); break;
      case 6: $img = imagecreatefromwbmp($src); break;
      case 1: $img = imagecreatefromgif($src); break;
      case 2: $img = imagecreatefromjpeg($src); break;
      case 3: $img = imagecreatefrompng($src); break;
      default : return 'Unsupported picture type!';
    }
    $dst_height = ($src_height * $dst_width) / $src_width;
    $new = imagecreatetruecolor($dst_width, $dst_height);
    imagecopyresampled($new, $img, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
    imagejpeg($new, $dst);
    // Free up memory.
    imagedestroy($new);
    header('HTTP/1.1 200 Ok');
    return site_url() . $dst;
  }
}

switch ($data['type']) {
  case 'album':
    echo fetchImages($data, 'album_img');
    break;
  case 'artist':
    echo fetchImages($data, 'artist_img');
    break;
  case 'user':
    echo fetchImages($data, 'user_img');
    break;
  default:
    exit('No direct script access allowed');
    header('HTTP/1.1 400 Bad Request');
}
?>