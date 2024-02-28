<div class="heading_container">
  <div class="heading_cont tag_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>')">
    <div class="info">
      <div class="top_info year_info">
        <h2><?=anchor(array('music'), 'Music')?></h2>
        <?php
        $title_elements = array();
        if (isset($from_array) && $from !== '1970-00-00' && isset($to_array) && $to !== CUR_DATE) {
          $title_elements[] = DateTime::createFromFormat('!m', $from_array[1])->format('F') . ' ' . DateTime::createFromFormat('!d', $from_array[2])->format('jS') . ' ' . $from_array[0] . ' to ' . DateTime::createFromFormat('!m', $to_array[1])->format('F') . ' ' . DateTime::createFromFormat('!d', $to_array[2])->format('jS') . ' ' . $to_array[0];
        }
        else if (isset($from_array) && $from !== '1970-00-00' && $to === CUR_DATE) {
          $title_elements[] = 'After ' . DateTime::createFromFormat('!m', $from_array[1])->format('F') . ' ' . DateTime::createFromFormat('!d', $from_array[2])->format('jS') . ' ' . $from_array[0];
        }
        else if (isset($to_array) && $to !== CUR_DATE && $from === '1970-00-00') {
          $title_elements[] = 'Before ' . DateTime::createFromFormat('!m', $to_array[1])->format('F') . ' ' . DateTime::createFromFormat('!d', $to_array[2])->format('jS') . ' ' . $to_array[0];
        }
        if ($month !== '\'%\'' && $day !== '\'%\'') {
          $title_elements[] = 'on ' . DateTime::createFromFormat('!m', $month)->format('F') . ' ' . DateTime::createFromFormat('!d', $day)->format('jS');
        }
        else if ($month !== '\'%\'') {
          $title_elements[] = 'in ' . DateTime::createFromFormat('!m', $month)->format('F');
        }
        else if ($day !== '\'%\'') {
          $title_elements[] = 'on ' . DateTime::createFromFormat('!d', $day)->format('jS');
        }
        if ($weekday !== '\'%\'') {
          $title_elements[] = 'on a ' . jddayofweek($weekday, 1);
        }
        ?>
        <h1><?=ucfirst(implode(', ', $title_elements))?></h1>
      </div>
    </div>
  </div>
  <div class="meta_container">
    <div class="meta">
      <div class="label">Listenings, age</div>
      <div class="value number"><?=anchor(array('recent'), number_format($listening_count))?> <?=($album_average_age) ? '<span class="share" title="' . number_format($album_average_age['average_year'], 0, '.', '') . '">' . number_format($album_average_age['count'], 1) . 'y</span>' : '–'?></div>
    </div>
    <div class="meta">
      <div class="label">Albums, new</div>
       <div class="value number"><?=anchor(array('artist'), number_format($album_count))?>, <?=($new_album_count) ? '<span class="share" title="' . $new_album_count . '">' . number_format($new_album_count / $album_count * 100) . '%</span>' : '–'?></div>
    </div>
    <div class="meta">
      <div class="label">Artists, new</div>
      <div class="value number"><?=anchor(array('artist'), number_format($artist_count))?>, <?=($new_artist_count) ? '<span class="share" title="' . $new_artist_count . '">' . number_format($new_artist_count / $artist_count * 100) . '%</span>' : '–'?></div>
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
    <div class="float_right music_date_selector">
      <span class="month_selector_container"><select class="month_selector"><option value="">Month</option><option value="" disabled="disabled">-----</option></select></span>
      <span class="day_selector_container"><select class="day_selector"><option value="">Day</option><option value="" disabled="disabled">-----</option></select></span>
      <span class="weekday_selector_container"><select class="weekday_selector"><option value="">Weekday</option><option value="" disabled="disabled">-----</option></select></span>
      <span class="date_range_container"><span class="date_range_picker"><?=($from !== '1970-00-00') ? $from . ' to ' . $to : 'All time'?></span></span>
      <a class="date_filter_submit" href="javascript:;"><span class="fa fa-calendar-alt"></span></a>
      <span class="date_filter_clear"><span class="fa fa-times"></span></span>
    </div>
  </div>
  <div class="left_container">
    <div class="container">
      <h2>History</h2>
      <div class="float_right settings">
        <a href="javascript:;" class="" onclick="view.getListeningHistory('%w')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%d')">Day</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%m')">Month</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%Y')">Year</a> | <a href="javascript:;" onclick="view.getListeningHistory('%Y%m')" class="unactive">Montly</a>
      </div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="historyLoader"/>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="music_bar"></div>
    </div>
    <div class="container">
      <h2>Top albums</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumLoader" />
      <ul id="topAlbum" class="music_wall"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Top artists</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistLoader" />
      <ul id="topArtist" class="music_wall"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container"><hr /></div>
  </div>
  <div class="right_container">
    <div class="container">
      <h1>Statistics</h1>
    </div>
    <div class="container">
      <h2>Top listeners</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListenerLoader"/>
      <table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container">
      <h2>Top releases</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topReleasesLoader" />
      <table id="topReleases" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container">
      <h2>Top formats</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListeningFormatTypesLoader"/>
      <table id="topListeningFormatTypes" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h1>Top tags</h1>
      <h2>Genres</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topGenreLoader" />
      <table id="topGenre" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container">
      <h2>Keywords</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topKeywordLoader" />
      <table id="topKeyword" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container">
      <h2>Nationalities</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topNationalityLoader" />
      <table id="topNationality" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container">
      <h2>Years</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topYearLoader" />
      <table id="topYear" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>