<?php
if (!empty($json_data)) {
  $size = isset($size) ? $size : 64;
  $strlenght = isset($strlenght) ? $strlenght : 80;
  if (is_array($json_data)) {
    if (!empty($limit)) {
      shuffle($json_data);
      $json_data = array_slice($json_data, 0, $limit);
    }
    $rank = 1;
    $prev_count = FALSE;
    foreach ($json_data as $idx => $row) {
      ?>
      <tr data-created="<?=$row['created']?>">
        <?php
        if (empty($hide['rank'])) {
          ?>
          <td class="rank">
            <?php
            if ($row['count'] != $prev_count) {
              ?>
              <span class="rank number"><?=$rank?></span>
              <?php
            }
            ?>
          </td>
          <?php
        }
        if (empty($hide['calendar'])) {
          ?>
          <td class="calendar">
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
        if (empty($row['type'])) {
          if (isset($row['album_name']) && empty($hide['album'])) {
            ?>
            <td class="img<?=$size?> album_img">
              <?=anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<div class="cover album_img img' . $size . '" style="background-image:url(' . getAlbumImg(array('album_id' => $row['album_id'], 'size' => $size)) . ')"></div>', array('title' => 'Browse to album\'s page'))?>
            </td>
            <?php
          }
          else {
            ?>
            <td class="img<?=$size?> artist_img">
              <?=anchor(array('music', url_title($row['artist_name'])), '<div class="cover artist_img img' . $size . '" style="background-image:url(' . getArtistImg(array('artist_id' => $row['artist_id'], 'size' => $size)) . ')"></div>', array('title' => 'Browse to artist\'s page'))?>
            </td>
            <?php
          }
        }
        ?>
        <td class="title">
          <div class="title">
            <?php
            if (!empty($row['type'])) {
              echo anchor(array($row['type'], url_title($row['name'])), $row['name'], array('title' => 'Browse to ' . $row['type'] . '\'s page'));
            }
            else {
              if (isset($row['album_name']) && empty($hide['album'])) {
                if (empty($hide['spotify']) && $row['spotify_uri']) {
                  echo '<a href="' . $row['spotify_uri'] . '" class="spotify_link"><span class="spotify_container album_spotify_container"></span></a>';
                }
                echo anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), substrwords($row['album_name'], $strlenght), array('title' => 'Browse to album\'s page'));
                echo ' ';
                echo anchor(array('year', url_title($row['year'])), '<span class="album_year number">' . $row['year'] . '</span>', array('title' => 'Browse release year'));
              }
              if (empty($hide['artist'])) {
                echo '<div class="metainfo">' . anchor(array('music', url_title($row['artist_name'])), substrwords($row['artist_name'], $strlenght), array('title' => 'Browse to artist\'s page')) . '</div>';
              }
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
            <div class="count"><span class="number"><?=number_format($row['count'])?></span> <?=isset($term) ? $term : 'listenings'?></div>
            <?php
          }
          ?>
        </td>
      </tr>
      <?php
      $rank++;
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
  echo ERR_NO_RESULTS;
}
?>