<?php
$json_data = json_decode($json_data);
$size = isset($size) ? $size : 64;
if (is_array($json_data)) {
  if (!empty($limit)) {
    shuffle($json_data);
    $json_data = array_slice($json_data, 0, 2);
  }
  $rank = 1;
  $prev_count = FALSE;
  foreach ($json_data as $idx => $row) {
    ?>
    <tr id="albumTable<?=$idx?>">
      <?php
      if (empty($hide['rank'])) {
        ?>
        <td class="rank">
          <?php
          if ($row->count != $prev_count) {
            ?>
            <span class="rank"><?=$rank?>.</span>
            <?php
          }
          ?>
        </td>
        <?php
      }
      if (empty($hide['calendar'])) {
        ?>
        <td class="barChartCalendar">
          <?php
          $timestamp = strtotime($row->date);
          ?>
          <span class="month"><?=date('M', $timestamp)?></span>
          <span class="day"><?=date('j', $timestamp)?></span>
        </td>
        <?php
      }
      ?>
      <td class="img<?=$size?> <?=$img?>Img">
      <?php
      switch ($img) {
        case 'album':
          echo anchor(array('music', url_title($row->artist_name), url_title($row->album_name)), '<img src="' . getAlbumImg(array('album_id' => $row->album_id, 'size' => $size)) . '" alt="" class="albumImg albumImg' . $size . '" />', array('title' => 'Browse to album\'s page'));
          break;
        case 'user':
          echo anchor(array('user', 'profile', url_title($row->username)), '<img src="' . getUserImg(array('user_id' => $row->user_id, 'size' => $size)) . '" alt="" class="userImg userImg' . $size . '" />', array('title' => 'Browse to user\'s page'));
          break;
      }
      ?>
      </td>
      <td class="title">
        <div class="title">
          <?php
          switch ($title) {
            case 'music':
              if (empty($hide['artist'])) {
                echo anchor(array('music', url_title($row->artist_name)), $row->artist_name, array('title' => 'Browse to artist\'s page'));
                echo ' ' . DASH . ' ';
              }
              echo anchor(array('music', url_title($row->artist_name), url_title($row->album_name)), $row->album_name, array('title' => 'Browse to album\'s page'));
              echo ' ';
              echo anchor(array('tag', 'release+year', url_title($row->year)), '<span class="albumYear">(' . $row->year . ')</span>', array('title' => 'Browse albums'));
              break;
            case 'user':
              echo anchor(array('user', 'profile', url_title($row->username)), $row->username, array('title' => 'Browse to user profile'));
              break;
          }
          ?>
        </div>
        <?php
        if (empty($hide['date'])) {
          ?>
          <div class="datetime">
            <?=timeAgo($row->date)?>
          </div>
          <?php
        }
        if (empty($hide['count'])) {
          ?>
          <div class="count">
            <?=$row->count?> listenings
          </div>
          <?php
        }
        ?>
      </td>
    </tr>
    <?php
    if ($row->count != $prev_count) {
      $rank++;
    }
    $prev_count = $row->count;
  }
}
else {
  echo $json_data->error->msg;
}
?>