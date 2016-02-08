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
      <p>Top genres</p>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topGenreLoader" />
      <table id="topGenre" class="barTable"><!-- Content is loaded with AJAX --></table>
      <h2>Keywords</h2>
      <p>Top keywords</p>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topKeywordLoader" />
      <table id="topKeyword" class="barTable"><!-- Content is loaded with AJAX --></table>
      <h2>Nationalities</h2>
      <p>Top nationalities</p>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topNatinalityLoader" />
      <table id="topNationality" class="barTable"><!-- Content is loaded with AJAX --></table>
      <h2>Release Years</h2>
      <p>Top release years</p>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topReleaseYearLoader" />
      <table id="topReleaseYear" class="barTable"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>