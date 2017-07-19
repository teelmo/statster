<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    ?>
    <div class="left_container">
      <?php
      if (!empty($json_data[0])) {
        if ($type == 'album') {
          echo anchor(array('music', url_title($json_data[0]['artist_name']), url_title($json_data[0]['album_name'])), '<div class="cover album_img img300" style="background-image:url(' . getAlbumImg(array('album_id' => $json_data[0]['album_id'], 'size' => 174)) . ')">' . anchor(array('music', url_title($json_data[0]['artist_name']), url_title($json_data[0]['album_name'])), '<span class="title">' . $json_data[0]['album_name'] . '</span>', array('title' => 'Browse to album\'s page')) . '</div>', array('title' => 'Browse to album\'s page'));
        }
        elseif ($type == 'artist') {
          echo anchor(array('music', url_title($json_data[0]['artist_name'])), '<div class="cover artist_img img300" style="background-image:url(' . getArtistImg(array('artist_id' => $json_data[0]['artist_id'], 'size' => 300)) . ')">' . anchor(array('music', url_title($json_data[0]['artist_name'])), '<span class="title">' . $json_data[0]['artist_name'] . '</span>', array('title' => 'Browse to artist\'s page')) . '</div>', array('title' => 'Browse to artist\'s page'));
        }
      }
      ?>
    </div>
    <div class="right_container">
      <?php
      for ($i = 1; $i <= ($limit - 1); $i++) {
        if (!empty($json_data[$i])) {
          if ($type == 'album') {
            echo anchor(array('music', url_title($json_data[$i]['artist_name']), url_title($json_data[$i]['album_name'])), '<div class="cover album_img img150" style="background-image:url(' . getAlbumImg(array('album_id' => $json_data[$i]['album_id'], 'size' => 174)) . ')">' . anchor(array('music', url_title($json_data[$i]['artist_name']), url_title($json_data[$i]['album_name'])), '<span class="title">' . $json_data[$i]['album_name'] . '</span>', array('title' => 'Browse to album\'s page')) . '</div>', array('title' => 'Browse to album\' s page'));
          }
          elseif ($type == 'artist') {
            echo anchor(array('music', url_title($json_data[$i]['artist_name'])), '<div class="cover artist_img img150" style="background-image:url(' . getArtistImg(array('artist_id' => $json_data[$i]['artist_id'], 'size' => 174)) . ')">' . anchor(array('music', url_title($json_data[$i]['artist_name'])), '<span class="title">' . $json_data[$i]['artist_name'] . '</span>', array('title' => 'Browse to artist\'s page')) . '</div>', array('title' => 'Browse to artist\'s page'));
          }
        }
      }
      ?>
    </div>
    <?php
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
