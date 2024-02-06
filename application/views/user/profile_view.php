<div class="heading_container">
  <?php
  if (isset($artist_id)) {
    ?>
    <div class="heading_cont profile_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>')" title="#1 artist: <?=$artist_name?>">
      <div class="info">
        <div class="float_left cover user_img img174" style="background-image:url('<?=getUserImg(array('user_id' => $user_id, 'size' => 174))?>')"></div>
        <div class="top_info user_info">
          <h1><?=$username?></h1>
          <h4><span class="username"><?=($real_name) ? htmlentities($real_name) : htmlentities($username) ?></span><span class="meta"> • <?=($joined_year) ? 'active since ' . $joined_year : 'active since long time ago'?></span></h4>
          <ul id="tags">
            <?php
            foreach ($tags as $tag) {
              ?>
              <li class="tag <?=$tag['type']?>"><?=anchor(array($tag['type'], url_title($tag['name']) . '?u=teelmo'), '<i class="fa fa-music"></i> ' . $tag['name'] . '</i>')?></li>
              <?php
            }
            ?>
          </ul>
        </div>
      </div>
    </div>
    <?php
  }
  ?>
  <div class="meta_container">
    <div class="meta">
      <div class="label">Listenings</div>
      <div class="value number"><?=anchor(array('recent?u=' . $username), number_format($listening_count))?></div>
    </div>
    <div class="meta">
      <div class="label">Albums</div>
      <div class="value number"><?=anchor(array('album?u=' . $username), number_format($album_count))?></div>
    </div>
    <div class="meta">
      <div class="label">Artists</div>
      <div class="value number"><?=anchor(array('artist?u=' . $username), number_format($artist_count))?></div>
    </div>
    <div class="meta">
      <div class="label">Shouts</div>
      <div class="value number"><?=anchor(array('shout?u=' . $username), number_format($shout_count))?></div>
    </div>
    <div class="meta">
      <div class="label">Loved</div>
      <div class="value number"><?=anchor(array('love?u=' . $username), number_format($love_count))?></div>
    </div>
    <div class="meta">
      <div class="label">Faned</div>
      <div class="value number"><?=anchor(array('fan?u=' . $username), number_format($fan_count))?></div>
    </div>
  </div>
</div>
<div class="main_container">
  <?php
  if ($logged_in === 'true' && $username !== $this->session->userdata('username')) {
    ?>
    <div class="similarity_info">
      <div class="simililarity_image" title="<?=$similarity['value']?>%">
        <svg viewBox="0 0 36 36">
          <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="rgba(182, 192, 191, 0.5)"; stroke-width="4"; stroke-dasharray="100, 100" />
          <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#666"; stroke-width="4"; stroke-dasharray="<?=$similarity['value']?>, 100" class="similarity_path" />
        </svg>
        <div class="user_similarity_img cover img40" style="background-image: url('<?=getUserImg(array('user_id' => $this->session->userdata('user_id'), 'size' => 64))?>');"></div>
      </div>
      <div class="similarity_text">
        Your compatibility with <?=$username?> is <span class="text"><?=$similarity['text']?></span>.<br />
        <?php
        if ($similarity['artists']) {
          echo 'You both listen to ' . join(' and ', array_filter(array_merge(array(join(', ', array_slice($similarity['artists'], 0, -1))), array_slice($similarity['artists'], -1)), 'strlen')) . '.';
        }
        else {
          echo 'You have no common popular artists.';
        }
        ?>
      </div>
    </div>
    <?php
  }
  ?>
  <div class="page_links">
    <?=anchor('album?u=' . $username, 'Albums')?>
    <?=anchor('artist?u=' . $username, 'Artists')?>
    <?=anchor('format?u=' . $username, 'Formats')?>
    <?=anchor('recent?u=' . $username, 'Library')?>
    <?=anchor('like?u=' . $username, 'Likes')?>
    <?=anchor('shout?u=' . $username, 'Shouts')?>
    <?=anchor('tag?u=' . $username, 'Tags')?>
  </div>
  <div class="left_container">
    <div class="container">
      <div class="user_info">
        <?php
        if (!empty($homepage) && $homepage != 'http://') {
          ?>
          <div><span class="label"></strong> <span class="value"><?=anchor(htmlentities($homepage), htmlentities($homepage), array('title' => 'Homepage'))?></span></div>
          <?php
        }
        ?>
        <p><?=nl2br(htmlentities($about))?></p>
      </div>
    </div>
    <div class="clear"></div>
    <div class="container">
      <h2>History <span class="line"></span></h2>
      <div class="float_right settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%w')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%d')">Day</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%m')">Month</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('%Y')">Year</a> | <a href="javascript:;" onclick="view.getListeningHistory('%Y%m')" class="unactive">Montly</a>
      </div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="historyLoader"/>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="music_bar"></div>
    </div>
    <div class="container"><hr /></div>
    <?php
    if ($logged_in === 'true' && $username === $this->session->userdata('username')) {
      ?>
      <div class="container">
        <?=form_open('', array('class' => '', 'id' => 'addListeningForm'), array('addListeningType' => 'form'))?>
          <div id="addListeningDateContainer" class="listening_date">Listening date: <input name="date" title="Change date" id="addListeningDate" class="number" value="" /></div>
          <div><input type="text" autocomplete="off" tabindex="1" id="addListeningText" placeholder="♪ ♪ ♪" name="addListeningText" /></div>
          <div><input type="submit" name="addListeningSubmit" tabindex="10" id="addListeningSubmit" value="statster" /></div>
          <div>
            <?php
            foreach(unserialize($this->session->formats) as $key => $format) {
              list($format, $format_type) = array_pad(explode(':', $format), 2, false);
              ?>
              <input type="radio" name="addListeningFormat" value="<?=(empty($format_type) ? $format : $format . ':' . $format_type)?>" id="format_<?=$key?>" class="hidden" /><label for="format_<?=$key?>"><img src="/media/img/format_img/<?=(empty($format_type) ? getFormatImg(array('format' => $format)) : getFormatTypeImg(array('format_type' => $format_type)))?>_logo.png" tabindex="<?=($key + 2)?>" class="listening_format desktop_format tooltip tooltipstered" title="<?=(empty($format_type) ? $format : $format_type)?>" alt="" /></label>
              <?php
            }
            ?>
          </div>
        </form>
      </div>
      <?php
    }
    ?>
    <div class="container">
      <h2>Recently listened <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="recentlyListenedLoader2" /> <span class="func_container"><i class="fa fa-sync-alt" id="refreshRecentAlbums"></i></span></h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="recentlyListenedLoader"/>
      <table id="recentlyListened" class="music_table" style="margin-top: -12px;"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor('recent?u=' . $username, 'More listenings', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Favorite albums
        <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="topAlbumLoader2" />
        <div class="func_container">
          <div class="value top_album_value" data-value="<?=$top_album_profile?>"><?=INTERVAL_TEXTS[$top_album_profile]?></div>
          <ul class="subnav" data-name="top_album_profile" data-callback="getTopAlbums" data-loader="topAlbumLoader2">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="topAlbumLoader"/>
      <ul id="topAlbum" class="music_wall clearfix"><!-- Content is loaded with AJAX --></ul>
      <div class="more">
        <?=anchor('album?u=' . $username, 'More albums', array('title' => 'Browse more albums'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Favorite artist
        <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="topArtistLoader2" />
        <div class="func_container">
          <div class="value top_artist_value" data-value="<?=$top_artist_profile?>"><?=INTERVAL_TEXTS[$top_artist_profile]?></div>
          <ul class="subnav" data-name="top_artist_profile" data-callback="getTopArtists" data-loader="topArtistLoader2">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="topArtistLoader"/>
      <ul id="topArtist" class="music_wall clearfix"><!-- Content is loaded with AJAX --></ul>
      <div class="more">
        <?=anchor('artist?u=' . $username, 'More artists', array('title' => 'Browse more artists'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Shoutbox <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="shoutLoader2" /><span id="shoutTotal"></span></h2>
      <table class="shout_table">
        <?php
        if ($logged_in === 'true') {
          ?>
          <tr class="post_shout">
            <td class="img user_img">
              <?=anchor(array('user', url_title($this->session->userdata('username'))), '<div class="cover user_img img64" style="background-image:url(' . $this->session->userdata('user_image') . ')"></div>', array('title' => 'Browse to user\'s page'))?>
            </td>
            <td class="textarea" colspan="2">
              <input type="hidden" id="contentID" value="<?=$user_id?>" />
              <input type="hidden" id="contentType" value="user" />
              <div><textarea placeholder="Post a shout…" id="shoutText"></textarea></div>
              <div><button id="shoutSubmit">post shout</button></div>
            </td>
          </tr>
          <?php
        }
        ?>
      </table>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="userShoutLoader"/>
      <table id="userShout" class="shout_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h1>Top in <?=date('F', strtotime('first day of last month'))?></h1>
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
          echo ERR_NO_RESULTS;
        }
        ?>
      </table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Shouts</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="shoutLoader" />
      <table id="shout" class="shout_table"><!-- Content is loaded with AJAX --></table>
      <table id="albumShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
      <table id="artistShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor('shout?u=' . $username, 'More', array('title' => 'Browse more shouts'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Likes</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyLikedLoader" />
      <table id="recentlyLiked" class="side_table"><!-- Content is loaded with AJAX --></table>
      <table id="recentlyFaned" class="likes hidden"><!-- Content is loaded with AJAX --></table>
      <table id="recentlyLoved" class="likes hidden"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor('like?u=' . $username, 'More', array('title' => 'Browse more likes'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Listening formats
        <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="topFormatLoader2" />
        <div class="func_container">
          <div class="value"><?=INTERVAL_TEXTS[$top_listening_format_profile]?></div>
          <ul class="subnav" data-name="top_listening_format_profile" data-callback="getTopFormats" data-loader="topFormatLoader2">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topFormatLoader"/>
      <table id="topFormat" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor('format?u=' . $username, 'More', array('title' => 'Browse more formats'))?>
      </div>
    </div>
    <div class="container">
      <h2>Genres
        <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="topGenreLoader2" />
        <div class="func_container">
          <div class="value"><?=INTERVAL_TEXTS[$top_genre_profile]?></div>
          <ul class="subnav" data-name="top_genre_profile" data-callback="getTopGenres" data-loader="topGenreLoader2">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topGenreLoader" />
      <table id="topGenre" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor('genre?u=' . $username, 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Keywords
        <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="topKeywordLoader2" />
        <div class="func_container">
          <div class="value"><?=INTERVAL_TEXTS[$top_keyword_profile]?></div>
          <ul class="subnav" data-name="top_keyword_profile" data-callback="getTopKeywords" data-loader="topKeywordLoader2">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topKeywordLoader" />
      <table id="topKeyword" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor('keyword?u=' . $username, 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Nationalities
        <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="topNationalityLoader2" />
        <div class="func_container">
          <div class="value"><?=INTERVAL_TEXTS[$top_nationality_profile]?></div>
          <ul class="subnav" data-name="top_nationality_profile" data-callback="getTopNationalities" data-loader="topNationalityLoader2">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topNationalityLoader" />
      <table id="topNationality" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor('nationality?u=' . $username, 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Years
        <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="topYearLoader2" />
        <div class="func_container">
          <div class="value"><?=INTERVAL_TEXTS[$top_year_profile]?></div>
          <ul class="subnav" data-name="top_year_profile" data-callback="getTopYears" data-loader="topYearLoader2">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topYearLoader" />
      <table id="topYear" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor('year?u=' . $username, 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
  </div>