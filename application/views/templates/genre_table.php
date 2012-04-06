<?php
$json_data = json_decode($json_data);
if (is_array($json_data)) {
  foreach ($json_data as $idx => $row) {
    ?>
    <tr id="<?=$idx?>">
      <td><?=anchor(array('tag', 'genre', url_title($row->name)), $row->name, array('title' => 'Browse genre'))?></td>
    </tr>
    <?php
  }
}
else {
  echo $json_data->error->msg;
}
?>