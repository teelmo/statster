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
          <td class="img64 artist_img"><?=anchor(array('music', url_title($top_artist['artist_name'])), '<div class="cover artist_img img64" style="background-image:url(' . getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 64)) . ')"></div>', array('title' => 'Browse to artist\'s page'))?></td>
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
          <td class="img64 tag_img"><i class="mask-icon mask-icon-music" aria-hidden="true"></i></td>
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
          <td class="img64 tag_img"><i class="mask-icon mask-icon-flag" aria-hidden="true"></i></td>
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
          <td class="img64 tag_img"><i class="mask-icon mask-icon-hashtag" aria-hidden="true"></i></td>
          <td class="title">
            <?=anchor(array('year', url_title($top_year['year'])), $top_year['year'])?>
            <div class="count"><span class="number"><?=$top_year['count']?></span> listenings</div>
          </td>
        </tr>
        <?php
      }
      if (!$top_album && !$top_artist['artist_id'] !== 0 && !$top_genre && !$top_nationality && !$top_year) {
        echo ERR_NO_RESULTS;
      }
      ?>
    </table>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h3>Shouts</h3>
    <div class="lds-facebook" id="shoutLoader"><div></div><div></div><div></div></div>
    <table id="shout" class="shout_table"><!-- Content is loaded with AJAX --></table>
    <table id="albumShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
    <table id="artistShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
    <div class="more">
      <?=anchor('shout?u=' . $username, 'More', array('title' => 'Browse more shouts'))?>
    </div>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h3>Likes</h3>
    <div class="lds-facebook" id="recentlyLikedLoader"><div></div><div></div><div></div></div>
    <table id="recentlyLiked" class="side_table"><!-- Content is loaded with AJAX --></table>
    <table id="recentlyFaned" class="likes hidden"><!-- Content is loaded with AJAX --></table>
    <table id="recentlyLoved" class="likes hidden"><!-- Content is loaded with AJAX --></table>
    <div class="more">
      <?=anchor('like?u=' . $username, 'More', array('title' => 'Browse more likes'))?>
    </div>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h3>Listening formats
      <span class="lds-ring hidden" id="topFormatLoader2"><div></div><div></div><div></div><div></div></span>
      <div class="func_container">
        <div class="value top_format_value" data-value="<?=$top_listening_format_profile?>"><?=INTERVAL_TEXTS[$top_listening_format_profile]?></div>
        <ul class="subnav hidden" data-name="top_listening_format_profile" data-callback="getTopFormats" data-loader="topFormatLoader2">
          <li data-value="7">Last 7 days</li>
          <li data-value="30">Last 30 days</li>
          <li data-value="90">Last 90 days</li>
          <li data-value="180">Last 180 days</li>
          <li data-value="365">Last 365 days</li>
          <li data-value="overall">All time</li>
        </ul>
      </div>
    </h3>
    <div class="lds-facebook" id="topFormatLoader"><div></div><div></div><div></div></div>
    <table id="topFormat" class="column_table"><!-- Content is loaded with AJAX --></table>
    <div class="more">
      <?=anchor('format?u=' . $username, 'More', array('title' => 'Browse more formats'))?>
    </div>
  </div>
  <div class="container">
    <h3>Genres
      <span class="lds-ring hidden" id="topGenreLoader2"><div></div><div></div><div></div><div></div></span>
      <div class="func_container">
        <div class="value top_genre_value" data-value="<?=$top_genre_profile?>"><?=INTERVAL_TEXTS[$top_genre_profile]?></div>
        <ul class="subnav hidden" data-name="top_genre_profile" data-callback="getTopGenres" data-loader="topGenreLoader2">
          <li data-value="7">Last 7 days</li>
          <li data-value="30">Last 30 days</li>
          <li data-value="90">Last 90 days</li>
          <li data-value="180">Last 180 days</li>
          <li data-value="365">Last 365 days</li>
          <li data-value="overall">All time</li>
        </ul>
      </div>
    </h3>
    <div class="lds-facebook" id="topGenreLoader"><div></div><div></div><div></div></div>
    <table id="topGenre" class="column_table"><!-- Content is loaded with AJAX --></table>
    <div class="more">
      <?=anchor('genre?u=' . $username, 'More', array('title' => 'Browse more listenings'))?>
    </div>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h3>Keywords
      <span class="lds-ring hidden" id="topKeywordLoader2"><div></div><div></div><div></div><div></div></span>
      <div class="func_container">
        <div class="value top_keyword_value" data-value="<?=$top_keyword_profile?>"><?=INTERVAL_TEXTS[$top_keyword_profile]?></div>
        <ul class="subnav hidden" data-name="top_keyword_profile" data-callback="getTopKeywords" data-loader="topKeywordLoader2">
          <li data-value="7">Last 7 days</li>
          <li data-value="30">Last 30 days</li>
          <li data-value="90">Last 90 days</li>
          <li data-value="180">Last 180 days</li>
          <li data-value="365">Last 365 days</li>
          <li data-value="overall">All time</li>
        </ul>
      </div>
    </h3>
    <div class="lds-facebook" id="topKeywordLoader"><div></div><div></div><div></div></div>
    <table id="topKeyword" class="column_table"><!-- Content is loaded with AJAX --></table>
    <div class="more">
      <?=anchor('keyword?u=' . $username, 'More', array('title' => 'Browse more listenings'))?>
    </div>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h3>Nationalities
      <span class="lds-ring hidden" id="topNationalityLoader2"><div></div><div></div><div></div><div></div></span>
      <div class="func_container">
        <div class="value top_nationality_value" data-value="<?=$top_nationality_profile?>"><?=INTERVAL_TEXTS[$top_nationality_profile]?></div>
        <ul class="subnav hidden" data-name="top_nationality_profile" data-callback="getTopNationalities" data-loader="topNationalityLoader2">
          <li data-value="7">Last 7 days</li>
          <li data-value="30">Last 30 days</li>
          <li data-value="90">Last 90 days</li>
          <li data-value="180">Last 180 days</li>
          <li data-value="365">Last 365 days</li>
          <li data-value="overall">All time</li>
        </ul>
      </div>
    </h3>
    <div class="lds-facebook" id="topNationalityLoader"><div></div><div></div><div></div></div>
    <table id="topNationality" class="column_table"><!-- Content is loaded with AJAX --></table>
    <div class="more">
      <?=anchor('nationality?u=' . $username, 'More', array('title' => 'Browse more listenings'))?>
    </div>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h3>Years
      <span class="lds-ring hidden" id="topYearLoader2"><div></div><div></div><div></div><div></div></span>
      <div class="func_container">
        <div class="value top_year_value" data-value="<?=$top_year_profile?>"><?=INTERVAL_TEXTS[$top_year_profile]?></div>
        <ul class="subnav hidden" data-name="top_year_profile" data-callback="getTopYears" data-loader="topYearLoader2">
          <li data-value="7">Last 7 days</li>
          <li data-value="30">Last 30 days</li>
          <li data-value="90">Last 90 days</li>
          <li data-value="180">Last 180 days</li>
          <li data-value="365">Last 365 days</li>
          <li data-value="overall">All time</li>
        </ul>
      </div>
    </h3>
    <div class="lds-facebook" id="topYearLoader"><div></div><div></div><div></div></div>
    <table id="topYear" class="column_table"><!-- Content is loaded with AJAX --></table>
    <div class="more">
      <?=anchor('year?u=' . $username, 'More', array('title' => 'Browse more listenings'))?>
    </div>
  </div>
</div>
