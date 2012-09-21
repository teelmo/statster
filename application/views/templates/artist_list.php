<?php
$json_data = json_decode($json_data);
if (is_array($json_data)) {
  foreach ($json_data as $idx => $row) {
    ?>
    <li id="artistList<?=$idx?>">
      <?=anchor(array('music', url_title($row->artist_name)), '<img src="' . getArtistImg(array('artist_id' => $row->artist_id, 'size' => 124)) . '" alt="" class="artistImg artistImg124" />', array('title' => 'Browse to artist\'s page'))?>
      <?=anchor(array('music', url_title($row->artist_name)), '<span class="title">' . $row->artist_name . '</span>', array('title' => 'Browse to artist\'s page'))?><br />
      <span class="playCount"><?=$row->count?> plays</span>
    </li>
    <?php
  }
}
else {
  echo $json_data->error->msg;
}
?>