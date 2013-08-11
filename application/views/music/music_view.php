<div id="leftCont">
  <div id="leftContInner">
    <div class="container">
      <h2>Find music</h2>
      <ul class="noBullets">
        <li>» <?=anchor(array('artist'), 'Browse artists', array('title' => 'Browse artists'))?></li>
        <li>» <?=anchor(array('album'), 'Browse albums', array('title' => 'Browse albums'))?></li>
      </ul>
    </div>
    <div class="container">
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="popularGenreLoader" />
      <table id="popularGenre" class="genreTable"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div id="leftContOuter">
    <div class="container" style="margin-right: 0px;">
      <h1>Popular albums</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="popularAlbumLoader" />
      <table id="popularAlbum" class="sideTable sideTableLeftCont"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
  </div>
</div>

<div id="rightCont">
  <div class="container">
    <h1>Statistics</h1>
    <h2>Latest likes</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyLikedLoader" />
    <table id="recentlyLiked" class="sideTable"><!-- Content is loaded with AJAX --></table>
    <table id="recentlyFaned" class="recentlyLiked hidden"><!-- Content is loaded with AJAX --></table>
    <table id="recentlyLoved" class="recentlyLiked hidden"><!-- Content is loaded with AJAX --></table>
  </div>
</div>