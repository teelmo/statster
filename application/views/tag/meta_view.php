<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('format'), 'Formats')?>
    <?=anchor(array('listener'), 'Listeners')?>
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('shout'), 'Shouts')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div id="leftCont">
    <div class="container clearfix">
      <h1><?=$genre['name']?></h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumGenreLoader" />
      <ul id="topAlbumGenre" class="music_wall"><!-- Content is loaded with AJAX --></ul>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistGenreLoader" />
      <ul id="topArtistGenre" class="music_wall"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container clearfix">
      <h1><?=$keyword['name']?></h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumKeywordLoader" />
      <ul id="topAlbumKeyword" class="music_wall"><!-- Content is loaded with AJAX --></ul>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistKeywordLoader" />
      <ul id="topArtistKeyword" class="music_wall"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container clearfix">
      <h1><?=$nationality['name']?></h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumNationalityLoader" />
      <ul id="topAlbumNationality" class="music_wall"><!-- Content is loaded with AJAX --></ul>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistNationalityLoader" />
      <ul id="topArtistNationality" class="music_wall"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container clearfix">
      <h1><?=$year['name']?></h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumYearLoader" />
      <ul id="topAlbumYear" class="music_wall"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Hot</h1>
      <h2>Genres</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topGenreLoader" />
      <table id="topGenre" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('genre'), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Keywords</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topKeywordLoader" />
      <table id="topKeyword" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('keyword'), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Nationalities</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topNationalityLoader" />
      <table id="topNationality" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('nationality'), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Years</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topYearLoader" />
      <table id="topYear" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('year'), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
  </div>