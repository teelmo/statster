<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    ?>
    <thead>
      <tr>
        <th><?=$type?></th>
        <th>Count</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($type == 'Month') {
        $prev_bar_date = 1;
      }
      else {
        $prev_bar_date = $json_data[0]['bar_date'];
      }
      foreach ($json_data as $idx => $row) {
        while (intval($row['bar_date']) != $prev_bar_date) {
          ?>
          <tr>
            <td class="time">
              <?=($type == 'Month') ? DateTime::createFromFormat('!m', $prev_bar_date)->format('M') : $prev_bar_date ?>
            </td>
            <td class="count">
              0
            </td>
          </tr>
          <?php
          $prev_bar_date++;
        }
        ?>
        <tr>
          <td class="time">
            <?=($type == 'Month') ? DateTime::createFromFormat('!m', $row['bar_date'])->format('M') : $row['bar_date'] ?>
          </td>
          <td class="count">
            <?=$row['count']?>
          </td>
        </tr>
        <?
        $last_bar_date = $prev_bar_date;
        $prev_bar_date++;
      }
      if ($type == 'Month') {
        while ($prev_bar_date <= 12) {
          ?>
          <tr>
            <td class="time">
              <?=($type == 'Month') ? DateTime::createFromFormat('!m', $prev_bar_date)->format('M') : $prev_bar_date ?>
            </td>
            <td class="count">
              0
            </td>
          </tr>
          <?php
          $prev_bar_date++;
        }
      }
      else {
        while ($prev_bar_date <= CUR_YEAR) {
          ?>
          <tr>
            <td class="time">
              <?=($type == 'Month') ? DateTime::createFromFormat('!m', $prev_bar_date)->format('M') : $prev_bar_date ?>
            </td>
            <td class="count">
              0
            </td>
          </tr>
          <?php
          $prev_bar_date++;
        }
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