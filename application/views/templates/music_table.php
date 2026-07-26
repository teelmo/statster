<?php
$strlenght = isset($strlenght) ? $strlenght : 80;
if (!empty($json_data)) {
  if (is_array($json_data)) {
    ?>
    <thead>
      <tr>
        <td colspan="6" id="recentlyUpdated" value="" class="updated"></td>
      </tr>
    </thead>
    <tbody>
      <?php
      $justAdded = FALSE;
      $album_artists = getAlbumsArtists(array_column($json_data, 'album_id'));
      $loved_pairs = getLovedPairs(array_column($json_data, 'user_id'), array_column($json_data, 'album_id'));
      $listening_imgs = getListeningImgsForListenings(array_column($json_data, 'listening_id'));
      $image_requests = array();
      foreach ($json_data as $idx => $row) {
        $img_size = ($idx == 0 && isset($time) && ($time - strtotime($row['created'] . ' UTC')) < JUST_LISTENED_INTERVAL && $row['date'] == gmdate('Y-m-d', $time)) ? 64 : 32;
        $image_requests[] = array('type' => 'album', 'size' => $img_size, 'id' => $row['album_id']);
        if (empty($hide['user'])) {
          $image_requests[] = array('type' => 'user', 'size' => $img_size, 'id' => $row['user_id']);
        }
      }
      prefetchImagePaths($image_requests);
      foreach ($json_data as $idx => $row) {
        $class = '';
        $size = 32;
        $datetime = '';
        if ($idx == 0 && isset($time)) {
          if (($time - strtotime($row['created'] . ' UTC')) < JUST_LISTENED_INTERVAL && $row['date'] == gmdate('Y-m-d', $time)) {
            $class = 'just_added';
            $size = 64;
            $datetime = '<span class="now_playing"></span> <span>now playing</span>';
            $justAdded = TRUE;
          }
        }
        elseif ($justAdded === TRUE) {
          $class = 'just_added_rest';
        }
        ?>
        <tr id="musicTable<?=$idx?>" class="row <?=$class?>">
          <td class="img album_img">
            <?=anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<div class="cover album_img img' . $size . '" style="background-image:url(' . getAlbumImg(array('album_id' => $row['album_id'], 'size' => $size)) . ')"></div>', array('title' => 'Browse to album\'s page'))?>
          </td>
          <td class="title">
            <?php
            if (empty($hide['spotify']) && $row['spotify_id']) {
              ?>
              <a href="spotify:album:<?=$row['spotify_id']?>" class="spotify_link"><span class="spotify_container album_spotify_container"></span></a>
              <?php
            }
            ?>
            <span class="title">
              <span class="artist"><?=implode('<span class="artist_separator">, </span>', array_map(function($artist) { return anchor(array('music', url_title($artist['artist_name'])), $artist['artist_name'], array('title' => 'Browse to artist\'s page'));}, isset($album_artists[$row['album_id']]) ? $album_artists[$row['album_id']] : array()))?>
                <?=DASH?>
              </span>
              <span class="album"><?=anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), substrwords($row['album_name'], $strlenght), array('title' => 'Browse to album\'s page'))?></span>
              <span class="year">
                <?=anchor(array('year', url_title($row['year'])), '<span class="album_year number">' . $row['year'] . '</span>', array('title' => 'Browse albums'))?>
              </span>
            </span>
            <?php
            if (empty($hide['del'])) {
              if ($this->session->userdata('user_id') === $row['user_id']) {
                ?>
                <span class="delete" data-confirmation-container=".confirmation_<?=$idx?>"><a href="javascript:;"><i class="mask-icon mask-icon-times"></i></a></span>
                <div class="confirmation confirmation_<?=$idx?>">Are you sure: <a href="javascript:;" class="confirm" data-listening-id="<?=$row['listening_id']?>" data-row-id="musicTable<?=$idx?>">Ok</a> / <a href="javascript:;" class="cancel">Cancel</a></div>
                <?php
              }
            }
            ?>
          </td>
          <td class="love icon">
            <?php
            if (!empty($loved_pairs[$row['user_id'] . ':' . $row['album_id']])) {
              ?>
              <span class="love_icon" title="Loved"></span>
              <?php
            }
            ?>
          </td>
          <td class="format icon">
            <?php
            $listeningsFormatImg = isset($listening_imgs[$row['listening_id']]) ? $listening_imgs[$row['listening_id']] : array('filename' => site_url() . '/media/img/format_img/format_icons/empty.png', 'name' => '');
            ?>
            <img src="<?=$listeningsFormatImg['filename']?>" alt="" title="<?=$listeningsFormatImg['name']?>" class="middle icon listeningFormatType"/>
          </td>
          <td class="datetime"><?php echo !empty($datetime) ? $datetime : timeAgo($row['date'], CUR_DATE)?></td>
          <?php
          if (empty($hide['user'])) {
            ?>
            <td class="img user_img">
              <?=anchor(array('user', url_title($row['username'])), '<div class="cover user_img img' . $size . '" style="background-image:url(' . getUserImg(array('user_id' => $row['user_id'], 'size' => $size)) . ')"></div>', array('title' => 'Browse to user\'s page'))?>
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
  echo ERR_NO_RESULTS;
}
?>