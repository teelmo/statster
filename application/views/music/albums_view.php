<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <h1><?=$title?></h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbum10Loader"/>
      <ul id="topAlbum10" class="chart_list chart_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumLoader"/>
      <table id="topAlbum" class="column_table full"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Yearly</h1>
    </div>
    <div id="years"><!-- Content is loaded with AJAX --></div>
  </div>