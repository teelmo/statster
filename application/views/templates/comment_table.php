<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    foreach ($json_data as $idx => $row) {
      ?>
      <tr id="comment<?=ucfirst($row['type'])?>Table<?=$idx?>" data-created="<?=$row['created']?>" class="comment">
        <?php
        if (empty($hide['img'])) {
          ?>
          <td class="img user_img">
            <?=anchor(array('user', url_title($row['username'])), '<div class="cover user_img img64" style="background-image:url(' . getUserImg(array('user_id' => $row['user_id'], 'size' => 64)) . ')"></div>', array('title' => 'Browse to user\'s page'))?>
          </td>
          <?php
        }
        else if ($row['type'] === 'artist') {
          ?>
          <td class="img artist_img">
            <?=anchor(array('music', url_title($row['artist_name'])), '<div class="cover artist_img img32" style="background-image:url(' . getArtistImg(array('artist_id' => $row['artist_id'], 'size' => 32)) . ')"></div>', array('title' => 'Browse to artist\'s page'))?>
          </td>
          <?php
        }
        else if ($row['type'] === 'album') {
          ?>
          <td class="img album_img">
            <?=anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<div class="cover album_img img32" style="background-image:url(' . getAlbumImg(array('album_id' => $row['album_id'], 'size' => 32)) . ')"></div>', array('title' => 'Browse to artist\'s page'))?>
          </td>
          <?php
        }
        ?>
        <td class="text">
          <div>
            <?php
            if ($row['type'] === 'user') {
              ?>
              <span class="username title"><?=anchor(array('user', url_title($row['username'])), $row['username'])?></span>
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
            if ($this->session->userdata('user_id') === $row['user_id']) {
              ?>
              <span class="delete" data-confirmation-container=".confirmation_<?=$row['type']?>_<?=$idx?>"><a href="javascript:;"><i class="fa fa-times"></i></a></span>
              <div class="confirmation confirmation_<?=$row['type']?>_<?=$idx?>">Are you sure: <a href="javascript:;" class="confirm" data-comment-id="<?=$row['comment_id']?>" data-comment-type="<?=$row['type']?>" data-row-id="comment<?=ucfirst($row['type'])?>Table<?=$idx?>">Ok</a> / <a href="javascript:;" class="cancel">Cancel</a></div>
              <?php
            }
            ?>
            <span class="datetime float_right"><?=timeAgo($row['created'])?></span>
          </div>
          <?=nl2br($row['text'])?>
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