<div id="headingCont" class="artist_heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
  <h1>
    <div><span class="stats">stats</span><span class="ter">ter</span><span class="separator"></span><span class="meta">reconcile with music</span></div>
    <div class="top_music">
      <div class="date">#1 in <?=date('F', strtotime('first day of last month'))?></div>
      <div><span class="info">artist</span> <?=anchor(array('music', url_title($top_album['artist_name'])), $top_album['artist_name'])?></div>
      <div><span class="info">album</span> <?=anchor(array('music', url_title($top_album['artist_name']), url_title($top_album['album_name'])), $top_album['album_name'])?></div>
    </div>
  </h1>
</div>
<div class="clear"></div>
<div id="mainCont" class="heading_container">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>History</h2>
      <div class="float_right settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%w')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%d')">Day</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%m')">Month</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('%Y')">Year</a> | <a href="javascript:;" onclick="view.getListeningHistory('%Y%m')" class="unactive">Montly</a>
      </div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="historyLoader"/>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="bar_chart"></div>
    </div>
    <div class="container"><hr /></div>
    <div id="leftContInner">
      <div class="container">
        <h2>Browse</h2>
        <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="popularGenreLoader" />
        <table id="popularGenre" class="genre_table"><!-- Content is loaded with AJAX --></table>
      </div>
    </div>
    <div id="leftContOuter">
      <div class="container">
        <h2>Popular albums</h2>
        <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="popularAlbumLoader" />
        <table id="popularAlbum" class="side_table"><!-- Content is loaded with AJAX --></table>
      </div>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Likes</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyLikedLoader" />
      <table id="recentlyLiked" class="side_table"><!-- Content is loaded with AJAX --></table>
      <table id="recentlyFaned" class="recentlyLiked hidden"><!-- Content is loaded with AJAX --></table>
      <table id="recentlyLoved" class="recentlyLiked hidden"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>