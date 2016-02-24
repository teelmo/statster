<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('genre'), 'Genres')?>
    <?=anchor(array('keyword'), 'Keywords')?>
    <?=anchor(array('nationality'), 'Nationalities')?>
    <?=anchor(array('year'), 'Years')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <h1>Top nationalities</h1>
    </div>
    <div class="container">
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="top<?=ucfirst($tag_type)?>Loader" />
      <table id="top<?=ucfirst($tag_type)?>" class="column_table full"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Yearly</h1>
    </div>
    <div id="years"><!-- Content is loaded with AJAX --></div>
  </div>