<?php
$json_data = json_decode($json_data);
foreach($json_data as $idx => $row) {
  ?>
  <tr id="<?=$idx?>">
    <td class="img albumImg">
      <?=anchor(array('music', url_title($row->artist_name), url_title($row->album_name)), '<img src="' . getAlbumImg(array('album_id' => $row->album_id, 'size' => 64)) . '" alt="" class="albumImg albumImg64" />', array('title' => 'Browse to album\'s page'))?>
    </td>
    <td class="title">
      <span class="title">
        <?=anchor(array('music', url_title($row->artist_name)), $row->artist_name, array('title' => 'Browse to artist\'s page'))?>
        <?=DASH?>
        <?=anchor(array('music', url_title($row->artist_name), url_title($row->album_name)), $row->album_name, array('title' => 'Browse to album\'s page'))?>
        <?=anchor(array('tag', 'release-year', url_title($row->year)), '<span class="albumYear">(' . $row->year . ')</td></span>', array('title' => 'Browse to album\'s page'))?>
      </span>
    </td>
  </tr>
  <?php
}
?>
