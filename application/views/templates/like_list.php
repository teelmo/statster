<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      ?>
      <li data-created="<?=$row['created']?>">
        <i class="mask-icon mask-icon-<?=($row['type'] === 'star') ? 'star-solid' : 'heart-solid'?>"></i>
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