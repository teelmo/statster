<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    if (!empty($json_data[0])) {
      if ($type == 'album') {
        ?>
        <li>
          <?=anchor(array('music', url_title($json_data[0]['artist_name']), url_title($json_data[0]['album_name'])), '
            <div class="cover album_img img300" style="background-image:url(' . getAlbumImg(array('album_id' => $json_data[0]['album_id'], 'size' => 174)) . ')"></div><div class="meta"><div class="title main">' . anchor(array('music', url_title($json_data[0]['artist_name']), url_title($json_data[0]['album_name'])), $json_data[0]['album_name'], array('title' => 'Browse to album\'s page')) . '</div><div class="title">' . anchor(array('music', url_title($json_data[0]['artist_name'])), $json_data[0]['artist_name'], array('title' => 'Browse to artist\'s page')) . '</div><div class="title">' . $json_data[0]['count'] . ' listenings</div></div>
          ', array('title' => 'Browse to album\'s page'));
          ?>
        </li>
        <?php
      }
      elseif ($type == 'artist') {
        ?>
        <li>
          <?=anchor(array('music', url_title($json_data[0]['artist_name'])), '
            <div class="cover album_img img300" style="background-image:url(' . getAlbumImg(array('album_id' => $json_data[0]['album_id'], 'size' => 174)) . ')"></div><div class="meta"><div class="title main">' . anchor(array('music', url_title($json_data[0]['artist_name'])), $json_data[0]['artist_name'], array('title' => 'Browse to artist\'s page')) . '</div><div class="title">' . $json_data[0]['count'] . ' listenings</div></div>
          ', array('title' => 'Browse to artist\'s page'));
          ?>
        </li>
        <?php
      }
    }
    for ($i = 1; $i <= ($limit - 1); $i++) {
      if (!empty($json_data[$i])) {
        if ($type == 'album') {
          ?>
          <li>
            <?=anchor(array('music', url_title($json_data[$i]['artist_name']), url_title($json_data[$i]['album_name'])), '
              <div class="cover album_img img150" style="background-image:url(' . getAlbumImg(array('album_id' => $json_data[$i]['album_id'], 'size' => 174)) . ')"></div><div class="meta"><div class="title main">' . anchor(array('music', url_title($json_data[$i]['artist_name']), url_title($json_data[$i]['album_name'])), $json_data[$i]['album_name'], array('title' => 'Browse to album\'s page')) . '</div><div class="title">' . anchor(array('music', url_title($json_data[$i]['artist_name'])), $json_data[$i]['artist_name'], array('title' => 'Browse to artist\'s page')) . '</div><div class="title">' . $json_data[$i]['count'] . ' listenings</div></div>
            ', array('title' => 'Browse to album\'s page'));
            ?>
          </li>
          <?php
        }
        elseif ($type == 'artist') {
          ?>
          <li>
            <?=anchor(array('music', url_title($json_data[$i]['artist_name'])), '
              <div class="cover album_img img150" style="background-image:url(' . getAlbumImg(array('album_id' => $json_data[$i]['album_id'], 'size' => 174)) . ')"></div><div class="meta"><div class="title main">' . anchor(array('music', url_title($json_data[$i]['artist_name'])), $json_data[$i]['artist_name'], array('title' => 'Browse to artist\'s page')) . '</div><div class="title">' . $json_data[$i]['count'] . ' listenings</div></div>
            ', array('title' => 'Browse to artis\'s page'));
            ?>
          </li>
          <?php
        }
      }
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
