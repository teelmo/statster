<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      ?>
      <li>
        <?=anchor(array('music', url_title($row['artist_name'])), '<div class="cover artist_img img124" style="background-image:url(' . getArtistImg(array('artist_id' => $row['artist_id'], 'size' => 124)) . ')">' . anchor(array('music', url_title($row['artist_name'])), '<span class="title">' . $row['artist_name'] . '</span>', array('title' => 'Browse to artist\'s page')) . '</div>', array('title' => 'Browse to artist\'s page'))?>
        <?php
        if (empty($hide['count'])) {
          ?>
          <span class="play_count number"><?=$row['count']?></span> plays
          <?php
        }
        ?>
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