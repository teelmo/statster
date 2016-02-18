<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      ?>
      <li class="tag <?=$row['type']?>">
        <?=anchor(array($row['type'], url_title($row['country'])), '<img src="/media/img/flag_img/' . strtolower($row['country_code']) . '.png"/ alt="' . $row['country'] . '" />')?>
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
  ?>
  No results
  <?php
}
?>