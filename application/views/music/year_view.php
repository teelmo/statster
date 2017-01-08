<div id="headingCont" class="artist_heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
  <h1>
    <div><span class="stats">stats</span><span class="ter">ter</span><span class="separator"></span><span class="meta">reconcile with music</span></div>
    <div class="top_music">
      <div><?=anchor(array('music', url_title($top_artist['artist_name'])), $top_artist['artist_name'], array('title' => $top_artist['count'] . ' listenings'))?></div>
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
    <h1><?=$year?> in music</h1>
    <div class="container">
      <h2>History</h2>
      <div class="float_right settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%w', '<?=$lower_limit?>', '<?=$upper_limit?>')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%d', '<?=$lower_limit?>', '<?=$upper_limit?>')">Day</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('%m', '<?=$lower_limit?>', '<?=$upper_limit?>')">Month</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%Y%m', '<?=$lower_limit?>', '<?=$upper_limit?>')">Montly</a>
      </div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="historyLoader"/>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="music_bar"></div>
    </div>
    <div class="container">
      <h2>Top albums</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumLoader" />
      <ul id="topAlbum" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Top artists</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistLoader" />
      <ul id="topArtist" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Top listeners</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListenerLoader"/>
      <table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container">
      <h2>Top releases</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topReleasesLoader" />
      <table id="topReleases" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h1>Top tags</h1>
      <h2>Genres</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topGenreLoader" />
      <table id="topGenre" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Keywords</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topKeywordLoader" />
      <table id="topKeyword" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Nationalities</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topNationalityLoader" />
      <table id="topNationality" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Years</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topYearLoader" />
      <table id="topYear" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container">
      <h2>Top day</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="" />
      <ul id="" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container">
      <h2>Loved</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="" />
      <ul id="" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container">
      <h2>Faned</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="" />
      <ul id="" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container">
      <h2>Comments</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="" />
      <ul id="" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container">
      <h2>New album count</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="" />
      <ul id="" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container">
      <h2>New artist count</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="" />
      <ul id="" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>