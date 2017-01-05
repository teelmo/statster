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
      <h2>Listening week / month / weekday</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="" />
      <ul id="" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
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
      <h2>Top releases</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topReleasesLoader" />
      <table id="topReleases" class="column_table"><!-- Content is loaded with AJAX --></table>
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
      <h2>New artist count</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="" />
      <ul id="" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container">
      <h2>New album count</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="" />
      <ul id="" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container">
      <h2>Releases</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="" />
      <ul id="" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container">
      <h2>Top listeners</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="" />
      <ul id="" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container">
      <h2>Top genres</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topGenreLoader" />
      <ul id="topGenre" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Top nationalities</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topNationalityLoader" />
      <ul id="topNationality" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Top keywords</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topKeywordLoader" />
      <ul id="topKeyword" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Top years</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topYearLoader" />
      <ul id="topYear" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>