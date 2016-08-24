<div id="headingCont" class="artist_heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
  <h1>
    <div><span class="stats">stats</span><span class="ter">ter</span><span class="separator"></span><span class="meta">reconcile with music</span></div>
    <div class="top_music">
      <div><?=anchor(array('music', url_title($top_artist['artist_name'])), $top_artist['artist_name'], array('title' => $top_artist['count'] . ' listenings', 'class' => 'tooltip'))?></div>
    </div>
  </h1>
</div>
<div class="clear"></div>
<div id="mainCont" class="heading_container">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('shout'), 'Shouts')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <!--<h1></h1>-->
      <br />
      <?=form_open('', array('class' => '', 'id' => 'addListeningForm'), array('addListeningType' => 'form'))?>
        <div id="addListeningDateContainer" class="listening_date">
          Listening date: <input name="date" title="Change date" id="addListeningDate" class="number" value="<?=CUR_DATE?>" />
        </div>
        <div>
          <input type="text" autocomplete="off" tabindex="1" id="addListeningText" placeholder="♪ ♪ ♪" name="addListeningText" />
        </div>
        <div>
          <input type="submit" name="addListeningSubmit" tabindex="4" id="addListeningSubmit" value="statster" />
        </div>
        <div>
          <input type="radio" name="addListeningFormat" value="Stream:Spotify Unlimited" id="format_0" class="hidden" /><label for="format_0"><img src="/media/img/format_img/spotify_logo.png" tabindex="2" class="listening_format desktop_format" title="Spotify Unlimited" alt="" /></label>
          <input type="radio" name="addListeningFormat" value="File:Kodi" id="format_1" class="hidden" /><label for="format_1"><img src="/media/img/format_img/xbmc_logo.png" tabindex="2" class="listening_format desktop_format" title="Kodi" alt="" /></label>
          <input type="radio" name="addListeningFormat" value="File:Samsung Galaxy S II" id="format_2" class="hidden" /><label for="format_2"><img src="/media/img/format_img/smartphone_logo.png" tabindex="2" class="listening_format" title="Samsung Galaxy S II" alt="" /></label>
          <input type="radio" name="addListeningFormat" value="File:Portable Device" id="format_3" class="hidden" /><label for="format_3"><img src="/media/img/format_img/headphones_logo.png" tabindex="2" class="listening_format" title="Portable Device" alt="" /></label>
          <input type="radio" name="addListeningFormat" value="Compact Disc:Compact Disc" id="format_4" class="hidden" /><label for="format_4"><img src="/media/img/format_img/cdrom_logo.png" tabindex="2" class="listening_format desktop_format" title="Compact Disc" alt="" /></label>
          <!--<input type="radio" name="addListeningFormat" id="winampFormat" class="hidden" /><label for="winampFormat"><img src="/media/img/format_img/winamp_logo.png" tabindex="3" class="listening_format _fidden" title="Winamp" alt="" /></label>
          <input type="radio" name="addListeningFormat" id="itunesFormat" class="" /><label for="itunesFormat"><img src="/media/img/format_img/itunes_logo.png" tabindex="3" class="listening_format _fidden" title="iTunes" alt="" /></label>
          <input type="radio" name="addListeningFormat" id="showmoreFormat" class="" /><label for="showmoreFormat"><img src="/media/img/format_img/showmore_logo.png" tabindex="3" class="listening_format" id="addListeningShowmore" title="" alt="" /></label>-->
        </div>
      </form>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Recently listened <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="recentlyListenedLoader2" /> <span class="func_container"><i class="fa fa-refresh" id="refreshRecentAlbums"></i></span></h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader"/>
      <table id="recentlyListened" class="music_table" style="margin-top: -12px;"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor('recent', 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Hot albums</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumLoader" />
      <ul id="topAlbum" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Statistics</h1>
    </div>
    <div class="container">
      <h2>Hot artists</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistLoader" />
      <table id="topArtist" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h1>What's hot</h1>
      <h2>Popular albums <span class="func_container"><i class="fa fa-refresh" id="refreshPopularAlbums"></i></span></h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recommentedTopAlbumLoader" />
      <table id="recommentedTopAlbum" class="side_table"><!-- Content is loaded with AJAX --></table>
      <h2>New releases <span class="func_container"><i class="fa fa-refresh" id="refreshNewAlbums"></i></span></h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recommentedNewAlbumLoader" />
      <table id="recommentedNewAlbum" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>