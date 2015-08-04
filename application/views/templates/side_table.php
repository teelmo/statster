<?php
if (!empty($json_data)) {
  $size = isset($size) ? $size : 64;
  if (is_array($json_data)) {
    if (!empty($limit)) {
      shuffle($json_data);
      $json_data = array_slice($json_data, 0, $limit);
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
            if ($row['count'] != $prev_count) {
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
            list($date['year'], $date['month'], $date['day']) = explode('-', $row['date']);
            if ($date['month'] == '00' || $date['day'] == '00') {
              $month = '–';
              $day = '–';
            }
            else {
              $timestamp = strtotime($row['date']);
              $month = date('M', $timestamp);
              $day = date('j', $timestamp);
            } 
            ?>
            <span class="month"><?=$month?></span>
            <span class="day"><?=$day?></span>
          </td>
          <?php
        }
        if (empty($row['album_name'])) {
          ?>
          <td class="img<?=$size?> artistImg">
            <?=anchor(array('music', url_title($row['artist_name'])), '<img src="' . getArtistImg(array('artist_id' => $row['artist_id'], 'size' => $size)) . '" alt="" class="artistImg img' . $size . '" />', array('title' => 'Browse to artist\'s page'))?>
          </td>
          <?php
        }
        else {
          ?>
          <td class="img<?=$size?> albumImg">
            <?=anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<img src="' . getAlbumImg(array('album_id' => $row['album_id'], 'size' => $size)) . '" alt="" class="albumImg img' . $size . '" />', array('title' => 'Browse to album\'s page'))?>
          </td>
          <?php
        }
        ?>
        <td class="title">
          <div class="title">
            <?php
            if (empty($hide['artist'])) {
              echo anchor(array('music', url_title($row['artist_name'])), $row['artist_name'], array('title' => 'Browse to artist\'s page'));
              if (!empty($row['album_name'])) {
              echo ' ' . DASH . ' ';
              }
            }
            if (!empty($row['album_name'])) {
              echo anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), $row['album_name'], array('title' => 'Browse to album\'s page'));
              echo ' ';
              echo anchor(array('tag', 'release+year', url_title($row['year'])), '<span class="albumYear">(' . $row['year'] . ')</span>', array('title' => 'Browse release year'));
            }
            ?>
          </div>
          <?php
          if (empty($hide['date'])) {
            ?>
            <div class="datetime">
              <?=timeAgo($row['date'])?> by <?=anchor(array('user', url_title($row['username'])), $row['username'], array('title' => 'Browse to user\'s page'))?>
            </div>
            <?php
          }
          if (empty($hide['count'])) {
            ?>
            <div class="count">
              <?=$row['count']?> listenings
            </div>
            <?php
          }
          ?>
        </td>
      </tr>
      <?php
      if ($row['count'] != $prev_count) {
        $rank++;
      }
      $prev_count = $row['count'];
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