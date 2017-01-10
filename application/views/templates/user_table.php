<?php
if (!empty($json_data)) {
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
      <tr id="userTable<?=$idx?>">
        <?php
        if (empty($hide['rank'])) {
          ?>
          <td class="rank">
            <?php
            if ($row['count'] != $prev_count) {
              ?>
              <span class="rank"><?=$rank?></span>
              <?php
            }
            ?>
          </td>
          <?php
        }
        if (empty($hide['calendar'])) {
          ?>
          <td class="calendar">
            <?php
            list($date['year'], $date['month'], $date['day']) = explode('-', $row['date']);
            if ($date['month'] == '00' || $date['day'] == '00') {
              $month = '–';
              $day = '–';
            }
            else {
              $timestamp = strtotime($row['date']);
              $month = date('M', $timestamp);
              $day = date('j', $timestamp);
            } 
            ?>
            <span class="month"><?=$month?></span>
            <span class="day"><?=$day?></span>
          </td>
          <?php
        }
        ?>
        <td class="img<?=$size?> userImg">
          <?=anchor(array('user', url_title($row['username'])), '<div class="cover user_img img' . $size . '" style="background-image:url(' . getUserImg(array('user_id' => $row['user_id'], 'size' => $size)) . ')"></div>', array('title' => 'Browse to user\'s page'))?>
        </td>
        <td class="title">
          <div class="title">
            <?=anchor(array('user', url_title($row['username'])), $row['username'], array('title' => 'Browse to user profile'))?>
          </div>
          <?php
          if (empty($hide['date'])) {
            ?>
            <div class="datetime">
              <?=timeAgo($row['date'])?>
            </div>
            <?php
          }
          if (empty($hide['count'])) {
            ?>
            <div class="count">
              <?=number_format($row['count'])?> listenings
            </div>
            <?php
          }
          ?>
        </td>
      </tr>
      <?php
      if ($row['count'] != $prev_count) {
        $rank++;
      }
      $prev_count = $row['count'];
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