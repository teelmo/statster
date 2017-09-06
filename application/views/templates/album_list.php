<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      ?>
      <li>
        Released: <span class="album_year number"><?=anchor(array('year', $row['year']), $row['year'], array('title' => 'Browse release year'))?></span>
        <?=anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<div class="cover album_img img150" style="background-image:url(' . getAlbumImg(array('album_id' => $row['album_id'], 'size' => 174)) . ')">' . anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<span class="title">' . substrwords($row['album_name'], 35) . '</span>', array('title' => 'Browse to album\'s page')) . '</div>', array('title' => 'Browse to album\'s page'))?>
        <span class="play_count number"><?=$row['count']?></span> plays
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
  echo ERR_NO_RESULTS;
}
?>