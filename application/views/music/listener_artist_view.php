<div class="heading_container">
  <div class="heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>')">
    <div class="info">
      <div class="float_left cover artist_img img174" style="background-image:url('<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 174))?>')">
        <?php
        if ($spotify_uri !== FALSE) {
          ?>
          <a href="<?=$spotify_uri?>" class="spotify_link"><div class="spotify_container artist_spotify_container"></div></a>
          <?php
        }
        ?>
      </div>
      <div class="top_info artist_info">
        <h1><?=anchor(array('music', url_title($artist_name)), $artist_name)?></h1>
        <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="tagsLoader"/>
        <ul id="tags"><!-- Content is loaded with AJAX --></ul>
        <div id="tagAdd" class="hidden">
          <select data-placeholder="Add metadata" class="chosen-select" multiple>
            <optgroup label="Genres" id="genre"></optgroup>
            <optgroup label="Keywords" id="keyword"></optgroup>
            <optgroup label="Nationality" id="nationality"></optgroup>
          </select>
          <button type="submit" id="submitTags" class="submit" title="Add"></button>
        </div>
      </div>
    </div>
  </div>
  <div class="artist_meta">
    <div class="meta">
      <div class="label">Listenings</div>
      <div class="value number"><?=anchor(array('recent', url_title($artist_name)), number_format($total_count))?></div>
    </div>
    <div class="meta">
      <div class="label">Listeners</div>
      <div class="value number"><?=anchor(array('listener', url_title($artist_name)), number_format($listener_count))?></div>
    </div>
    <div class="meta">
      <div class="label">Added</div>
      <div class="value number"><?=anchor(array('year', $created), $created)?></div>
    </div>
    <?php
    if ($this->session->userdata('logged_in') === TRUE) {
      ?>
      <div class="meta">
        <div class="label user_listening">
          <div class="user_listening_image" title="<?=intval(($user_count / $total_count) * 100)?>%">
            <svg viewBox="0 0 36 36">
              <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="rgba(182, 192, 191, 0.5)"; stroke-width="4"; stroke-dasharray="100, 100" />
              <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#666"; stroke-width="4"; stroke-dasharray="<?=intval(($user_count / $total_count) * 100)?>, 100" class="similarity_path" />
            </svg>
            <div class="user_listenings_img cover img32" style="background-image: url('<?=getUserImg(array('user_id' => $this->session->userdata('user_id'), 'size' => 32))?>');"></div>
          </div>
          <span class="user_value"><span class="value number"><?=anchor(array('recent', url_title($artist_name) . '?u=' . $this->session->userdata('username')), number_format($user_count))?></span> in your library</span>
          <span id="fan" class="like_toggle"><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="fanLoader"/><span class="like_msg"></span></span>
        </div>
      </div>
      <?php
    }
    ?>
  </div>
</div>
<div class="main_container">
  <div class="page_links">
    <?=anchor(array('format', url_title($artist_name)), 'Formats')?>
    <?=anchor(array('like', url_title($artist_name)), 'Likes')?>
    <?=anchor(array('listener', url_title($artist_name)), 'Listeners')?>
    <?=anchor(array('recent', url_title($artist_name)), 'Listenings')?>
    <?=anchor(array('shout', url_title($artist_name)), 'Shouts')?>
    <?=anchor(array('tag', url_title($artist_name)), 'Tags')?>
    <div class="float_right">
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="artistFanLoader"/>
      <ul id="artistFan" class="like_list no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div class="left_container">
    <div class="container">
      <h1>Listeners</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListenerLoader"/>
      <table id="topListener" class="side_table full"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Latest listenings</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader"/>
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?php
        if (!empty($album_name)) {
          echo anchor(array('recent', url_title($artist_name), url_title($album_name)), 'More', array('title' => 'Browse more listenings'));
        }
        elseif (!empty($artist_name)) {
          echo anchor(array('recent', url_title($artist_name)), 'More', array('title' => 'Browse more listenings'));
        }
        ?>
      </div>
    </div>
  </div>