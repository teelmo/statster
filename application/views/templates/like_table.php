<?php
if (!empty($json_data)) {
  $size = isset($size) ? $size : 32;
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      ?>
      <tr id="likeTable<?=$idx?>" data-created="<?=$row['created']?>">
        <td class="type">
          <img src="<?=site_url()?>/media/img/icons/<?=$row['type']?>.png" alt="" />
        </td>
        <td class="img<?=$size?> albumImg">
          <?php
          if (empty($row['album_name'])) {
            echo anchor(array('music', url_title($row['artist_name'])), '<img src="' . getArtistImg(array('artist_id' => $row['artist_id'], 'size' => $size)) . '" alt="" class="artistImg img' . $size . '" />', array('title' => 'Browse to artist\'s page'));
          }
          else {
            echo anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<img src="' . getAlbumImg(array('album_id' => $row['album_id'], 'size' => $size)) . '" alt="" class="albumImg img' . $size . '" />', array('title' => 'Browse to album\'s page'));
          }
          ?>
        </td>
        <td class="title">
          <div class="title">
            <?php
            if (empty($hide['title'])) {
              if (empty($row['album_name'])) {
                echo anchor(array('music', url_title($row['artist_name'])), '<span class="title">' . $row['artist_name'] . '</span>', array('title' => 'Browse to artist\'s page'));
              }
              else {
                echo anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), $row['album_name'], array('title' => 'Browse to album\'s page'));
              }
            }
            ?>
          </div>
          <?php
          if (empty($hide['date'])) {
            ?>
            <div class="datetime">
              <?=timeAgo($row['created'])?> by <?=anchor(array('user', url_title($row['username'])), $row['username'], array('title' => 'Browse to user\'s page'))?>
            </div>
            <?php
          }
          ?>
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
  ?>
  No results
  <?php
}
?>