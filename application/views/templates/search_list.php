  <?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    $result_id = 0;
    foreach ($json_data as $idx => $row) {
      if (isset($row['type'])) {
        $result_id++;
        if ($row['type'] === 'album') {
          echo '<li class="' . ($result_id % 2 ? 'even' : 'odd') . '">' . anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<div class="cover album_img img64" style="background-image:url(' . getAlbumImg(array('album_id' => $row['album_id'], 'size' => 64)) . ')"></div><div class="title">' . $row['artist_name'] . ' – ' . substrwords($row['album_name'], 50) . '</div>', array('title' => 'Browse to album\'s page')) . '</li>';
        }
        else if ($row['type'] === 'artist') {
          echo '<li class="' . ($result_id % 2 ? 'even' : 'odd') . '">' . anchor(array('music', url_title($row['artist_name'])), '<div class="cover artist_img img64" style="background-image:url(' . getArtistImg(array('artist_id' => $row['artist_id'], 'size' => 64)) . ')"></div><div class="title">' . $row['artist_name'] . '</div>', array('title' => 'Browse to artist\'s page')) . '</li>';
        }
        else if ($row['type'] === 'genre') {
          echo '<li class="' . ($result_id % 2 ? 'even' : 'odd') . ' no_img"><i class="fa fa-music"></i>' . anchor(array('genre', url_title($row['value'])), '<div class="title">' . $row['value'] . '</div>', array('title' => 'Browse to genre\'s page')) . '</li>';
        }
        else if ($row['type'] === 'keyword') {
          echo '<li class="' . ($result_id % 2 ? 'even' : 'odd') . ' no_img"><i class="fa fa-tag"></i>' . anchor(array('keyword', url_title($row['value'])), '<div class="title">' . $row['value'] . '</div>', array('title' => 'Browse to keyword\'s page')) . '</li>';
        }
        else if ($row['type'] === 'nationality') {
          echo '<li class="' . ($result_id % 2 ? 'even' : 'odd') . ' no_img"><i class="fa fa-flag"></i>' . anchor(array('nationality', url_title($row['value'])), '<div class="title">' . $row['value'] . '</div>', array('title' => 'Browse to nationality\'s page')) . '</li>';
        }
        else if ($row['type'] === 'year') {
          echo '<li class="' . ($result_id % 2 ? 'even' : 'odd') . ' no_img"><i class="fa fa-hashtag"></i>' . anchor(array('year', url_title($row['value'])), '<div class="title">' . $row['value'] . '</div>', array('title' => 'Browse to year\'s page')) . '</li>';
        }
        else if ($row['type'] === 'user') {
          echo '<li class="' . ($result_id % 2 ? 'even' : 'odd') . '">' . anchor(array('user', url_title($row['value'])), '<div class="cover artist_img img64" style="background-image:url(' . $row['img'] . ')"></div><div class="title">' . $row['value'] . '</div>', array('title' => 'Browse to user\'s page')) . '</li>';
        }
      }
      else if ($row['value'] === '') {
        $result_id = 0;
        echo '<li class="title"><h3><span class="title">' . $row['label'] . '</span></h3></li>';
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