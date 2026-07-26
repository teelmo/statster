<?php
if (!empty($json_data)) {
  if (is_array($json_data)) {
    switch ($type) {
      case 'album':
        prefetchImagePaths(array_map(function($row) { return array('type' => 'album', 'size' => 174, 'id' => $row['album_id']); }, $json_data));
        foreach ($json_data as $idx => $row) {
          ?>
          <li>
            <?=anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<div class="cover album_img img150" style="background-image:url(' . getAlbumImg(array('album_id' => $row['album_id'], 'size' => 174)) . ')"></div>', array('title' => 'Browse to albums\'s page'))?>
          </li>
          <?php
        }
        break;
      case 'artist':
        prefetchImagePaths(array_map(function($row) { return array('type' => 'artist', 'size' => 174, 'id' => $row['artist_id']); }, $json_data));
        foreach ($json_data as $idx => $row) {
          ?>
          <li>
            <?=anchor(array('music', url_title($row['artist_name'])), '<div class="cover artist_img img150" style="background-image:url(' . getArtistImg(array('artist_id' => $row['artist_id'], 'size' => 174)) . ')"></div>', array('title' => 'Browse to artist\'s page'))?>
          </li>
          <?php
        }
        break;
      case 'recent':
        $prev_date = '';
        $album_artists = getAlbumsArtists(array_column($json_data, 'album_id'));
        $listening_imgs = getListeningImgsForListenings(array_column($json_data, 'listening_id'));
        prefetchImagePaths(array_map(function($row) { return array('type' => 'album', 'size' => 174, 'id' => $row['album_id']); }, $json_data));
        foreach ($json_data as $idx => $row) {
          if (strip_tags(timeAgo($row['date'], CUR_DATE)) != $prev_date) {
            ?>
            <li class="block"><h3><?=timeAgo($row['date'], CUR_DATE)?> <?=(str_contains(timeAgo($row['date']), 'day') ? '<span class="datetime number">' . $row['date'] . '</span>' : '')?></h3></li>
            <?php
          }
          ?>
          <li class="album">
            <?php
            $listeningsFormatImg = isset($listening_imgs[$row['listening_id']]) ? $listening_imgs[$row['listening_id']] : array('filename' => site_url() . '/media/img/format_img/format_icons/empty.png', 'name' => '');
            ?>
            <?=anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), '<div class="cover album_img img150" style="background-image:url(' . getAlbumImg(array('album_id' => $row['album_id'], 'size' => 174)) . ')"></div><div class="meta"><div class="title main">' . anchor(array('music', url_title($row['artist_name']), url_title($row['album_name'])), substrwords($row['album_name'], 35), array('title' => 'Browse to album\'s page')) . '</div><div class="title">' . implode('<span class="artist_separator">, </span>', array_map(function($artist) { return anchor(array('music', url_title($artist['artist_name'])), $artist['artist_name'], array('title' => 'Browse to artist\'s page'));}, isset($album_artists[$row['album_id']]) ? $album_artists[$row['album_id']] : array())) . '</div></div><img src="' . $listeningsFormatImg['filename'] . '" alt="" title="' . $listeningsFormatImg['name'] . '" class="middle icon listeningFormatType"/></div>', array('title' => 'Browse to albums\'s page'))?>
          </li>
          <?php
          $prev_date = strip_tags(timeAgo($row['date'], CUR_DATE));
        }
        break;
      case 'user':
        prefetchImagePaths(array_map(function($row) { return array('type' => 'user', 'size' => 174, 'id' => $row['user_id']); }, $json_data));
        foreach ($json_data as $idx => $row) {
          ?>
          <li>
            <?=anchor(array('user', url_title($row['username'])), '<div class="cover user_img img150" style="background-image:url(' . getUserImg(array('user_id' => $row['user_id'], 'size' => 174)) . ')"><div class="meta"><div class="title main">' . anchor(array('user', url_title($row['username'])), substrwords($row['username'], 35)) . '</div></div></div>', array('title' => 'Browse to user\'s page'))?>
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