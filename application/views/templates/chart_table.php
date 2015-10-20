<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    ?>
    <thead>
      <tr>
        <td colspan="6" id="recentlyUpdated" value=""></td>
      </tr>
    </thead>
    <tbody>
      <?php
      $justAdded = FALSE;
      foreach ($json_data as $idx => $row) {
        $class = '';
        $size = 32;
        $datetime = '';
        if ($idx == 0) {
          if ((time() - strtotime($row['created'])) < JUST_LISTENED_INTERVAL && $row['date'] == CUR_DATE) {
            $class = 'justAdded';
            $size = 64;
            $datetime = '<span id="nowPlaying"></span> <span id="nowPlayingText">now playing</span>';
            $justAdded = TRUE;
          }
        }
        elseif ($justAdded === TRUE) {
          $class = 'justAddedRest';
        }
        ?>
        <tr id="chartTable<?=$idx?>" class="row <?=$class?>">
          <td class="img albumImg">
            <?=anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<img src="' . getAlbumImg(array('album_id' => $row['album_id'], 'size' => $size)) . '" alt="" class="albumImg img' . $size . '" />', array('title' => 'Browse to album\'s page'))?>
          </td>
          <td class="title">
            <span class="title">
              <span class="artist"><?=anchor(array('music', url_title($row['artist_name'])), $row['artist_name'], array('title' => 'Browse to artist\'s page'))?>
                <?=DASH?>
              </span>
              <span class="album"><?=anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), $row['album_name'], array('title' => 'Browse to album\'s page'))?></span>
              <span class="year">
                <?=anchor(array('tag', 'release+year', url_title($row['year'])), '<span class="album_year">' . $row['year'] . '</span>', array('title' => 'Browse albums'))?>
              </span>
            </span>
            <?php
            if (empty($hide['del'])) {
              if ($this->session->userdata('user_id') === $row['user_id']) {
                ?>
                <span class="delete deleteCont" id="delete_<?=$idx?>">
                  <a href="javascript:;"><img src="/media/img/icons/delete.png" class="icon" /></a>
                </span>
                <div class="confirmation" for="delete_<?=$idx?>"><span class="small">Are you sure: <a href="javascript:;" class="confirm" for="delete_<?=$idx?>" data-listening-id="<?=$row['listening_id']?>" data-row-id="chartTable<?=$idx?>">Ok</a> / <a href="javascript:;" class="cancel" for="delete_<?=$idx?>">Cancel</a></span></div>
                <?php
              }
            }
            ?>
          <td class="love icon">
            <?php
            $love_data = getLove(array(
              'user_id' => $row['user_id'], 
              'album_id' => $row['album_id'],
            ));
            if (!empty($love_data)) {
              ?>
              <span class="loveIcon" title="Loved"></span>
              <?php
            }
            ?>
          </td>
          <td class="format icon">
            <?php
            $listeningsFormatImg = getListeningsFormatImg(array('listening_id' => $row['listening_id']));
            ?>
            <img src="<?=$listeningsFormatImg['filename']?>" alt="" title="<?=$listeningsFormatImg['name']?>" class="middle icon listeningFormatType"/>
          </td>
          <td class="datetime text_right"><?php echo !empty($datetime) ? $datetime : timeAgo($row['date'])?></td>
          <?php
          if (empty($hide['user'])) {
            ?>
            <td class="img userImg">
              <?=anchor(array('user', url_title($row['username'])), '<img src="' . getUserImg(array('user_id' => $row['user_id'], 'size' => $size)) . '" alt="" class="userImg img' . $size . '" />', array('title' => 'Browse to user\'s page'))?>
            </td>
            <?php
          }
          ?>
        </tr>
        <?php
      }
      ?>
    </tbody>
    <?php
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