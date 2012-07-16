<?php
$json_data = json_decode($json_data);
if (is_array($json_data)) {
  foreach ($json_data as $idx => $row) {
    ?>
    <tr id="<?=$idx?>">
      <td class="img albumImg">
        <?=anchor(array('music', url_title($row->artist_name), url_title($row->album_name)), '<img src="' . getAlbumImg(array('album_id' => $row->album_id, 'size' => 32)) . '" alt="" class="albumImg albumImg32" />', array('title' => 'Browse to album\'s page'))?>
      </td>
      <td class="title">
        <span class="title">
          <?=anchor(array('music', url_title($row->artist_name)), $row->artist_name, array('title' => 'Browse to artist\'s page'))?>
          <?=DASH?>
          <?=anchor(array('music', url_title($row->artist_name), url_title($row->album_name)), $row->album_name, array('title' => 'Browse to album\'s page'))?>
          <?=anchor(array('tag', 'release-year', url_title($row->year)), '<span class="albumYear">(' . $row->year . ')</span>', array('title' => 'Browse to album\'s page'))?>
        </span>
      <td class="format icon middle">
        <?php
        $listeningsFormatImg = getListeningsFormatImg(array('listening_format_id' => $row->listening_format_id, 'listening_format_type_id' => $row->listening_format_type_id));
        ?>
        <img src="<?=$listeningsFormatImg['filename']?>" alt="" title="<?=$listeningsFormatImg['name']?>"/>
      </td>
      <td class="love icon middle">
        <?=($row->love == 1) ? '<span class="loveIcon" title=""></span>' : ''?>
      </td>
      <td class="datetime"><?=$row->date?></td>
      <td class="img userImg">
        <img src="<?=getUserImg(array('user_id' => $row->user_id, 'size' => 32))?>" alt="" class="userImg userImg32"/>
      </td>
    </tr>
    <?php
  }
}
else {
  echo $json_data->error->msg;
}
?>