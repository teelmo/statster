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
        <h1><?=$album_name?></h1>
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
          <td class="label">Added in</td>
          <?php
          if ($logged_in === TRUE) {
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
    <?=anchor(array('like', url_title($artist_name), url_title($album_name)), 'Likes')?>
    <?=anchor(array('listener', url_title($artist_name), url_title($album_name)), 'Listeners')?>
    <?=anchor(array('recent', url_title($artist_name), url_title($album_name)), 'Listenings')?>
    <div class="float_right">
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="albumLoveLoader"/>
      <ul id="albumLove" class="like_list no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div id="leftCont">
    <?php
    if (!empty($bio_summary)) {
      ?>
      <div class="container">
        <h2>Biography</h2>
        <p class="summary"><?=nl2br($bio_summary)?></p>
        <div class="more moreDown">
          <?=anchor('', 'See more', array('title' => 'See full biography', 'id' => 'biographyMore'))?>
        </div>
        <p class="content hidden"><?=nl2br($bio_content)?></p>
        <div class="more moreUp ">
          <?=anchor('', 'See less', array('title' => 'Suppress biograhpy', 'id' => 'biographyLess', 'class' => 'hidden'))?>
        </div>
      </div>
      <?php
    }
    ?>
    <div class="container">
      <h2>History</h2>
      <div class="float_right settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%w')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%d')">Day</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%m')">Month</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('%Y')">Year</a> | <a href="javascript:;" onclick="view.getListeningHistory('%Y%m')" class="unactive">Montly</a>
      </div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="historyLoader"/>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="music_bar"></div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Artist's albums</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="artistAlbumLoader"/>
      <ul id="artistAlbum" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Similar</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="similarArtistLoader"/>
      <ul id="similarArtist" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Shoutbox <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="shoutLoader2" /><span id="shoutTotal"></span></h2>
      <table class="shout_table">
        <?php
        if ($logged_in === TRUE) {
          ?>
          <tr class="post_shout">
            <td class="img user_img">
              <?=anchor(array('user', url_title($this->session->userdata('username'))), '<div class="cover user_img img64" style="background-image:url(' . $this->session->userdata('user_image') . ')"></div>', array('title' => 'Browse to user\'s page'))?>
            </td>
            <td class="textarea" colspan="2">
              <input type="hidden" id="contentID" value="<?=$album_id?>" />
              <input type="hidden" id="contentType" value="album" />
              <div><textarea placeholder="Post a shout…" id="shoutText"></textarea></div>
              <div><button id="shoutSubmit">post shout</button></div>
            </td>
          </tr>
          <?php
        }
        ?>
      </table>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="shoutLoader"/>
      <table id="shout" class="shout_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Top listeners</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListenerLoader"/>
      <table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('listener', url_title($artist_name), url_title($album_name)), 'More listeners', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Latest listenings</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader"/>
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('recent', url_title($artist_name), url_title($album_name)), 'More listenings', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
  </div>