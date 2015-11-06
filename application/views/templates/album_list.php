<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      ?>
      <li>
        <span class="album_year">Released: <?=anchor(array('year', $row['year']), $row['year'], array('title' => 'Browse release year'))?></span>
        <?=anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<div class="cover album_img img124" style="background-image:url(' . getAlbumImg(array('album_id' => $row['album_id'], 'size' => 124)) . ')">' . anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<span class="title">' . $row['album_name'] . '</span>', array('title' => 'Browse to album\'s page')) . '</div>', array('title' => 'Browse to album\'s page'))?>
        <span class="playCount"><?=$row['count']?> plays</span>
      </li>
      <?php
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
  ?>
  No results
  <?php
}
?>