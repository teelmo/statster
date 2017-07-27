<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      ?>
      <li>
        <?=anchor(array('user', url_title($row['username'])), '<div class="cover user_img img150" style="background-image:url(' . getUserImg(array('user_id' => $row['user_id'], 'size' => 174)) . ')"></div>', array('title' => 'Browse to user\'s page'))?>
      </li>
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
  echo ERR_NO_RESULTS;
}
?>