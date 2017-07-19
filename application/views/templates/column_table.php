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
            if ($row['count'] != $prev_count) {
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
              echo anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), $row['album_name'], array('title' => 'Browse to artist\'s page'));
            }
            else {
              echo anchor(array($row['type'], url_title($row['name'])), $row['name'], array('title' => 'Browse to ' . $row['type'] . '\'s page'));
            }
          }
          else {
            if (!empty($row['album_name']) && empty($hide['album'])) {
              echo anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), $row['album_name'], array('title' => 'Browse to artist\'s page'));
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
          $min_width = ($row['count'] < 10) ? 'min-width: 22px' : 'min-width: 30px';
          if ($row['count'] > 100) {
            $min_width = 'min-width: 38px;';
          }
          ?>
          <div style="width: <?=$width?>%; <?=$min_width?>;">
            <div class="number"><?=number_format($row['count'])?></div>
          </div>
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
  echo ERR_NO_RESULTS;
}
?>