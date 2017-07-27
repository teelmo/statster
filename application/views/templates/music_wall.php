<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    if ($first = array_shift($json_data)) {
      if ($type == 'album') {
        echo '<li>' . anchor(array('music', url_title($first['artist_name']), url_title($first['album_name'])), '<div class="cover album_img img300" style="background-image:url(' . getAlbumImg(array('album_id' => $first['album_id'], 'size' => 174)) . ')"></div><div class="meta"><div class="title main">' . anchor(array('music', url_title($first['artist_name']), url_title($first['album_name'])), $first['album_name'], array('title' => 'Browse to album\'s page')) . '</div><div class="title">' . anchor(array('music', url_title($first['artist_name'])), $first['artist_name'], array('title' => 'Browse to artist\'s page')) . '</div><div class="title">' . $first['count'] . ' plays</div></div>
          ', array('title' => 'Browse to album\'s page')) . '</li>';
      }
      elseif ($type == 'artist') {
        echo '<li>' . anchor(array('music', url_title($first['artist_name'])), '<div class="cover artist_img img300" style="background-image:url(' . getArtistImg(array('artist_id' => $first['artist_id'], 'size' => 174)) . ')"></div><div class="meta"><div class="title main">' . anchor(array('music', url_title($first['artist_name'])), $first['artist_name'], array('title' => 'Browse to artist\'s page')) . '</div><div class="title">' . $first['count'] . ' plays</div></div>', array('title' => 'Browse to artist\'s page')) . '</li>';
      }
    }
    foreach ($json_data as $data) {
      if ($type == 'album') {
        echo '<li>' . anchor(array('music', url_title($data['artist_name']), url_title($data['album_name'])), '<div class="cover album_img img150" style="background-image:url(' . getAlbumImg(array('album_id' => $data['album_id'], 'size' => 174)) . ')"></div><div class="meta"><div class="title main">' . anchor(array('music', url_title($data['artist_name']), url_title($data['album_name'])), $data['album_name'], array('title' => 'Browse to album\'s page')) . '</div><div class="title">' . anchor(array('music', url_title($data['artist_name'])), $data['artist_name'], array('title' => 'Browse to artist\'s page')) . '</div><div class="title">' . $data['count'] . ' plays</div></div>', array('title' => 'Browse to album\'s page')) . '</li>';
      }
      elseif ($type == 'artist') {
        echo '<li>' . anchor(array('music', url_title($data['artist_name'])), '<div class="cover artist_img img150" style="background-image:url(' . getArtistImg(array('artist_id' => $data['artist_id'], 'size' => 174)) . ')"></div><div class="meta"><div class="title main">' . anchor(array('music', url_title($data['artist_name'])), $data['artist_name'], array('title' => 'Browse to artist\'s page')) . '</div><div class="title">' . $data['count'] . ' plays</div></div>', array('title' => 'Browse to artis\'s page')) . '</li>';
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
