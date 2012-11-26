<div id="leftCont">
  <div class="container">
    <!--<h1></h1>-->
    <br />
    <?=form_open('', array('class' => '', 'id' => 'addListeningForm'), array('addListeningType' => 'form'))?>
      <div id="addListeningDateContainer" class="listeningDate">
        Listening date: <a href="javascript:" title="Change date" onlick="return false;"><?=CUR_DATE?></a>
        <input type="hidden" name="date" id="addListeningDate" value="<?=CUR_DATE?>"/>
      </div>
      <div>
        <input type="text" autocomplete="off" tabindex="1" id="addListeningText" placeholder="What&rsquo;s ya listening?" name="addListeningText" />
      </div>
      <div>
        <input type="submit" name="addListeningSubmit" tabindex="4" id="addListeningSubmit" value="Statster" />
      </div>
      <div>
        <input type="radio" name="addListeningFormat" value="Stream:Spotify Unlimited" id="format_0" class="hidden" /><label for="format_0"><img src="./media/img/format_img/spotify_logo.png" tabindex="2" class="listeningFormat" title="Spotify Unlimited" alt="" /></label>
        <input type="radio" name="addListeningFormat" value="File:XBMC Media Center" id="format_1" class="hidden" /><label for="format_1"><img src="./media/img/format_img/xbmc_logo.png" tabindex="2" class="listeningFormat" title="XBMC Media Center" alt="" /></label>
        <input type="radio" name="addListeningFormat" value="File:Samsung Galaxy S II" id="format_2" class="hidden" /><label for="format_2"><img src="./media/img/format_img/smartphone_logo.png" tabindex="2" class="listeningFormat" title="Samsung Galaxy S II" alt="" /></label>
        <input type="radio" name="addListeningFormat" value="File:Portable Device" id="format_3" class="hidden" /><label for="format_3"><img src="./media/img/format_img/headphones_logo.png" tabindex="2" class="listeningFormat" title="Portable Device" alt="" /></label>
        <input type="radio" name="addListeningFormat" value="Compact Disc:Compact Disc" id="format_4" class="hidden" /><label for="format_4"><img src="./media/img/format_img/cdrom_logo.png" tabindex="2" class="listeningFormat" title="Compact Disc" alt="" /></label>
        <!--<input type="radio" name="addListeningFormat" id="winampFormat" class="hidden" /><label for="winampFormat"><img src="/media/img/format_img/winamp_logo.png" tabindex="3" class="listeningFormat hidden" title="Winamp" alt="" /></label>
        <input type="radio" name="addListeningFormat" id="itunesFormat" class="" /><label for="itunesFormat"><img src="/media/img/format_img/itunes_logo.png" tabindex="3" class="listeningFormat hidden" title="iTunes" alt="" /></label>
        <input type="radio" name="addListeningFormat" id="showmoreFormat" class="" /><label for="showmoreFormat"><img src="/media/img/format_img/showmore_logo.png" tabindex="3" class="listeningFormat" id="addListeningShowmore" title="" alt="" /></label>-->
      </div>
    </form>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h2>Recently listened <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="recentlyListenedLoader2" /></h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader"/>
    <table id="recentlyListened" class="chartTable"><!-- Content is loaded with AJAX --></table>
    <div class="more">
      <?=anchor('recent', 'See more/edit', array('title' => 'Browse more listenings'))?>
    </div>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h2>Hot albums</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumLoader" />
    <ul id="topAlbum" class="chartList chartList124 noBullets"><!-- Content is loaded with AJAX --></ul>
  </div>
  <div class="container"><hr /></div>
</div>
<div id="rightCont">
  <div class="container">
    <h1>Statistics</h1>
  </div>
  <div class="container">
    <!--
    <ul class="noBullets">
      <li>Most Listened Album Last Month: Tool - Lateralus</li>
      <li>Most Listened Artist Last Month: Penniless</li>
      <li>Listenings in March: 0 <span>(0 in total)</span></li>
      <li>Listenings in 2012: 0 <span>(0 in total)</span></li>
      <li>Your listening count: 13817 <span>(32293 in total)</span></li>
    </ul>
    -->
    <h2>Hot artists</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistLoader" />
    <table id="topArtist" class="barTable"><!-- Content is loaded with AJAX --></table>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h1>Statster recommends</h1>
    <h2>Popular albums</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recommentedTopAlbumLoader" />
    <table id="recommentedTopAlbum" class="albumTable"><!-- Content is loaded with AJAX --></table>
    <h2>Recently released</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recommentedNewAlbumLoader" />
    <table id="recommentedNewAlbum" class="albumTable"><!-- Content is loaded with AJAX --></table>
  </div>
  <div class="container"><hr /></div>
  <!--<div class="container">
    <h1>Latest blog posts</h1>
  </div>
  <div class="container"><hr /></div>-->
  <div class="container">
    <h1>Browse Statster</h1>
    <ul class="noBullets">
      <li>&raquo; <?=anchor(array('music'), 'Browse music', array('title' => 'Browse music'))?></li>
      <li>&raquo; <?=anchor(array('tag'), 'Browse tags', array('title' => 'Browse tags'))?></li>
      <!--<li>&raquo; <?=anchor(array('user'), 'Browse users', array('title' => 'Browse users'))?></li>-->
    </ul>
  </div>
</div>