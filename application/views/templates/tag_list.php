<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      if ($row['type'] === 'nationality') {
        ?>
        <li class="tag <?=$row['type']?>">
          <?=anchor(array($row['type'], url_title($row['country'])), '<img src="/media/img/flag_img/' . strtolower($row['country_code']) . '.png"/ alt="' . $row['country'] . '" />')?>
        </li>
        <?php
      }
      else {
        ?>
        <li class="tag <?=$row['type']?>">
          <?=anchor(array($row['type'], url_title($row['name'])), $row['name'])?>
        </li>
        <?php
      }
    }
    if ($logged_in == '1' && empty($hide['add'])) {
      ?>
      <li class="tag moretags" id="moretags"><a href="javascript:;"><i class="fa fa-bars"></i></a></li>
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