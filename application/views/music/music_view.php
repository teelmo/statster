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
      <table id="popularAlbum" class="albumTable albumTableLeftCont"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
  </div>
</div>

<div id="rightCont">
  <div class="container">
    <h1>Recently favorited</h1>

  </div>
</div>