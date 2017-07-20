<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      if ($row['type'] === 'nationality') {
        ?>
        <li class="tag <?=$row['type']?>">
          <?=anchor(array($row['type'], url_title($row['name'])), '<img src="/media/img/flag_img/' . strtolower($row['country_code']) . '.png"/ alt="' . $row['name'] . '" />')?>
          <i class="hide fa fa-times"></i>
        </li>
        <?php
      }
      else if ($row['type'] === 'genre') {
        ?>
        <li class="tag <?=$row['type']?>">
          <?=anchor(array($row['type'], url_title($row['name'])), '<i class="fa fa-music"></i> ' . $row['name'])?>
          <i class="hide fa fa-times"></i>
        </li>
        <?php
      }
      else if ($row['type'] === 'keyword') {
        ?>
        <li class="tag <?=$row['type']?>">
          <?=anchor(array($row['type'], url_title($row['name'])), '<i class="fa fa-tag"></i> ' . $row['name'])?>
          <i class="hide fa fa-times"></i>
        </li>
        <?php
      }
    }
    if ($logged_in === 'true' && empty($hide['add'])) {
      ?>
      <li class="tag addtags" id="addtags"><a href="javascript:;"><i class="fa fa-bars"></i></a></li>
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