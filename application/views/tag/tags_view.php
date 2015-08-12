<div id="leftCont">
  <div class="container">
    <h1><?=$tag_type?></h1>
  </div>
  <div class="container">
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="popular<?=$tag_type?>Loader" />
    <table id="popular<?=$tag_type?>" class="genre<?=$tag_type?>"><!-- Content is loaded with AJAX --></table>
  </div>
</div>

<div id="rightCont">
  <div class="container">
    <h1>Statistics</h1>
    <h2>Genres</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topGenreLoader" />
    <table id="topGenre" class="barTable"><!-- Content is loaded with AJAX --></table>
    <h2>Keywords</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topKeywordLoader" />
    <table id="topKeyword" class="barTable"><!-- Content is loaded with AJAX --></table>
    <?php
    /*
    <h2>Nationalities</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topNatinalityLoader" />
    <table id="topNationality" class="barTable"><!-- Content is loaded with AJAX --></table>
    <h2>Release Years</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topReleaseYearLoader" />
    <table id="topReleaseYear" class="barTable"><!-- Content is loaded with AJAX --></table>
    */
    ?>
  </div>
</div>