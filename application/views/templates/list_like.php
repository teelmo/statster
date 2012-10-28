<?php
$json_data = json_decode($json_data);
if (is_array($json_data)) {
  foreach ($json_data as $idx => $row) {
    ?>
    <li><img src="<?=site_url()?>/media/img/icons/<?=$row->type?>.png" alt="" /></li>
    <?php
  }
}
else {
  echo $json_data->error->msg;
}
?>