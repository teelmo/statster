<?php
if (!empty($json_data)) {
  $size = isset($size) ? $size : 64;
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      ?>
      <tr id="shout<?=ucfirst($row['type'])?>Table<?=$idx?>" data-created="<?=$row['created']?>" class="shout">
        <?php
        if (isset($type)) {
          ?>
          <td class="img <?=$type?>_img">
            <?=anchor(array('user', url_title($row['username'])), '<div class="cover ' . $type . '_img img' . $size . '" style="background-image:url(' . call_user_func('get' . ucfirst($type) . 'Img', array($type . '_id' => $row[$type . '_id'], 'size' => $size)) . ')"></div>', array('title' => 'Browse to user\'s page'))?>
          </td>
          <?php
        }
        else if ($row['type'] === 'user') {
          ?>
          <td class="img user_img">
            <?=anchor(array('user', url_title($row['profile'])), '<div class="cover user_img img' . $size . '" style="background-image:url(' . getUserImg(array('user_id' => $row['profile_id'], 'size' => $size)) . ')"></div>', array('title' => 'Browse to user\'s page'))?>
          </td>
          <?php
        }
        else if ($row['type'] === 'artist') {
          ?>
          <td class="img artist_img">
            <?=anchor(array('music', url_title($row['artist_name'])), '<div class="cover artist_img img' . $size . '" style="background-image:url(' . getArtistImg(array('artist_id' => $row['artist_id'], 'size' => $size)) . ')"></div>', array('title' => 'Browse to artist\'s page'))?>
          </td>
          <?php
        }
        else if ($row['type'] === 'album') {
          ?>
          <td class="img album_img">
            <?=anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<div class="cover album_img img' . $size . '" style="background-image:url(' . getAlbumImg(array('album_id' => $row['album_id'], 'size' => $size)) . ')"></div>', array('title' => 'Browse to artist\'s page'))?>
          </td>
          <?php
        }
        ?>
        <td class="text">
          <div>
            <?php
            if (isset($type)) {
              ?>
              <span class="username title"><?=anchor(array('user', url_title($row['username'])), $row['username'])?></span>
              <?php
            }
            else if ($row['type'] === 'user') {
              ?>
              <span class="username title"><?=anchor(array('user', url_title($row['profile'])), $row['profile'])?></span>
              <?php
            }
            else if ($row['type'] === 'artist') {
              ?>
              <span class="artist_name title"><?=anchor(array('music', url_title($row['artist_name'])), $row['artist_name'])?></span>
              <?php
            }
            else if ($row['type'] === 'album') {
              ?>
              <span class="album_name title"><?=anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), $row['album_name'])?></span>
              <?php
            }
            if ($this->session->userdata('user_id') === $row['user_id'] && empty($hide['delete'])) {
              ?>
              <span class="delete" data-confirmation-container=".confirmation_<?=$row['type']?>_<?=$idx?>"><a href="javascript:;"><i class="fa fa-times"></i></a></span>
              <div class="confirmation confirmation_<?=$row['type']?>_<?=$idx?>">Are you sure: <a href="javascript:;" class="confirm" data-shout-id="<?=$row['shout_id']?>" data-shout-type="<?=$row['type']?>" data-row-id="shout<?=ucfirst($row['type'])?>Table<?=$idx?>">Ok</a> / <a href="javascript:;" class="cancel">Cancel</a></div>
              <?php
            }
            ?>
            <div class="metainfo">
              <div><?=timeAgo($row['created'])?></div>
              <?php
                if (empty($hide['user'])) {
                  ?>
                  <div>by <?=anchor(array('user', url_title($row['username'])), $row['username'], array('title' => 'Browse to user\'s page'))?></div>
                <?php
              }
              ?>
            </div>
          </div>
          <div class="shout_text">
            <?=nl2br($row['text'])?>
          </div>
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