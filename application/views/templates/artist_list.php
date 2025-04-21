<?php
if (!empty($json_data)) {
  if (empty($json_data['error'])) {
    foreach ($json_data as $idx => $row) {
      ?>
      <li class="artist">
        <?php
        if (getArtistID($row)) {
          ?>
          <?=(isset($row['country'])) ? anchor(array('nationality', url_title($row['country'])), '<div>' . $row['country'] . ' <img src="/media/img/flag_img/' . strtolower($row['country_code']) . '.png"/ alt="' . $row['name'] . '" /></div>') : ''?>
          <?=anchor(array('music', url_title($row['artist_name'])), '<div class="cover artist_img img150" style="background-image:url(' . getArtistImg(array('artist_id' => $row['artist_id'], 'size' => 174)) . ')"></div><div class="meta"><div class="title main">' . anchor(array('music', url_title($row['artist_name'])), substrwords($row['artist_name'], 35), array('title' => 'Browse to artist\'s page')) . '</div></div>', array('title' => 'Browse to artis\'s page'))?>
          <?php
        }
        else {
          echo '<div class="cover artist_img img150" style="background-image:url(' . getArtistImg(array('artist_id' => $row['artist_id'], 'size' => 174)) . ')"></div><div class="meta"><div class="title main">' . substrwords($row['artist_name'], 35) . '</div></div>';
        }
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
  elseif (is_array($json_data)) {
    echo $json_data['error']['msg'];
  }
  else {
    echo $json_data;
  }
}
else {
  echo ERR_NO_RESULTS;
}
?>