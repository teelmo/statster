<div id="headingCont" class="artist_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>')">
  <div class="inner">
    <div class="float_left">
      <div class="cover artist_img img174" style="background-image:url('<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 174))?>')">
        <?php
        if ($spotify_uri !== FALSE) {
          ?>
          <a href="<?=$spotify_uri?>" class="spotify_link"><div class="spotify_container artist_spotify_container"></div></a>
          <?php
        }
        ?>
      </div>
    </div>
    <div class="info">
      <div class="top_info artist_info">
        <h1><?=anchor(array('music', url_title($artist_name)), $artist_name)?></h1>
      </div>
      <table class="artist_meta">
        <tr>
          <td class="label">Listenings</td>
          <td class="label">Listeners</td>
          <td class="label">Added in</td>
          <?php
          if ($logged_in === 'true') {
            ?>
            <td class="label user_listening" rowspan="3">
              <div class="user_listenings_img cover img32" style="background-image: url('<?=getUserImg(array('user_id' => $this->session->userdata('user_id'), 'size' => 32))?>');"></div><span class="user_value"><span class="value number"><?=anchor(array('recent', url_title($artist_name) . '?u=' . $this->session->userdata('username')), number_format($user_count))?></span> in your library</span>
              <span id="fan" class="like_toggle"><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="fanLoader"/><span class="like_msg"></span></span>
            </td>
            <?php
          }
          ?>
        </tr>
        <tr>
          <td class="value number"><?=anchor(array('recent', url_title($artist_name)), number_format($total_count))?></td>
          <td class="value number"><?=anchor(array('listener', url_title($artist_name)), number_format($listener_count))?></td>
          <td class="value number"><?=anchor(array('tag', 'year', $created), $created)?></td>
        </tr>
      </table>
    </div>
  </div>
</div>
<div class="clear"></div>
<div id="mainCont" class="heading_container">
  <div class="page_links">
    <?=anchor(array('like', url_title($artist_name)), 'Likes')?>
    <?=anchor(array('listener', url_title($artist_name)), 'Listeners')?>
    <?=anchor(array('recent', url_title($artist_name)), 'Listenings')?>
    <?=anchor(array('tag', url_title($artist_name)), 'Tags')?>
    <div class="float_right">
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="artistFanLoader"/>
      <ul id="artistFan" class="like_list no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div id="leftCont">
    <div class="container">
      <h1>
        <?php
        if (!empty($album_name)) {
          ?>
          <div class="desc"><?=anchor(array('music', url_title($artist_name), url_title($album_name)), $artist_name . ' ' . DASH . ' ' . $album_name, array('title' => 'Browse to album\'s page'))?></div>
          <?php
        }
        elseif (!empty($artist_name)) {
          ?>
          <div class="desc"><?=anchor(array('music', url_title($artist_name)), $artist_name, array('title' => 'Browse to artist\'s page'))?></div>
          <?php
        }
        ?>Recently listened <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="recentlyListenedLoader2" /> <span class="func_container"><i class="fa fa-refresh" id="refreshRecentAlbums"></i></span>
      </h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader"/>
      <table id="recentlyListened" class="music_table" style="margin-top: -12px;"><!-- Content is loaded with AJAX --></table>
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
