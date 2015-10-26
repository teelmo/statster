<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>History</h2>
      <div class="float_right settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('weekday')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('day')">Day</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('month')">Month</a> | <a href="javascript:;" onclick="view.getListeningHistory('year')" class="unactive">Year</a>
      </div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="historyLoader"/>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="bar_chart"></div>
    </div>
    <div class="container"><hr /></div>
    <div id="leftContInner">
      <div class="container">
        <h2>Browse</h2>
        <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="popularGenreLoader" />
        <table id="popularGenre" class="genreTable"><!-- Content is loaded with AJAX --></table>
      </div>
    </div>
    <div id="leftContOuter">
      <div class="container">
        <h2>Popular albums</h2>
        <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="popularAlbumLoader" />
        <table id="popularAlbum" class="side_table"><!-- Content is loaded with AJAX --></table>
      </div>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Likes</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyLikedLoader" />
      <table id="recentlyLiked" class="side_table"><!-- Content is loaded with AJAX --></table>
      <table id="recentlyFaned" class="recentlyLiked hidden"><!-- Content is loaded with AJAX --></table>
      <table id="recentlyLoved" class="recentlyLiked hidden"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>