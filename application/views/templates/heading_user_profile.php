<div class="heading_container">
  <?php
  if (isset($artist_id)) {
    ?>
    <div class="heading_cont profile_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>')" title="#1 artist: <?=$artist_name?>">
      <div class="info">
        <div class="float_left cover user_img img174" style="background-image:url('<?=getUserImg(array('user_id' => $user_id, 'size' => 174))?>')"></div>
        <div class="top_info user_info">
          <h1><?=anchor(array('user', url_title($username)), $username)?></h1>
          <h4><span class="username"><?=($real_name) ? htmlentities($real_name) : htmlentities($username) ?></span><span class="meta"> • <?=($joined_year) ? 'active since ' . $joined_year : 'active since long time ago'?> • <?=anchor(array('user', url_title($username), 'collection'), 'Album collection')?></span></h4>
          <ul id="tags">
            <?php
            foreach ($tags as $tag) {
              ?>
              <li class="tag <?=$tag['type']?>"><?=anchor(array($tag['type'], url_title($tag['name']) . '?u=teelmo'), '<i class="mask-icon mask-icon-music" aria-hidden="true"></i> ' . $tag['name'] . '</i>')?></li>
              <?php
            }
            ?>
          </ul>
        </div>
      </div>
    </div>
    <?php
  }
  ?>
  <div class="meta_container">
    <div class="meta">
      <div class="label">Listenings</div>
      <div class="value number"><span class="<?=($per_year === NULL) ? '' : 'data_per_year_user'?>" data-per-year="<?=$per_year?>"><?=anchor(array('recent?u=' . $username), number_format($listening_count))?></span></div>
    </div>
    <div class="meta">
      <div class="label">Albums</div>
      <div class="value number"><?=anchor(array('album?u=' . $username), number_format($album_count))?></div>
    </div>
    <div class="meta">
      <div class="label">Artists</div>
      <div class="value number"><?=anchor(array('artist?u=' . $username), number_format($artist_count))?></div>
    </div>
    <div class="meta">
      <div class="label">Shouts</div>
      <div class="value number"><?=anchor(array('shout?u=' . $username), number_format($shout_count))?></div>
    </div>
    <div class="meta">
      <div class="label">Loved</div>
      <div class="value number"><?=anchor(array('love?u=' . $username), number_format($love_count))?></div>
    </div>
    <div class="meta">
      <div class="label">Faned</div>
      <div class="value number"><?=anchor(array('fan?u=' . $username), number_format($fan_count))?></div>
    </div>
  </div>
</div>
<div class="page_links">
  <?=anchor('album?u=' . $username, 'Albums')?>
  <?=anchor('artist?u=' . $username, 'Artists')?>
  <?=anchor('format?u=' . $username, 'Formats')?>
  <?=anchor('recent?u=' . $username, 'Library')?>
  <?=anchor('like?u=' . $username, 'Likes')?>
  <?=anchor('shout?u=' . $username, 'Shouts')?>
  <?=anchor('tag?u=' . $username, 'Tags')?>
</div>
