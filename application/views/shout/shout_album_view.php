<div id="headingCont"> 
  <div class="heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>')">
    <div class="info">
      <div class="float_left cover album_img img174" style="background-image:url('<?=getAlbumImg(array('album_id' => $album_id, 'size' => 174))?>')">
        <?php
        if ($spotify_uri !== FALSE) {
          ?>
          <a href="<?=$spotify_uri?>" class="spotify_link"><div class="spotify_container album_spotify_container"></div></a>
          <?php
        }
        ?>
        <span class="album_year number"><?=anchor(array('year', $year), $year, array('class' => 'album_year'))?></span>
      </div>
      <div class="top_info album_info">
        <h2><?=anchor(array('music', url_title($artist_name)), $artist_name)?></h2>
        <h1><?=anchor(array('music', url_title($artist_name), url_title($album_name)), $album_name)?></h1>
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
  <div class="album_meta">
    <div class="meta">
      <div class="label">Listenings</div>
      <div class="value number"><?=anchor(array('recent', url_title($artist_name), url_title($album_name)), number_format($total_count))?></div>
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
          <div class="user_listenings_img cover img32" style="background-image: url('<?=getUserImg(array('user_id' => $this->session->userdata('user_id'), 'size' => 32))?>');"></div><span class="user_value"><span class="value number"><?=anchor(array('recent', url_title($artist_name), url_title($album_name) . '?u=' . $this->session->userdata('username')), number_format($user_count))?></span> in your library</span>
          <span id="love" class="like_toggle"><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="loveLoader"/><span class="like_msg"></span></span>
        </div>
      </div>
      <?php
    }
    ?>
  </div>
</div>
<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('format', url_title($artist_name), url_title($album_name)), 'Formats')?>
    <?=anchor(array('like', url_title($artist_name), url_title($album_name)), 'Likes')?>
    <?=anchor(array('listener', url_title($artist_name), url_title($album_name)), 'Listeners')?>
    <?=anchor(array('recent', url_title($artist_name), url_title($album_name)), 'Listenings')?>
    <?=anchor(array('shout', url_title($artist_name), url_title($album_name)), 'Shouts')?>
    <?=anchor(array('tag', url_title($artist_name), url_title($album_name)), 'Tags')?>
    <div class="float_right">
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="albumLoveLoader"/>
      <ul id="albumLove" class="like_list no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div id="leftCont">
    <div class="container">
      <h1>Shouts</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="albumShoutLoader" />
      <table id="albumShout" class="shout_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Top listeners</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListenerLoader"/>
      <table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('listener', url_title($artist_name)), 'More listeners', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Latest listenings</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader"/>
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('recent', url_title($artist_name)), 'More listenings', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
  </div>