<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      ?>
      <div class="float_left">
        <?=anchor(array('user', url_title($row['username'])), '<div class="cover uesr_img img64" style="background-image:url(' . getUserImg(array('user_id' => $row['user_id'], 'size' => 64)) . ')"></div>', array('title' => 'Browse to user\'s page'))?>
      </div>
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
  No results
  <?php
}
?>