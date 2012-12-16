<?php
if (!empty($json_data)) {
  $json_data = json_decode($json_data);
  if (is_array($json_data)) {
    $rank = !empty($rank) ? $rank : 1;
    $prev_count = FALSE;
    foreach ($json_data as $idx => $row) {
      if (empty($highest_count)) {
        $highest_count = $row->count;
      }
      ?>
      <tr id="barTable<?=$idx?>">
        <?php
        if (empty($hide['rank'])) {
          ?>
          <td class="barChartRank">
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
        ?>
        <td class="barChartName">
          <?php
          if (!empty($row->album_name)) {
            echo anchor(array('music', url_title($row->artist_name), url_title($row->album_name)), $row->album_name, array('title' => 'Browse to artist\'s page'));
          }
          else {
            echo anchor(array('music', url_title($row->artist_name)), $row->artist_name, array('title' => 'Browse to artist\'s page'));
          }
          ?>
        </td>
        <td class="barChartBar">
          <?
          $width = ceil(($row->count / $highest_count) * 100);
          $min_width = ($row->count < 10) ? 'min-width: 22px' : 'min-width: 30px';
          if ($row->count > 100) {
            $min_width = 'min-width: 38px;';
          }
          ?>
          <div style="width: <?=$width?>%; <?=$min_width?>;" class="chartBar">
            <div><?=$row->count?></div>
          </div>
        </td>
      </tr>
      <?php
      if ($row->count != $prev_count) {
        $rank++;
      }
      $prev_count = $row->count;
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