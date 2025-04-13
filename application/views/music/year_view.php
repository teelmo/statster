<div class="heading_container">
  <div class="heading_cont tag_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>')">
    <div class="info">
      <div class="top_info year_info">
        <h2><?=anchor(array('music'), 'Music')?></h2>
        <h1><?=$year?></h1>
      </div>
    </div>
  </div>
  <div class="meta_container">
    <div class="meta">
      <div class="label">Listenings, age</div>
      <div class="value number"><?=anchor(array('recent'), number_format($listening_count))?> <?=($album_average_age) ? '<span class="share" title="' . number_format($album_average_age['average_year'], 0, '.', '') . '">' . anchor(array('year'), number_format($album_average_age['count'], 1) . 'y') . '</span>' : '–'?></div>
    </div>
    <div class="meta">
      <div class="label">Albums, new</div>
       <div class="value number"><?=anchor(array('artist'), number_format($album_count))?> <?=($new_album_count) ? '<span class="share" title="' . $new_album_count . '">' . number_format($new_album_count / $album_count * 100) . '%</span>' : '–'?></div>
    </div>
    <div class="meta">
      <div class="label">Artists, new</div>
      <div class="value number"><?=anchor(array('artist'), number_format($artist_count))?> <?=($new_artist_count) ? '<span class="share" title="' . $new_artist_count . '">' . number_format($new_artist_count / $artist_count * 100) . '%</span>' : '–'?></div>
    </div>
    <div class="meta">
      <div class="label">Shouts</div>
      <div class="value number"><?=anchor(array('shout'), number_format($shout_count))?></div>
    </div>
    <div class="meta">
      <div class="label">Loved</div>
      <div class="value number"><?=anchor(array('love'), number_format($love_count))?></div>
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
  </div>
  <div class="left_container">
    <div class="container">
      <h3>History</h3>
      <div class="float_right settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%w', '<?=$lower_limit?>', '<?=$upper_limit?>')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%d', '<?=$lower_limit?>', '<?=$upper_limit?>')">Day</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('%m', '<?=$lower_limit?>', '<?=$upper_limit?>')">Month</a>
      </div>
      <div class="lds-facebook" id="historyLoader"><div></div><div></div><div></div></div>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="music_bar"></div>
    </div>
    <div class="container">
      <h3>Top albums</h3>
      <div class="lds-facebook" id="topAlbumLoader"><div></div><div></div><div></div></div>
      <ul id="topAlbum" class="music_wall"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <div class="more">
        <?=anchor(array('album', url_title($year)), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container">
      <h3>Top artists</h3>
      <div class="lds-facebook" id="topArtistLoader"><div></div><div></div><div></div></div>
      <ul id="topArtist" class="music_wall"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <div class="more">
        <?=anchor(array('artist', url_title($year)), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h2>Statistics</h2>
    </div>
    <div class="container">
      <h3>Top listeners</h3>
      <div class="lds-facebook" id="topListenerLoader"><div></div><div></div><div></div></div>
      <table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container">
      <h3>Top releases</h3>
      <div class="lds-facebook" id="topReleasesLoader"><div></div><div></div><div></div></div>
      <table id="topReleases" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container">
      <h3>Top formats</h3>
      <div class="lds-facebook" id="topListeningFormatTypesLoader"><div></div><div></div><div></div></div>
      <table id="topListeningFormatTypes" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <?php
    if ($year > 2007) {
      ?>
      <div class="container">
        <h3>Monthly</h3>
        <table class="month_table">
          <?php
          for ($month = 1; $month <= 12; $month++) {
            ;
            if ($year != CUR_YEAR || $month <= (int) CUR_MONTH) {
              ?>
              <tr><td><span class="r"><?=anchor(array('music', url_title($year), url_title(str_pad($month, 2, '0', STR_PAD_LEFT))), DateTime::createFromFormat('!m', str_pad($month, 2, '0', STR_PAD_LEFT))->format('F'), array('title' => DateTime::createFromFormat('!m', str_pad($month, 2, '0', STR_PAD_LEFT))->format('F') . ' ' . $year . ' overview'))?></span></td></tr>
              <?php
            }
          }
          ?>
        </table>
      </div>
      <?php
    }
    ?>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Top tags</h2>
      <h3>Genres</h3>
      <div class="lds-facebook" id="topGenreLoader"><div></div><div></div><div></div></div>
      <table id="topGenre" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container">
      <h3>Keywords</h3>
      <div class="lds-facebook" id="topKeywordLoader"><div></div><div></div><div></div></div>
      <table id="topKeyword" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container">
      <h3>Nationalities</h3>
      <div class="lds-facebook" id="topNationalityLoader"><div></div><div></div><div></div></div>
      <table id="topNationality" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container">
      <h3>Years</h3>
      <div class="lds-facebook" id="topYearLoader"><div></div><div></div><div></div></div>
      <table id="topYear" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>