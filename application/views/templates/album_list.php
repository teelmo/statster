<?php
$json_data = json_decode($json_data);
foreach($json_data as $idx => $row) {
  ?>
  <li id="<?=$idx?>">
    <span class="albumYear">Released: <?=$row->year?></td></span>
    <img src="<?=getAlbumImg(array('album_id' => $row->album_id, 'size' => 124))?>" alt="" class="albumImg albumImg124"/>
    <span class="title"><?=$row->album_name?></span><br />
    <span class="playCount"><?=$row->count?> plays</span>
  </li>
  <?php
}
?>