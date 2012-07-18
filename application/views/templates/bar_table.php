<?php
$json_data = json_decode($json_data);
if (is_array($json_data)) {
  foreach ($json_data as $idx => $row) {
    if(empty($highest_count)) {
      $highest_count = $row->count;
    }
    ?>
    <tr id="barTable<?=$idx?>">
      <td class="barChartRank">
        <?=$idx + 1?>.
      </td>
      <td class="barChartName">
        <?=anchor(array('music', url_title($row->artist_name)), $row->artist_name, array('title' => 'Browse to artist\'s page'))?>
      </td>
      <td class="barChartBar">
        <?
        $width = ceil(($row->count / $highest_count) * 100);
        $min_width = ($row->count < 10) ? 'min-width: 20px' : 'min-width: 28px';
        if ($row->count > 100) {
          $min_width = 'min-width: 36px;';
        }
        ?>
        <div style="width: <?=$width?>%; <?=$min_width?>;" class="chartBar">
          <div><?=$row->count?></div>
        </div>
      </td>
    </tr>
    <?php
  }
}
else {
  echo $json_data->error->msg;
}
?>