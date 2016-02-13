<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('genre'), 'Genres')?>
    <?=anchor(array('keyword'), 'Keywords')?>
    <?=anchor(array('nationality'), 'Nationalities')?>
    <?=anchor(array('year'), 'Release years')?>
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
      <h1>Statistics</h1>
      <h2>Genres</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topGenreLoader" />
      <table id="topGenre" class="column_table full"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('genre'), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Keywords</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topKeywordLoader" />
      <table id="topKeyword" class="column_table full"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('keyword'), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Nationalities</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topNationalityLoader" />
      <table id="topNationality" class="column_table full"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('nationality'), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Release Years</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topYearLoader" />
      <table id="topYear" class="column_table full"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('year'), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
  </div>