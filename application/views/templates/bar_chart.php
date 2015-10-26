<?php
$script = 'view.categories=[];view.chart_data=[];';
if (!empty($json_data)) {
  if (is_array($json_data)) {    
    $weekdays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    ?>
    <thead>
      <tr>
        <th><?=ucfirst($type)?></th>
        <th>Listenings</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($type == 'year') {
        $prev_bar_date = $json_data[0]['bar_date'];
      }
      else if ($type == 'weekday') {
        $prev_bar_date = 0;
      }
      else {
        $prev_bar_date = 1;
      }
      foreach ($json_data as $idx => $row) {
        while (intval($row['bar_date']) != $prev_bar_date) {
          if ($type == 'month') {
            $time = DateTime::createFromFormat('!m', $prev_bar_date)->format('M');
          }
          else if ($type == 'weekday') {
            $time = $weekdays[$prev_bar_date];
          }
          else {
            $time = $prev_bar_date;
          }
          $script .= 'view.categories.push(\'' . $time . '\');view.chart_data.push(0);';
          ?>
          <tr>
            <th class="time"><?=$time?></th>
            <td class="count">0</td>
          </tr>
          <?php
          $prev_bar_date++;
        }
        if ($type == 'month') {
          $time = DateTime::createFromFormat('!m', $prev_bar_date)->format('M');
        }
        else if ($type == 'weekday') {
          $time = $weekdays[$prev_bar_date];
        }
        else {
          $time = $prev_bar_date;
        }
        $script .= 'view.categories.push(\'' . $time . '\');view.chart_data.push(' . $row['count'] . ');';
        ?>
        <tr>
          <th class="time"><?=$time?></th>
          <td class="count"><?=$row['count']?></td>
        </tr>
        <?
        $last_bar_date = $prev_bar_date;
        $prev_bar_date++;
      }
      if ($type == 'month') {
        $limit = 12;
      }
      else if ($type == 'day') {
        $limit = 31;
      }
      else if ($type == 'weekday') {
        $limit = 6;
      }
      else {
        $limit = CUR_YEAR;
      }
      while ($prev_bar_date <= $limit) {
        if ($type == 'month') {
          $time = DateTime::createFromFormat('!m', $prev_bar_date)->format('M');
        }
        else if ($type == 'weekday') {
          $time = $weekdays[$prev_bar_date];
        }
        else {
          $time = $prev_bar_date;
        }
        $script .= 'view.categories.push(\'' . $time . '\');view.chart_data.push(0);';
        ?>
        
        <tr>
          <th class="time"><?=$time?></th>
          <td class="count">0</td>
        </tr>
        <?php
        $prev_bar_date++;
      }
      ?>
    </tbody>
    <?php
    echo '<script type="text/javascript" style="display:none;"> ' . $script . '</script>';
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