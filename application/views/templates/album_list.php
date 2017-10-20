<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      ?>
      <li>
        <div class="metainfo">Released: <span class="album_year number"><?=anchor(array('year', $row['year']), $row['year'], array('title' => 'Browse release year'))?></span></div>
        <?=anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<div class="cover album_img img150" style="background-image:url(' . getAlbumImg(array('album_id' => $row['album_id'], 'size' => 174)) . ')"><div class="meta"><div class="title main">' . anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), substrwords($row['album_name'], 35), array('title' => 'Browse to album\'s page')) . '</div><div class="title">' . anchor(array('music', url_title($row['artist_name'])), '' . substrwords($row['artist_name'], 35), array('title' => 'Browse to artist\'s page')) . '</div></div></div>', array('title' => 'Browse to album\'s page'))?>
        <div class="play_count number"><?=$row['count']?></div> plays
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