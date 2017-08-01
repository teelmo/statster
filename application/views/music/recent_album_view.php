<div id="headingCont" class="artist_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>')">
  <div class="inner">
    <div class="float_left">
      <div class="cover album_img img174" style="background-image:url('<?=getAlbumImg(array('album_id' => $album_id, 'size' => 174))?>')">
        <?php
        if ($spotify_uri !== FALSE) {
          ?>
          <a href="<?=$spotify_uri?>" class="spotify_link"><div class="spotify_container album_spotify_container"></div></a>
          <?php
        }
        ?>
        <span class="album_year number"><?=anchor(array('year', $year), $year, array('class' => 'album_year'))?></span>
      </div>
    </div>
    <div class="info">
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
      <table class="album_meta">
        <tr>
          <td class="label">Listenings</td>
          <td class="label">Listeners</td>
          <td class="label">Added</td>
          <?php
          if ($logged_in === 'true') {
            ?>
            <td class="label user_listening" rowspan="3">
              <div class="user_listenings_img cover img32" style="background-image: url('<?=getUserImg(array('user_id' => $this->session->userdata('user_id'), 'size' => 32))?>');"></div><span class="user_value"><span class="value number"><?=anchor(array('recent', url_title($artist_name), url_title($album_name) . '?u=' . $this->session->userdata('username')), number_format($user_count))?></span> in your library</span>
              <span id="love" class="like_toggle"><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="loveLoader"/><span class="like_msg"></span></span>
            </td>
            <?php
          }
          ?>
        </tr>
        <tr>
          <td class="value number"><?=anchor(array('recent', url_title($artist_name), url_title($album_name)), number_format($total_count))?></td>
          <td class="value number"><?=anchor(array('listener', url_title($artist_name), url_title($album_name)), number_format($listener_count))?></td>
          <td class="value number"><?=anchor(array('tag', 'year', $created), $created)?></td>
        </tr>
      </table>
    </div>
  </div>
</div>
<div class="clear"></div>
<div id="mainCont" class="heading_container">
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
      <h1>Listenings</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader"/>
      <table id="recentlyListened" class="music_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Top listeners</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListenerLoader"/>
      <table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?php
        if (!empty($album_name)) {
          echo anchor(array('listener', url_title($artist_name), url_title($album_name)), 'See more', array('title' => 'Browse more listenings'));
        }
        elseif (!empty($artist_name)) {
          echo anchor(array('listener', url_title($artist_name)), 'See more', array('title' => 'Browse more listenings'));
        }
        ?>
      </div>
    </div>
  </div>
