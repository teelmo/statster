<?php
$json_data = json_decode($json_data);
foreach($json_data as $idx => $row) {
  ?>
  <li id="<?=$idx?>">
    <span class="albumYear">Released: <?=anchor(array('tag', 'release-year', url_title($row->year)), $row->year, array('title' => 'Browse to album\'s page'))?></td></span>
    <?=anchor(array('music', url_title($row->artist_name), url_title($row->album_name)), '<img src="' . getAlbumImg(array('album_id' => $row->album_id, 'size' => 124)) . '" alt="" class="albumImg albumImg124" />', array('title' => 'Browse to album\'s page'))?>
    <?=anchor(array('music', url_title($row->artist_name), url_title($row->album_name)), '<span class="title">' . $row->album_name . '</span>', array('title' => 'Browse to album\'s page'))?><br />
    <span class="playCount"><?=$row->count?> plays</span>
  </li>
  <?php
}
?>