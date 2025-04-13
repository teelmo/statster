<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    switch ($type) {
      case 'album':
        foreach ($json_data as $idx => $row) {
          ?>
          <li>
            <?=anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<div class="cover album_img img150 no_overlay" style="background-image:url(' . getAlbumImg(array('album_id' => $row['album_id'], 'size' => 174)) . ')"></div>', array('title' => 'Browse to albums\'s page'))?>
          </li>
          <?php
        }
        break;
      case 'artist':
        foreach ($json_data as $idx => $row) {
          ?>
          <li>
            <?=anchor(array('music', url_title($row['artist_name'])), '<div class="cover artist_img img150 no_overlay" style="background-image:url(' . getArtistImg(array('artist_id' => $row['artist_id'], 'size' => 174)) . ')"></div>', array('title' => 'Browse to artist\'s page'))?>
          </li>
          <?php
        }
        break;
      case 'recent':
        $prev_date = '';
        foreach ($json_data as $idx => $row) {
          if (strip_tags(timeAgo($row['date'], CUR_DATE)) != $prev_date) {
            ?>
            <li class="block"><h2><?=timeAgo($row['date'], CUR_DATE)?> <?=(str_contains(timeAgo($row['date']), 'day') ? '<span class="datetime number">' . $row['date'] . '</span>' : '')?></h2></li>
            <?php
          }
          ?>
          <li>
            <?php
            $listeningsFormatImg = getListeningImg(array('listening_id' => $row['listening_id']));
            ?>
            <?=anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<div class="cover album_img img150 no_overlay" style="background-image:url(' . getAlbumImg(array('album_id' => $row['album_id'], 'size' => 174)) . ')"><div class="meta"><div class="title main">' . anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), substrwords($row['album_name'], 35), array('title' => 'Browse to album\'s page')) . '</div><div class="title">' . implode('<span class="artist_separator">, </span>', array_map(function($artist) { return anchor(array('music', url_title($artist['artist_name'])), $artist['artist_name'], array('title' => 'Browse to artist\'s page'));}, getAlbumArtists($row))) . '</div></div><img src="' . $listeningsFormatImg['filename'] . '" alt="" title="' . $listeningsFormatImg['name'] . '" class="middle icon listeningFormatType"/></div>', array('title' => 'Browse to albums\'s page'))?>
          </li>
          <?php
          $prev_date = strip_tags(timeAgo($row['date'], CUR_DATE));
        }
        break;
      case 'user':
        foreach ($json_data as $idx => $row) {
          ?>
          <li>
            <?=anchor(array('user', url_title($row['username'])), '<div class="cover user_img img150" style="background-image:url(' . getUserImg(array('user_id' => $row['user_id'], 'size' => 174)) . ')"></div>', array('title' => 'Browse to user\'s page'))?>
          </li>
          <?php
        }
        break;
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