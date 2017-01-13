<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('fan'), 'Fans')?>
    <?=anchor(array('love'), 'Loves')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <h1>Recently Loved</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyLovedLoader" />
      <table id="recentlyLoved" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Top Loved</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topLovedLoader"/>
      <table id="topLoved" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>