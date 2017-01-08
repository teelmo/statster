<?php
$script = 'view.categories=[];view.chart_data=[];';
if (!empty($json_data)) {
  if (is_array($json_data)) {    
    $weekdays = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
    ?>
    <thead>
      <tr>
        <th><?=ucfirst($type)?></th>
        <th>Listenings</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($type == '%Y') {
        $prev_bar_date = $json_data[0]['bar_date'];
      }
      else if ($type == '%Y%m') {
        $prev_bar_date = $json_data[0]['bar_date'];
      }
      else if ($type == '%w') {
        $prev_bar_date = 0;
      }
      else {
        $prev_bar_date = 1;
      }
      foreach ($json_data as $idx => $row) {
        if ($type == '%Y%m') {
          $cur_bar_year = substr($prev_bar_date, 0, 4);
          $cur_bar_month = substr($prev_bar_date, 4, 2);
          while ($row['bar_date'] != ($cur_bar_year . $cur_bar_month)) {
            $time = DateTime::createFromFormat('!m', intval($cur_bar_month))->format('M') . ' ’' . substr($cur_bar_year, 2, 2);
            $script .= 'view.categories.push(\'' . $time . '\');view.chart_data.push(0);';
            ?>
            <tr>
              <th class="time"><?=$time?></th>
              <td class="count">0</td>
            </tr>
            <?php
            $cur_bar_month = intval($cur_bar_month);
            if ($cur_bar_month >= 12) {
              $cur_bar_month = '01';
              $cur_bar_year++;
            } 
            else if ($cur_bar_month < 9) {
              $cur_bar_month = '0' . ($cur_bar_month + 1);
            }
            else {
              $cur_bar_month = $cur_bar_month + 1;
            }
          }
        }
        else {
          while (intval($row['bar_date']) != $prev_bar_date) {
            if ($type == '%m') {
              $time = DateTime::createFromFormat('!m', $prev_bar_date)->format('M');
            }
            else if ($type == '%w') {
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
        }
        if ($type == '%m') {
          $time = DateTime::createFromFormat('!m', $prev_bar_date)->format('M');
        }
        else if ($type == '%w') {
          $time = $weekdays[$prev_bar_date];
        }
        else if ($type == '%Y%m') {
          $time = DateTime::createFromFormat('!m', intval(substr($row['bar_date'], 4, 2)))->format('M') . ' ’' . substr($row['bar_date'], 2, 2);
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
        if ($type == '%Y%m') {
          $prev_bar_year = substr($row['bar_date'], 0, 4);
          $prev_bar_month = substr($row['bar_date'], 4, 2);
          $prev_bar_month = intval($prev_bar_month);
          if ($prev_bar_month >= 12) {
            $prev_bar_month = '01';
            $prev_bar_year++;
          } 
          else if ($prev_bar_month < 9) {
            $prev_bar_month = '0' . ($prev_bar_month + 1);
          }
          else {
            $prev_bar_month = $prev_bar_month + 1;
          }
          $prev_bar_date = $prev_bar_year . $prev_bar_month;
        }
        else {
          $prev_bar_date++;
        }
      }

      if ($type == '%m') {
        $limit = 12;
      }
      else if ($type == '%d') {
        $limit = 31;
      }
      else if ($type == '%w') {
        $limit = 6;
      }
      else if ($type == '%Y') {
        $limit = CUR_YEAR;
      }
      else if ($upper_limit) {
        $limit = $upper_limit;
      }
      else {
        $limit = CUR_YEAR . CUR_MONTH;
      }
      if ($type == '%Y%m') {
        $cur_bar_year = substr($prev_bar_date, 0, 4);
        $cur_bar_month = substr($prev_bar_date, 4, 2);
        while (($cur_bar_year . $cur_bar_month) <= $§) {
          $time = DateTime::createFromFormat('!m', intval($cur_bar_month))->format('M') . ' ’' . substr($cur_bar_year, 2, 2);
          $script .= 'view.categories.push(\'' . $time . '\');view.chart_data.push(0);';
          ?>
          <tr>
            <th class="time"><?=$time?></th>
            <td class="count">0</td>
          </tr>
          <?php
          $cur_bar_month = intval($cur_bar_month);
          if ($cur_bar_month >= 12) {
            $cur_bar_month = '01';
            $cur_bar_year++;
          } 
          else if ($cur_bar_month < 9) {
            $cur_bar_month = '0' . ($cur_bar_month + 1);
          }
          else {
            $cur_bar_month = $cur_bar_month + 1;
          }
        }
      }
      else {
        while ($prev_bar_date <= $limit) {
          if ($type == '%m') {
            $time = DateTime::createFromFormat('!m', $prev_bar_date)->format('M');
          }
          else if ($type == '%w') {
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