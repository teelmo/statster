<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    $rank = !empty($rank) ? $rank : 1;
    $prev_count = FALSE;
    foreach ($json_data as $idx => $row) {
      if (empty($highest_count)) {
        $highest_count = $row['count'];
      }
      ?>
      <tr id="column_table_<?=$idx?>">
        <?php
        if (empty($hide['rank'])) {
          ?>
          <td class="rank">
            <?php
            if ($row['count'] !== $prev_count) {
              ?>
              <span class="rank number"><?=$rank?></span>
              <?php
            }
            ?>
          </td>
          <?php
        }
        ?>
        <td class="name">
          <?php
          if (isset($type) && $type === 'user') {
            echo anchor(array('user', url_title($row['username'])), $row['username'], array('title' => 'Browse to user\'s page'));
          }
          else if (!empty($row['type'])) {
            if ($row['type'] === 'star') {
              echo anchor(array('music', url_title($row['artist_name'])), $row['artist_name'], array('title' => 'Browse to artist\'s page'));
            }
            else if ($row['type'] === 'heart') {
              echo anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), $row['album_name'], array('title' => 'Browse to album\'s page'));
            }
            else if ($row['type'] === 'format') {
              $listeningsFormatImg = getFormatImg(array('format' => $row['format_name']));
              $listeningsFormatTypeImg = getFormatTypeImg(array('format_type' => $row['format_type_name']));
              echo (empty($row['format_type_name'])) ? anchor(array('format', url_title(str_replace(' ', '-', $row['format_name']))), '<img src="/media/img/format_img/format_icons/' . $listeningsFormatImg . '.png" alt="" title="' . $row['format_type_name'] . '" class="middle icon listeningFormatType"/>' . $row['format_name'], array('title' => 'Browse to format\'s page')) : anchor(array('format', url_title(str_replace(' ', '-', $row['format_name'])), url_title($row['format_type_name'])), '<img src="/media/img/format_img/format_icons/' . $listeningsFormatTypeImg . '.png" alt="" title="' . $row['format_type_name'] . '" class="middle icon listeningFormatType"/>' . $row['format_type_name'], array('title' => 'Browse to format type\'s page'));
            }
            else {
              echo anchor(array($row['type'], url_title($row['name'])), $row['name'], array('title' => 'Browse to ' . $row['type'] . '\'s page'));
            }
          }
          else {
            if (isset($row['album_name']) && empty($hide['album'])) {
              echo anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), $row['album_name'], array('title' => 'Browse to album\'s page'));
            }
            else {
              echo anchor(array('music', url_title($row['artist_name'])), $row['artist_name'], array('title' => 'Browse to artist\'s page'));
            }
          }
          ?>
        </td>
        <td class="bar">
          <?php
          $width = ceil(($row['count'] / $highest_count) * 100);
          if ($row['count'] >= 1000) {
            $min_width = 'min-width: 44px';
          }
          else if ($row['count'] >= 100) {
            $min_width = 'min-width: 33px';
          }
          else if ($row['count'] >= 10) {
            $min_width = 'min-width: 24px';
          }
          else {
            $min_width = 'min-width: 18px';
          }
          ?>
          <div style="width: <?=$width?>%; <?=$min_width?>;">
            <div class="number"><?=number_format($row['count'])?></div>
          </div>
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