<?php
$json_data = json_decode($json_data);
foreach($json_data as $idx => $row) {
  ?>
  <tr id="<?=$idx?>">
    <td class="img albumImg">
      <img src="<?=getAlbumImg(array('album_id' => $row->album_id, 'size' => 32))?>" alt="" class="albumImg albumImg32"/>
    </td>
    <td class="title">
      <span class="title">
        <?=$row->artist_name?>
        <?=DASH?>
        <?=$row->album_name?>
        <span class="albumYear">(<?=$row->year?>)</td></span>
      </span>
    <td class="love">
      <?=($row->love == 1) ? '<span class="loveIcon" title=""></span>' : ''?>
    </td>
    <td class="datetime"><?=$row->created?></td>
    <td class="img userImg">
      <img src="<?=getUserImg(array('user_id' => $row->user_id, 'size' => 32))?>" alt="" class="userImg userImg32"/>
    </td>
  </tr>
  <?php
}
?>
