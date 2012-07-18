<?php
$json_data = json_decode($json_data);
if (is_array($json_data)) {
  if (!empty($limit)) {
    shuffle($json_data);
    $json_data = array_slice($json_data, 0, 2);
  }
  foreach ($json_data as $idx => $row) {
    ?>
    <li id="albumList<?=$idx?>">
      <?=anchor(array('music', url_title($row->artist_name), url_title($row->album_name)), '<img src="' . getAlbumImg(array('album_id' => $row->album_id, 'size' => 64)) . '" alt="" class="albumImg albumImg64" />', array('title' => 'Browse to album\'s page'))?>
    </li>
    <?php
  }
}
else {
  echo $json_data->error->msg;
}
?>