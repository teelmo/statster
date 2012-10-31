<?php
$json_data = json_decode($json_data);
if (is_array($json_data)) {
  foreach ($json_data as $idx => $row) {
    ?>
    <tr id="genreTable<?=$idx?>">
      <td><span class="tag <?=$row->type?>"><?=anchor(array('tag', $row->type, url_title($row->name)), $row->name, array('title' => 'Browse ' . $row->type))?></a></td>
    </tr>
    <?php
  }
}
else {
  echo $json_data->error->msg;
}
?>