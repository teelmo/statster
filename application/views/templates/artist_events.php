<?php
if (!empty($json_data)) {
  if (empty($json_data['error'])) {
    foreach ($json_data as $idx => $row) {
      ?>
      <tr id="eventTable<?=$idx?>">
        <td class="barChartCalendar">
          <?php
          $timestamp = strtotime($row['date']);
          ?>
          <span class="month"><?=date('M', $timestamp)?></span>
          <span class="day"><?=date('j', $timestamp)?></span>
        </td>
        <td class="title">
          <div class="title">
            <?=anchor($row['url'], $row['name'], array('target' => '_blank'))?>
            <div class="location">
              <?=$row['city']?>, <?=$row['country']?>
            </div>
          </div>
        </td>
      </tr>
      <?php
    }
  }
  else {
    echo $json_data['error']['msg'];
  }
}
else {
  ?>
  No results
  <?php
}
?>