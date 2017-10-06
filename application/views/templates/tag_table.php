<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      ?>
      <tr>
        <td><span class="tag <?=$row['type']?>"><?=anchor(array($row['type'], url_title($row['name'])), $row['name'], array('title' => 'Browse ' . $row['type']))?></span></td>
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
  echo ERR_NO_RESULTS;
}
?>