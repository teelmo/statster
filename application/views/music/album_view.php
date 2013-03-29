<div id="leftCont">
  <div class="container">
    <span id="love"><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="loveLoader"/></span>
    <h1><div class="desc"><?=anchor(array('music', url_title($artist_name)), $artist_name, array('title' => 'Browse to artist\'s page'))?></div><?=$album_name?> <span class="albumYear">(<?=$year?>)</span></h1>
  </div>
  <div class="container">
    <div class="tags">
      <?php
      foreach ($tags as $tag) {
        ?>
        <span class="tag <?=$tag['type']?>"><?=anchor(array('tag', $tag['type'], url_title($tag['name'])), $tag['name'])?></span>
        <?php
      }
      ?>
      <span class="tag moretags"><a href="javascript:;">+</a></span>
      <div class="tag" id="tagAdd">
        <select id="tagAddSelect" data-placeholder="Add metadata" class="chzn-select" multiple>
          <optgroup label="Genres" id="genre">
          </optgroup>
          <optgroup label="Keywords" id="keyword">
          </optgroup>
          <optgroup label="Nationality" id="nationalitiy">
          </optgroup>
        </select>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="floatLeft">
      <img src="<?=getAlbumImg(array('album_id' => $album_id, 'size' => 174))?>" alt="" class="albumImg albumImg300" />
    </div>
    <div class="albumInfo">
      <div class="floatLeft">
        <div class="count"><?=$total_count?></div><div class="desc"> listenings</div>
      </div>
      <?php
      if (!empty($user_count)) {
        ?>
        <div class="floatLeft">
          <div class="count"><small><?=$user_count?></small></div><div class="desc">in your library</div>
        </div>
        <?php
      }
      ?>
      <h3 class="albumLove floatLeft">Album's loves</h3>
      <div>
        <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="albumLoveLoader"/>
        <ul id="albumLove" class="likeList noBullets"><!-- Content is loaded with AJAX --></ul>
      </div>
      <br />
      <div>
        <div>
          <div class="externalLink">
            <?=anchor('http://spotify', '<img src="' . site_url() . '/media/img/format_img/format_icons/spotify.png" alt="" class="icon" /> Search on Spotify')?>
          </div>
          <div class="externalLink">
            <?=anchor('http://lastfm', '<img src="' . site_url() . '/media/img/format_img/format_icons/lastfm.png" alt="" class="icon" /> Search on Last.fm')?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="clear"></div>
  <div class="container">
    <h2 id="biography">Biography</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="artistBioLoader"/>
    <div id="artistBio"><!-- Content is loaded with AJAX --></div>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h2>Artist's albums</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="artistAlbumLoader"/>
    <ul id="artistAlbum" class="chartList chartList124 noBullets"><!-- Content is loaded with AJAX --></ul>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h2>Similar artists</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="similarArtistLoader"/>
    <ul id="similarArtist" class="chartList chartList124 noBullets"><!-- Content is loaded with AJAX --></ul>
  </div>

  <!--
  <div class="container"><hr /></div>
  <div class="container">
    <h2>Shoutbox</h2>
  </div>
  -->
  <div class="container"><hr /></div>
</div>
<div id="rightCont">
  <div class="container">
    <h1>Statistics</h1>
    <h2>Top listeners</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListenerLoader"/>
    <table id="topListener" class="userTable"><!-- Content is loaded with AJAX --></table>
    <div class="more">
      <?=anchor(array('listener', url_title($artist_name), url_title($album_name)), 'See more', array('title' => 'Browse more listenings'))?>
    </div>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h2>Latest listenings</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader"/>
    <table id="recentlyListened" class="userTable"><!-- Content is loaded with AJAX --></table>
    <div class="more">
      <?=anchor(array('recent', url_title($artist_name), url_title($album_name)), 'See more', array('title' => 'Browse more listenings'))?>
    </div>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h2>On tour</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="artistEventLoader"/>
    <table id="artistEvent" class="eventTable"><!-- Content is loaded with AJAX --></table>
    <div class="more">
      <?=anchor('http://www.last.fm/music/' . urlencode($artist_name) . '/+events', 'See more', array('title' => 'Browse more events'))?>
    </div>
  </div>
</div>