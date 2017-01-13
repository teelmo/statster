<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('fan'), 'Fans')?>
    <?=anchor(array('love'), 'Loves')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <h1>Recently faned</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyFanedLoader" />
      <table id="recentlyFaned" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Top faned</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topFanedLoader"/>
      <table id="topFaned" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>