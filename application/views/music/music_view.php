<div class="heading_container">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><a href="/" class="statster"><span class="stats">stats</span><span class="ter">ter</span></a><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
  <div class="meta_container">
    <div class="meta">
      <div class="label">Listenings, age</div>
      <div class="value number"><?=anchor(array('recent'), number_format($listening_count))?> <?=($album_average_age) ? '<span class="share" title="' . number_format($album_average_age['average_year'], 0, '.', '') . '">' . anchor(array('year'), number_format($album_average_age['count'], 1) . 'y') . '</span>' : '–'?></div>
    </div>
    <div class="meta">
      <div class="label">Albums</div>
       <div class="value number"><?=anchor(array('album'), number_format($album_count))?></div>
    </div>
    <div class="meta">
      <div class="label">Artists</div>
      <div class="value number"><?=anchor(array('artist'), number_format($artist_count))?></div>
    </div>
    <div class="meta">
      <div class="label">Shouts</div>
      <div class="value number"><?=anchor(array('shout'), number_format($shout_count))?></div>
    </div>
    <div class="meta">
      <div class="label">Loved</div>
      <div class="value number"><?=anchor(array('shout'), number_format($love_count))?></div>
    </div>
    <div class="meta">
      <div class="label">Faned</div>
      <div class="value number"><?=anchor(array('fan'), number_format($fan_count))?></div>
    </div>
  </div>
</div>
<div class="main_container">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('format'), 'Formats')?>
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('listener'), 'Listeners')?>
    <?=anchor(array('shout'), 'Shouts')?>
    <?=anchor(array('tag'), 'Tags')?>
    <div class="music_date_selector">
      <span class="month_selector_container"><select class="month_selector"><option value="">Month</option><option value="" disabled="disabled">-----</option></select></span>
      <span class="day_selector_container"><select class="day_selector"><option value="">Day</option><option value="" disabled="disabled">-----</option></select></span>
      <span class="weekday_selector_container"><select class="weekday_selector"><option value="">Weekday</option><option value="" disabled="disabled">-----</option></select></span>
      <span class="date_range_container"><span class="date_range_picker">All time</span></span>
      <a class="date_filter_submit" href="javascript:;"><span class="fa fa-calendar-alt"></span></a>
      <span class="date_filter_clear hidden"><span class="fa fa-times"></span></span>
      <div class="calendar_container"></div>
    </div>
  </div>
  <div class="left_container">
    <div class="container">
      <h2>Statistics</h2>
      <h3>History <span class="line"></span></h3>
      <div class="settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%w')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%d')">Day</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%m')">Month</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('%Y')">Year</a> | <a href="javascript:;" onclick="view.getListeningHistory('%Y%m')" class="unactive">Montly</a>
      </div>
      <div class="lds-facebook" id="historyLoader"><div></div><div></div><div></div></div>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="music_bar"></div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <div class="left_container_inner">
        <div class="container">
          <h3>Overview</h3>
          <table class="year_table">
            <?php
            for ($year = CUR_YEAR; $year >= 2004; $year--) {
              ?>
              <tr><td><span class="number"><?=anchor(array('music', url_title($year)), $year, array('title' => $year . ' overview'))?></span></td></tr>
              <?php
            }
            ?>
          </table>
        </div>
        <div class="container">
          <h3>Browse</h3>
          <div class="lds-facebook" id="popularGenreLoader"><div></div><div></div><div></div></div>
          <table id="popularGenre" class="genre_table"><!-- Content is loaded with AJAX --></table>
        </div>
      </div>
      <div class="left_container_outer">
        <div class="container">
          <h3>Popular albums
            <span class="lds-ring hidden" id="popularAlbumLoader2"><div></div><div></div><div></div><div></div></span>
            <div class="func_container">
              <div class="value"><?=INTERVAL_TEXTS[$popular_album_music]?></div>
              <ul class="subnav" data-name="popular_album_music" data-callback="getPopularAlbums" data-loader="popularAlbumLoader2">
                <li data-value="7">Last 7 days</li>
                <li data-value="30">Last 30 days</li>
                <li data-value="90">Last 90 days</li>
                <li data-value="180">Last 180 days</li>
                <li data-value="365">Last 365 days</li>
                <li data-value="overall">All time</li>
              </ul>
            </div>
          </h3>
          <div class="lds-facebook" id="popularAlbumLoader"><div></div><div></div><div></div></div>
          <table id="popularAlbum" class="side_table"><!-- Content is loaded with AJAX --></table>
        </div>
      </div>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h2>Top in <?=date('F', strtotime('first day of last month'))?></h2>
      <table class="side_table">
        <?php
        if ($top_album) {
          ?>
          <tr>
            <td class="img64 album_img">
              <?=anchor(array('music', url_title($top_album['artist_name']), url_title($top_album['album_name'])), '<div class="cover album_img img64" style="background-image:url(' . getAlbumImg(array('album_id' => $top_album['album_id'], 'size' => 64)) . ')"></div>', array('title' => 'Browse to album\'s page'))?>
            </td>
            <td class="title">
              <?=anchor(array('music', url_title($top_album['artist_name']), url_title($top_album['album_name'])), substrwords($top_album['album_name'], 80), array('title' => $top_album['count'] . ' listenings'))?> <?=anchor(array('year', url_title($top_album['year'])), '<span class="album_year number">' . $top_album['year'] . '</span>', array('title' => 'Browse release year'))?>
              <div class="count"><span class="number"><?=$top_album['count']?></span> listenings</div>
            </td>
          </tr>
          <?php
        }
        if ($top_artist['artist_id'] > 0) {
          ?>
          <tr>
            <td class="img64 artist_img">
              <?=anchor(array('music', url_title($top_artist['artist_name'])), '<div class="cover artist_img img64" style="background-image:url(' . getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 64)) . ')"></div>', array('title' => 'Browse to artist\'s page'))?>
            </td>
            <td class="title">
              <?=anchor(array('music', url_title($top_artist['artist_name'])), substrwords($top_artist['artist_name'], 80), array('title' => $top_artist['count'] . ' listenings'))?>
              <div class="count"><span class="number"><?=$top_artist['count']?></span> listenings</div>
            </td>
          </tr>
          <?php
        }
        if ($top_genre) {
          ?>
          <tr>
            <td class="img64 tag_img"><i class="fa fa-music"></i></td>
            <td class="title">
              <?=anchor(array('genre', url_title($top_genre['name'])), $top_genre['name'])?>
              <div class="count"><span class="number"><?=$top_genre['count']?></span> listenings</div>
            </td>
          </tr>
          <?php
        }
        if ($top_nationality) {
          ?>
          <tr>
            <td class="img64 tag_img"><i class="fa fa-flag"></i></td>
            <td class="title">
              <?=anchor(array('nationality', url_title($top_nationality['name'])), $top_nationality['name'])?>
              <div class="count"><span class="number"><?=$top_nationality['count']?></span> listenings</div>
            </td>
          </tr>
          <?php
        }
        if ($top_year) {
          ?>
          <tr>
            <td class="img64 tag_img"><i class="fa fa-hashtag"></i></td>
            <td class="title">
              <?=anchor(array('year', url_title($top_year['year'])), $top_year['year'])?>
              <div class="count"><span class="number"><?=$top_year['count']?></span> listenings</div>
            </td>
          </tr>
          <?php
        }
        if (!$top_album && !$top_artist['artist_id'] !== 0 && !$top_genre && !$top_nationality && !$top_year) {
          ?>
          <tr><td><?=ERR_NO_RESULTS?></td></tr>
          <?php
        }
        ?>
      </table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Suggestions</h2>
      <h3>Second chance <span class="lds-ring hidden" id="secondChanceLoader2"><div></div><div></div><div></div><div></div></span><span class="func_container"><i class="fa fa-sync-alt" id="refreshSecondChanceAlbums"></i></span></h3>
      <div class="lds-facebook" id="secondChanceLoader"><div></div><div></div><div></div></div>
      <table class="side_table" id="secondChance"><!-- Content is loaded with AJAX --></table>
      <h3>From others <span class="lds-ring hidden" id="fromOthersLoader2"><div></div><div></div><div></div><div></div></span><span class="func_container"><i class="fa fa-sync-alt" id="refreshFromOthersAlbums"></i></span></h3>
      <div class="lds-facebook" id="fromOthersLoader"><div></div><div></div><div></div></div>
      <table class="side_table" id="fromOthers"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Likes</h2>
      <div class="lds-facebook" id="recentlyLikedLoader"><div></div><div></div><div></div></div>
      <table id="recentlyLiked" class="side_table"><!-- Content is loaded with AJAX --></table>
      <table id="recentlyFaned" class="recently_liked hidden"><!-- Content is loaded with AJAX --></table>
      <table id="recentlyLoved" class="recently_liked hidden"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('like'), 'More', array('title' => 'Browse more'))?>
      </div>
    </div>
  </div>