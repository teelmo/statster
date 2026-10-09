<div class="heading_container">
  <div class="heading_cont main_heading_cont">
    <div class="info">
      <h1><?=anchor(array('user', url_title($username)), $username)?><span class="separator"></span><span class="meta slogan">Owned albums</span></h1>
    </div>
  </div>
</div>
<div class="main_container">
  <div class="full_container">
    <div class="container">
      <div class="owned_album_filters">
        <select data-placeholder="Filter by format" class="chosen-select" id="ownedAlbumFormatFilter" multiple>
          <?php foreach ($ownable_formats as $format) : ?>
            <option value="<?=$format['id']?>"><?=html_escape($format['name'])?></option>
          <?php endforeach; ?>
        </select>
        <input type="text" id="ownedAlbumArtistFilter" placeholder="Filter by artist…" autocomplete="off" />
      </div>
      <?php if (empty($owned_albums)) : ?>
        <?=ERR_NO_RESULTS?>
      <?php else : ?>
        <?php
        prefetchImagePaths(array_merge(...array_map(function($group) {
          return array_map(function($album) { return array('type' => 'album', 'size' => 174, 'id' => $album['album_id']); }, $group['albums']);
        }, $owned_albums)));
        ?>
        <ul class="music_list music_list_150 no_bullets">
          <?php foreach ($owned_albums as $group) : ?>
            <li class="block"><h3><?=anchor(array('music', url_title($group['artist_name'])), $group['artist_name'])?></h3></li>
            <?php foreach ($group['albums'] as $album) : ?>
              <li class="album" data-artist="<?=html_escape(strtolower($group['artist_name']))?>" data-formats="<?=implode(',', array_column($album['formats'], 'id'))?>">
                <?=anchor(array('music', url_title($group['artist_name']), url_title($album['album_name'])), '<span></span>', array('title' => 'Browse to album\'s page'))?>
                <div class="cover album_img img150" style="background-image:url(<?=getAlbumImg(array('album_id' => $album['album_id'], 'size' => 174))?>)">
                  <div class="meta">
                    <div class="title main"><?=anchor(array('music', url_title($group['artist_name']), url_title($album['album_name'])), substrwords($album['album_name'], 35), array('title' => 'Browse to album\'s page'))?></div>
                  </div>
                  <?php if (!empty($album['formats'])) : ?>
                    <span class="icons">
                      <?php foreach ($album['formats'] as $format) : ?>
                        <img src="<?=$format['icon']?>" class="icon" alt="" title="<?=html_escape($format['name'])?>" />
                      <?php endforeach; ?>
                    </span>
                  <?php endif; ?>
                </div>
              </li>
            <?php endforeach; ?>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
</div>
