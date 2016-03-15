<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <h1>Tags</h1>
    </div>
    <div class="container">
      CI_VERSION: <?=CI_VERSION?>
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