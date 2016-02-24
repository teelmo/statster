<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('fan'), 'Fans')?>
    <?=anchor(array('love'), 'Loves')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <h1>Most liked</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topLovedLoader" />
      <div id="topLoved" class="music_wall"><!-- Content is loaded with AJAX --></div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topFanedLoader" />
      <div id="topFaned" class="music_wall"><!-- Content is loaded with AJAX --></div>
    </div>
  </div>

  <div id="rightCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Loved</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyLovedLoader" />
      <table id="recentlyLoved" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('love'), 'More', array('title' => 'Browse more'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Faned</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyFanedLoader" />
      <table id="recentlyFaned" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('fan'), 'More', array('title' => 'Browse more'))?>
      </div>
    </div>
  </div>