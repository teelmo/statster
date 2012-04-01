<?php
if ($json_data = json_decode($json_data)) {
  foreach ($json_data as $idx => $row) {
    ?>
    <tr id="<?=$idx?>">
      <td><?=anchor(array('tag', 'genre', url_title($row->name)), $row->name, array('title' => 'Browse genre'))?></td>
    </tr>
    <?php
  }
}
else {
  ?>
  Not enough data.
  <?
}
?>
