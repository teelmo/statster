<?php
$json_data = json_decode($json_data);
$size = isset($size) ? $size : 64;
if (is_array($json_data)) {
  if (!empty($limit)) {
    shuffle($json_data);
    $json_data = array_slice($json_data, 0, 2);
  }
  $rank = 1;
  $prev_count = FALSE;
  foreach ($json_data as $idx => $row) {
    ?>
    <tr id="albumTable<?=$idx?>">
      <?php
      if (empty($hide['rank'])) {
        ?>
        <td class="rank">
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
      if (empty($hide['calendar'])) {
        ?>
        <td class="barChartCalendar">
          <?php
          $timestamp = strtotime($row->date);
          ?>
          <span class="month"><?=date('M', $timestamp)?></span>
          <span class="day"><?=date('j', $timestamp)?></span>
        </td>
        <?php
      }
      ?>
      <td class="img<?=$size?> userImg">
      <?=anchor(array('user', 'profile', url_title($row->username)), '<img src="' . getUserImg(array('user_id' => $row->user_id, 'size' => $size)) . '" alt="" class="userImg userImg' . $size . '" />', array('title' => 'Browse to user\'s page'));
      ?>
      </td>
      <td class="title">
        <div class="title">
          <?= anchor(array('user', 'profile', url_title($row->username)), $row->username, array('title' => 'Browse to user profile'))?>
        </div>
        <?php
        if (empty($hide['date'])) {
          ?>
          <div class="datetime">
            <?=timeAgo($row->date)?>
          </div>
          <?php
        }
        if (empty($hide['count'])) {
          ?>
          <div class="count">
            <?=$row->count?> listenings
          </div>
          <?php
        }
        ?>
      </td>
    </tr>
    <?php
    if ($row->count != $prev_count) {
      $rank++;
    }
    $prev_count = $row->count;
  }
}
else {
  echo $json_data->error->msg;
}
?>