<?php
if (!empty($json_data)) {
  $size = isset($size) ? $size : 32;
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      ?>
      <tr data-created="<?=$row['created']?>">
        <td class="img<?=$size?>">
          <?php
          if (isset($row['album_name'])) {
            echo anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<div class="cover album_img img' . $size . '" style="background-image:url(' . getAlbumImg(array('album_id' => $row['album_id'], 'size' => $size)) . ')"></div>', array('title' => 'Browse to album\'s page'));
          }
          else {
            echo anchor(array('music', url_title($row['artist_name'])), '<div class="cover artist_img img' . $size . '" style="background-image:url(' . getArtistImg(array('artist_id' => $row['artist_id'], 'size' => $size)) . ')"></div>', array('title' => 'Browse to artist\'s page'));
          }
          ?>
        </td>
        <td class="title">
          <div class="title">
            <?php
            if (empty($hide['title'])) {
              if (isset($row['album_name'])) {
                echo anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), $row['album_name'], array('title' => 'Browse to album\'s page'));
              }
              else {
                echo anchor(array('music', url_title($row['artist_name'])), '<span class="title">' . $row['artist_name'] . '</span>', array('title' => 'Browse to artist\'s page'));
              }
            }
            ?>
          </div>
          <?php
          if (empty($hide['date'])) {
            ?>
            <div class="datetime">
              <?=timeAgo($row['created'])?> 
              <?php
              if (empty($hide['user'])) {
                ?>
                by <?=anchor(array('user', url_title($row['username'])), $row['username'], array('title' => 'Browse to user\'s page'));
              }
              ?>
            </div>
            <?php
          }
          ?>
        </td>
        <td class="type">
          <i class="fa fa-<?=$row['type']?>"></i>
        </td>
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