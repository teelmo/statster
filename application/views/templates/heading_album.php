<div class="heading_container">
  <div class="heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>')">
    <div class="info">
      <div class="float_left cover album_img img174" style="background-image:url('<?=getAlbumImg(array('album_id' => $album_id, 'size' => 174))?>')">
        <?php
        if ($spotify_id !== FALSE) {
          ?>
          <a href="spotify:album:<?=$spotify_id?>" class="spotify_link" aria-label="Open in Spotify"><div class="spotify_container album_spotify_container"></div></a>
          <?php
        }
        ?>
        <?=($most_listened_releaseyear !== false) ? '<span class="rank">#<span class="number">' . $most_listened_releaseyear . '</span></span>' : ''?>
        <span class="album_year number"><?=anchor(array('year', $year), $year, array('class' => 'album_year'))?></span>
      </div>
      <div class="top_info album_info">
        <h2><?=implode('<span class="artist_separator">, </span>', array_map(function($artist) { return anchor(array('music', url_title($artist['artist_name'])), $artist['artist_name']);}, $artists))?></h2>
        <h1>
          <?php
          if (!empty($is_current_page)) {
            echo $album_name;
            if (!empty($this->session->userdata['user_id']) && in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
              echo anchor(array('admin', 'album', $album_id . '?artist=' . $artist_name), '<span class="mask-icon mask-icon-pen-square" aria-hidden="true"></span>', array('aria-label' => 'Edit album'));
            }
          }
          else {
            echo anchor(array('music', url_title($artist_name), url_title($album_name)), $album_name);
          }
          ?>
        </h1>
        <div class="lds-facebook inline" id="tagsLoader"><div></div><div></div><div></div></div>
        <ul id="tags"><!-- Content is loaded with AJAX --></ul>
        <div id="tagAdd" class="hidden">
          <select data-placeholder="Add metadata" class="chosen-select" name="add_metadata" multiple>
            <optgroup label="Genres" id="genre"></optgroup>
            <optgroup label="Keywords" id="keyword"></optgroup>
            <optgroup label="Nationality" id="nationality"></optgroup>
          </select>
          <button type="submit" id="submitTags" class="submit" title="Add" aria-label="Add tags"></button>
        </div>
      </div>
    </div>
  </div>
  <div class="meta_container">
    <div class="meta">
      <div class="label">Listenings</div>
      <div class="value number"><span class="<?=(!isset($per_year) || $per_year === NULL) ? '' : 'data_per_year'?>" data-per-year="<?=isset($per_year) ? $per_year : ''?>"><?=anchor(array('recent', url_title($artist_name), url_title($album_name)), number_format($total_count))?></span><?=($most_listened_alltime !== false) ? ', ' . anchor(array('album'), '<span class="rank">#<span class="number">' . $most_listened_alltime . '</span></span>') : ''?></div>
    </div>
    <div class="meta">
      <div class="label">Listeners</div>
      <div class="value number"><?=anchor(array('listener', url_title($artist_name), url_title($album_name)), number_format($listener_count))?></div>
    </div>
    <div class="meta">
      <div class="label">Added</div>
      <div class="value number"><?=anchor(array('year', $created), $created)?></div>
    </div>
    <?php
    if ($logged_in === 'true') {
      ?>
      <div class="meta">
        <div class="label user_listening">
          <div class="user_listening_image" title="<?=($total_count > 0) ? intval(($user_count / $total_count) * 100) : 0?>%">
            <svg viewBox="0 0 36 36">
              <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke-width="4" stroke-dasharray="100, 100" />
              <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke-width="4" stroke-dasharray="<?=($total_count > 0) ? ($user_count / $total_count) * 100 : 0?>, 100" class="similarity_path" />
            </svg>
            <div class="user_listenings_img cover img32" style="background-image: url('<?=getUserImg(array('user_id' => $this->session->userdata('user_id'), 'size' => 32))?>');"></div>
          </div>
          <span class="user_value"><span class="value number"><span class="<?=(!isset($per_year_user) || $per_year_user === NULL) ? '' : 'data_per_year_user'?>" data-per-year="<?=isset($per_year_user) ? $per_year_user : ''?>"><?=anchor(array('recent', url_title($artist_name), url_title($album_name) . '?u=' . $this->session->userdata('username')), number_format($user_count))?></span></span> in your library<?=($most_listened_alltime_user !== false) ? ', ' . anchor(array('album' . '?u=' . $this->session->userdata('username')), '<span class="rank">#<span class="number">' . $most_listened_alltime_user . '</span></span>') : ''?></span>
          <span id="love" class="like_toggle" aria-label="Love this album"><div class="lds-facebook inline" id="loveLoader"><div></div><div></div><div></div></div><span class="like_msg"></span></span>
          <?php
          if (!empty($is_current_page)) {
            ?>
            <span id="quick_add_listening" class="quick_add_listening">
              <span class="mask-icon mask-icon-plus-square" aria-label="Add listening"></span>
              <ul class="subnav hidden">
                <?php
                foreach(unserialize($this->session->formats) as $key => $format) {
                  list($format, $format_type) = array_pad(explode(':', $format), 2, false);
                  ?>
                    <li data-value="<?=(empty($format_type) ? $format : $format . ':' . $format_type)?>"><img src="/media/img/format_img/format_icons/<?=(empty($format_type) ? getFormatImg(array('format' => $format)) : getFormatTypeImg(array('format_type' => $format_type)))?>.png" tabindex="<?=($key + 2)?>" class="middle icon listening_format_type" title="<?=(empty($format_type) ? $format : $format_type)?>" alt="" /> <?=$format_type?></li>
                  <?php
                }
                ?>
              </ul>
            </span>
            <?php
          }
          ?>
        </div>
      </div>
      <?php
    }
    ?>
  </div>
</div>
<div class="main_container">
  <div class="page_links">
    <?=anchor(array('format', url_title($artist_name), url_title($album_name)), 'Formats')?>
    <?=anchor(array('like', url_title($artist_name), url_title($album_name)), 'Likes')?>
    <?=anchor(array('listener', url_title($artist_name), url_title($album_name)), 'Listeners')?>
    <?=anchor(array('recent', url_title($artist_name), url_title($album_name)), 'Listenings')?>
    <?=anchor(array('shout', url_title($artist_name), url_title($album_name)), 'Shouts')?>
    <?=anchor(array('tag', url_title($artist_name), url_title($album_name)), 'Tags')?>
    <div class="album_love_container">
      <div class="lds-facebook inline" id="albumLoveLoader"><div></div><div></div><div></div></div>
      <ul id="albumLove" class="like_list no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div class="left_container">
