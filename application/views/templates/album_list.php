<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      ?>
      <li id="albumList<?=$idx?>">
        <span class="albumYear">Released: <?=anchor(array('tag', 'release+year', $row['year']), $row['year'], array('title' => 'Browse release year'))?></span>
        <?=anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<img src="' . getAlbumImg(array('album_id' => $row['album_id'], 'size' => 124)) . '" alt="" class="albumImg albumImg124" />', array('title' => 'Browse to album\'s page'))?>
        <?=anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<span class="title">' . $row['album_name'] . '</span>', array('title' => 'Browse to album\'s page'))?><br />
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