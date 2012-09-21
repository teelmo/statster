<?php
$json_data = json_decode($json_data);
if (is_array($json_data)) {
  ?>
  <thead>
    <tr>
      <td colspan="6" id="recentlyUpdated" value=""></td>
    </tr>
  </thead>
  <tbody>
    <?php
    $justAdded = FALSE;
    foreach ($json_data as $idx => $row) {
      $class = '';
      $size = 32;
      $datetime = timeAgo($row->date);
      if ($idx == 0) {
        if ((time() - strtotime($row->created)) < JUST_LISTENED_INTERVAL && $row->date == CUR_DATE) {
          $class = 'justAdded';
          $size = 64;
          $datetime = '<span id="nowPlaying"></span> just listened';
          $justAdded = TRUE;
        }
      }
      elseif($justAdded === TRUE) {
        $class = 'justAddedRest';
      }
      ?>
      <tr id="chartTable<?=$idx?>" class="<?=$class?>">
        <td class="img albumImg">
          <?=anchor(array('music', url_title($row->artist_name), url_title($row->album_name)), '<img src="' . getAlbumImg(array('album_id' => $row->album_id, 'size' => $size)) . '" alt="" class="albumImg albumImg' . $size . '" />', array('title' => 'Browse to album\'s page'))?>
        </td>
        <td class="title">
          <span class="title">
            <?=anchor(array('music', url_title($row->artist_name)), $row->artist_name, array('title' => 'Browse to artist\'s page'))?>
            <?=DASH?>
            <?=anchor(array('music', url_title($row->artist_name), url_title($row->album_name)), $row->album_name, array('title' => 'Browse to album\'s page'))?>
            <?=anchor(array('tag', 'release-year', url_title($row->year)), '<span class="albumYear">(' . $row->year . ')</span>', array('title' => 'Browse albums'))?>
          </span>
        <td class="love icon">
          <?=(getAlbumLove(array('user_id' => $row->user_id, 'album_id' => $row->album_id)) == TRUE) ? '<span class="loveIcon" title="Loved"></span>' : ''?>
        </td>
        <td class="format icon">
          <?php
          $listeningsFormatImg = getListeningsFormatImg(array('listening_id' => $row->listening_id));
          ?>
          <img src="<?=$listeningsFormatImg['filename']?>" alt="" title="<?=$listeningsFormatImg['name']?>" class="middle icon listeningFormatType"/>
        </td>
        <td class="datetime textRight"><?=$datetime?></td>
        <td class="img userImg">
          <img src="<?=getUserImg(array('user_id' => $row->user_id, 'size' => $size))?>" alt="" class="userImg userImg<?=$size?>"/>
        </td>
      </tr>
      <?php
    }
    ?>
  </tbody>
  <?php
}
else {
  echo $json_data->error->msg;
}
?>