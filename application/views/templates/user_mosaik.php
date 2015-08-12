<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      ?>
      <div class="float_left">
        <?=anchor(array('user', url_title($row['username'])), '<img src="' . getUserImg(array('user_id' => $row['id'], 'size' => 64)) . '" alt="" class="userImg img64" />', array('title' => 'Browse to user\'s page'))?>
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