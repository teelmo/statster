<div id="headingCont">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><span class="stats">stats</span><span class="ter">ter</span><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
</div>
<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('format'), 'Formats')?>
    <?=anchor(array('listener'), 'Listeners')?>
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('shout'), 'Shouts')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div id="leftCont">
    <div class="container clearfix">
      <h1><?=anchor(array('genre', url_title($genre['name'])), $genre['name'])?></h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumGenreLoader" />
      <ul id="topAlbumGenre" class="music_wall"><!-- Content is loaded with AJAX --></ul>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistGenreLoader" />
      <ul id="topArtistGenre" class="music_wall"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container clearfix">
      <h1><?=anchor(array('keyword', url_title($genre['name'])), $keyword['name'])?></h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumKeywordLoader" />
      <ul id="topAlbumKeyword" class="music_wall"><!-- Content is loaded with AJAX --></ul>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistKeywordLoader" />
      <ul id="topArtistKeyword" class="music_wall"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container clearfix">
      <h1><?=anchor(array('nationality', url_title($genre['name'])), $nationality['name'])?></h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumNationalityLoader" />
      <ul id="topAlbumNationality" class="music_wall"><!-- Content is loaded with AJAX --></ul>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistNationalityLoader" />
      <ul id="topArtistNationality" class="music_wall"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container clearfix">
      <h1><?=anchor(array('year', url_title($genre['name'])), $year['name'])?></h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumYearLoader" />
      <ul id="topAlbumYear" class="music_wall"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Hot</h1>
      <h2>Genres</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topGenreLoader" />
      <table id="topGenre" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('genre'), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Keywords</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topKeywordLoader" />
      <table id="topKeyword" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('keyword'), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Nationalities</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topNationalityLoader" />
      <table id="topNationality" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('nationality'), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Years</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topYearLoader" />
      <table id="topYear" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('year'), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
  </div>