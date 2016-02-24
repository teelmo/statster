<div id="headingCont" class="artist_heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
  <h1>
    <div><span class="stats">stats</span><span class="ter">ter</span><span class="separator"></span><span class="meta">reconcile with music</span></div>
    <div class="top_music">
      <div><?=anchor(array('music', url_title($top_artist['artist_name'])), $top_artist['artist_name'], array('title' => $top_artist['count'] . ' listenings'))?></div>
    </div>
  </h1>
</div>
<div class="clear"></div>
<div id="mainCont" class="heading_container">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>History</h2>
      <div class="float_right settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%w')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%d')">Day</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%m')">Month</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('%Y')">Year</a> | <a href="javascript:;" onclick="view.getListeningHistory('%Y%m')" class="unactive">Montly</a>
      </div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="historyLoader"/>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="bar_chart"></div>
    </div>
    <div class="container"><hr /></div>
    <div id="leftContInner">
      <div class="container">
        <h2>Browse</h2>
        <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="popularGenreLoader" />
        <table id="popularGenre" class="genre_table"><!-- Content is loaded with AJAX --></table>
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
      <h1>Top in <?=date('F', strtotime('first day of last month'))?></h1>
      <table class="side_table">
        <tr>
          <td class="img64 album_img">
            <?=anchor(array('music', url_title($top_album['artist_name']), url_title($top_album['album_name'])), '<div class="cover album_img img64" style="background-image:url(' . getAlbumImg(array('album_id' => $top_album['album_id'], 'size' => 64)) . ')"></div>', array('title' => 'Browse to album\'s page'))?>
          </td>
          <td class="title">
            <?=anchor(array('music', url_title($top_album['artist_name']), url_title($top_album['album_name'])), $top_album['album_name'], array('title' => $top_album['count'] . ' listenings'))?> <?=anchor(array('year', url_title($top_album['year'])), '<span class="album_year number">' . $top_album['year'] . '</span>', array('title' => 'Browse release year'))?>
            <div class="count"><span class="number"><?=$top_album['count']?></span> listenings</div>
          </td>
        </tr>
        <tr>
          <td class="img64 artist_img">
            <?=anchor(array('music', url_title($top_artist['artist_name'])), '<div class="cover artist_img img64" style="background-image:url(' . getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 64)) . ')"></div>', array('title' => 'Browse to artist\'s page'))?>
          </td>
          <td class="title">
            <?=anchor(array('music', url_title($top_artist['artist_name'])), $top_artist['artist_name'], array('title' => $top_artist['count'] . ' listenings'))?>
            <div class="count"><span class="number"><?=$top_artist['count']?></span> listenings</div>
          </td>
        </tr>
        <tr>
          <td class="img64 tag_img"></td>
          <td class="title">
            <?=anchor(array('genre', url_title($top_genre['name'])), $top_genre['name'])?>
            <div class="count"><span class="number"><?=$top_genre['count']?></span> listenings</div>
          </td>
        </tr>
        <tr>
          <td class="img64 tag_img"></td>
          <td class="title">
            <?=anchor(array('nationality', url_title($top_nationality['name'])), $top_nationality['name'])?>
            <div class="count"><span class="number"><?=$top_nationality['count']?></span> listenings</div>
          </td>
        </tr>
        <tr>
          <td class="img64 tag_img"></td>
          <td class="title">
            <?=anchor(array('year', url_title($top_year['year'])), $top_year['year'])?>
            <div class="count"><span class="number"><?=$top_year['count']?></span> listenings</div>
          </td>
        </tr>
      </table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h1>Likes</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyLikedLoader" />
      <table id="recentlyLiked" class="side_table"><!-- Content is loaded with AJAX --></table>
      <table id="recentlyFaned" class="recently_liked hidden"><!-- Content is loaded with AJAX --></table>
      <table id="recentlyLoved" class="recently_liked hidden"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('like'), 'More', array('title' => 'Browse more'))?>
      </div>
    </div>
  </div>