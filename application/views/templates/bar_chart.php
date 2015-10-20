<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    ?>
    <thead>
      <tr>
        <th>Year</th>
        <th>Count</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($json_data as $idx => $row) {
        ?>
        <tr id="bar_chart_<?=$idx?>">
          <td class="year">
            <?=$row['date']?>
          </td>
          <td class="count">
            <?=$row['count']?>
          </td>
        <?
      }
      ?>
    </tbody>
    <?php
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