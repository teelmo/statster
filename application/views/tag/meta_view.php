<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('shout'), 'Shouts')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div id="leftCont">
    <div class="container clearfix">
      <h1><?=$genre['name']?></h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumGenreLoader" />
      <div id="topAlbumGenre" class="music_wall"><!-- Content is loaded with AJAX --></div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistGenreLoader" />
      <div id="topArtistGenre" class="music_wall"><!-- Content is loaded with AJAX --></div>
    </div>
    <div class="container clearfix">
      <h1><?=$keyword['name']?></h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumKeywordLoader" />
      <div id="topAlbumKeyword" class="music_wall"><!-- Content is loaded with AJAX --></div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistKeywordLoader" />
      <div id="topArtistKeyword" class="music_wall"><!-- Content is loaded with AJAX --></div>
    </div>
    <div class="container clearfix">
      <h1><?=$nationality['name']?></h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumNationalityLoader" />
      <div id="topAlbumNationality" class="music_wall"><!-- Content is loaded with AJAX --></div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistNationalityLoader" />
      <div id="topArtistNationality" class="music_wall"><!-- Content is loaded with AJAX --></div>
    </div>
    <div class="container clearfix">
      <h1><?=$year['name']?></h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumYearLoader" />
      <div id="topAlbumYear" class="music_wall"><!-- Content is loaded with AJAX --></div>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Popular</h1>
      <h2>Genres</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="popularGenreLoader" />
      <table id="popularGenre" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('genre'), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Keywords</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="popularKeywordLoader" />
      <table id="popularKeyword" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('keyword'), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Nationalities</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="popularNationalityLoader" />
      <table id="popularNationality" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('nationality'), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Years</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="popularYearLoader" />
      <table id="popularYear" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('year'), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
  </div>