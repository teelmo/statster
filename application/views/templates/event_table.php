<?php
if (!empty($json_data)) {
  $json_data = json_decode($json_data);
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      ?>
      <tr id="eventTable<?=$idx?>">
        <td class="barChartCalendar">
          <?php
          $timestamp = strtotime($row->date);
          ?>
          <span class="month"><?=date('M', $timestamp)?></span>
          <span class="day"><?=date('j', $timestamp)?></span>
        </td>
        <td class="title">
          <div class="title">
            <?=anchor($row->url, $row->name, array('target' => '_blank'))?>
            <div class="location">
              <?=$row->city?>, <?=$row->country?>
            </div>
          </div>
        </td>
      </tr>
      <?php
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
  <div class="container">
    Internal error
  </div>
  <?php
}
?>