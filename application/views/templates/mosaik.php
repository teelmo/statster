<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    switch ($type) {
      case 'album':
        foreach ($json_data as $idx => $row) {
          ?>
          <li>
            <?=anchor(array(url_title($row['artist_name']), url_title($row['album_name'])), '<div class="cover album_img img150" style="background-image:url(' . getAlbumImg(array('album_id' => $row['album_id'], 'size' => 174)) . ')"></div>', array('title' => 'Browse to albums\'s page'))?>
          </li>
          <?php
        }
        break;
      case 'artist':
        foreach ($json_data as $idx => $row) {
          ?>
          <li>
            <?=anchor(array(url_title($row['artist_name'])), '<div class="cover artist_img img150" style="background-image:url(' . getArtistImg(array('artist_id' => $row['artist_id'], 'size' => 174)) . ')"></div>', array('title' => 'Browse to artist\'s page'))?>
          </li>
          <?php
        }
        break;
      case 'user':
        foreach ($json_data as $idx => $row) {
          ?>
          <li>
            <?=anchor(array('user', url_title($row['username'])), '<div class="cover user_img img150" style="background-image:url(' . getUserImg(array('user_id' => $row['user_id'], 'size' => 174)) . ')"></div>', array('title' => 'Browse to user\'s page'))?>
          </li>
          <?php
        }
        break;
    }
  }
  elseif (is_object($json_data)) {
    echo $json_data->error->msg;
  }
  else {
    echo $json_data;
  }
}
else {
  echo ERR_NO_RESULTS;
}
?>