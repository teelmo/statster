<?php
$json_data = json_decode($json_data);
if (is_array($json_data)) {
  foreach ($json_data as $idx => $row) {
    ?>
    <tr id="<?=$idx?>">
      <td>
        <?=$row->artist?>
      </td>
      <td class="chartBar">
        <?
        $width = ceil(($count / $highest_count) * 100);
        $min_width = ($count < 10) ? 'min-width: 20px' : 'min-width: 28px';
        if ($count > 100) {
          $min_width = 'min-width: 36px;';
        }
        ?>
        <div style="width: <?=$width?>%; <?=$min_width?>;" class="chartBar">
          <span class="chartBar"><?=$count?></span>
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