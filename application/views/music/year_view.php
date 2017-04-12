<div id="headingCont" class="artist_heading_cont tag_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>')">
  <div class="inner">
    <div class="info">
      <div class="top_info album_info">
        <h2>Year overview</h2>
        <h1><?=$year?></h1>
      </div>
      <table class="year_meta">
        <tr>
          <td class="label">Listenings</td>
          <td class="label">Albums (new)</td>
          <td class="label">Artists (new)</td>
          <td class="label">Shouts</td>
          <td class="label">Loved</td>
          <td class="label">Faned</td>
        </tr>
        <tr>
          <td class="value number"><?=anchor(array('recent'), number_format($listening_count))?></td>
          <td class="value number"><?=anchor(array('album'), number_format($album_count))?> (<?=$new_album_count?>)</td>
          <td class="value number"><?=anchor(array('artist'), number_format($artist_count))?> (<?=$new_artist_count?>)</td>
          <td class="value number"><?=anchor(array('shout'), number_format($shout_count))?></td>
          <td class="value number"><?=anchor(array('love'), number_format($love_count))?></td>
          <td class="value number"><?=anchor(array('fan'), number_format($fan_count))?></td>
        </tr>
      </table>
    </div>
  </div>
</div>
<div class="clear"></div>
<div class="clear"></div>
<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('shout'), 'Shouts')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <h2>History</h2>
      <div class="float_right settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%w', '<?=$lower_limit?>', '<?=$upper_limit?>')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%d', '<?=$lower_limit?>', '<?=$upper_limit?>')">Day</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('%m', '<?=$lower_limit?>', '<?=$upper_limit?>')">Month</a>
      </div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="historyLoader"/>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="music_bar"></div>
    </div>
    <div class="container">
      <h2>Top albums</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumLoader" />
      <div id="topAlbum" class="music_wall"><!-- Content is loaded with AJAX --></div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Top artists</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistLoader" />
      <div id="topArtist" class="music_wall"><!-- Content is loaded with AJAX --></div>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Statistics</h1>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Top listeners</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListenerLoader"/>
      <table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Top releases</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topReleasesLoader" />
      <table id="topReleases" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Monthly</h2>
      <table class="month_table">
        <?php
        for ($month = 1; $month <= 12; $month++) {
          ;
          if ($year != CUR_YEAR || $month <= (int) CUR_MONTH) {
            ?>
            <tr><td><span class="r"><?=anchor(array('music', url_title($year), url_title(str_pad($month, 2, '0', STR_PAD_LEFT))), DateTime::createFromFormat('!m', str_pad($month, 2, '0', STR_PAD_LEFT))->format('F'), array('title' => $month + ' overview'))?></span></td></tr>
            <?php
          }
        }
        ?>
      </table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h1>Top tags</h1>
      <h2>Genres</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topGenreLoader" />
      <table id="topGenre" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Keywords</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topKeywordLoader" />
      <table id="topKeyword" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Nationalities</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topNationalityLoader" />
      <table id="topNationality" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Years</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topYearLoader" />
      <table id="topYear" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>