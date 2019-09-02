<div class="heading_container">
  <div class="heading_cont tag_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>')">
    <div class="info">
      <div class="top_info month_info">
        <h2><?=anchor(array('music', url_title($year)), $year)?></h2>
        <h1><?=DateTime::createFromFormat('!m', $month)->format('F')?></h1>
      </div>
    </div>
  </div>
  <div class="month_meta">
    <div class="meta">
      <div class="label">Listenings</div>
      <div class="value number"><?=anchor(array('recent'), number_format($listening_count))?></div>
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
  </div>
  <div class="left_container">
    <div class="container">
      <h2>History</h2>
      <div class="float_right settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%w', '<?=$lower_limit?>', '<?=$upper_limit?>')">Weekday</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('%d', '<?=$lower_limit?>', '<?=$upper_limit?>')">Day</a>
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
      <div class="more">
        <?=anchor(array('album', url_title($year), url_title($month)), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container">
      <h2>Top artists</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistLoader" />
      <ul id="topArtist" class="music_wall"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <div class="more">
        <?=anchor(array('artist', url_title($year), url_title($month)), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
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
    <?php
    if ($year > 2007) {
      ?>
      <div class="container">
        <h2>Monthly</h2>
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