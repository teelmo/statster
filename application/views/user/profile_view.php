<div id="headingCont">
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
            <li class="tag <?=$tag['type']?>"><?=anchor(array($tag['type'], url_title($tag['name']) . '?u=teelmo'), $tag['name'])?></li>
            <?php
          }
          ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="user_meta">
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
<div id="mainCont">
  <div class="page_links">
    <?=anchor('recent?u=' . $username, 'Library')?>
    <?=anchor('album?u=' . $username, 'Albums')?>
    <?=anchor('artist?u=' . $username, 'Artists')?>
    <?=anchor('format?u=' . $username, 'Formats')?>
    <?=anchor('like?u=' . $username, 'Likes')?>
    <?=anchor('shout?u=' . $username, 'Shouts')?>
    <?=anchor('tag?u=' . $username, 'Tags')?>
  </div>
  <div id="leftCont">
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
      <h2>History</h2>
      <div class="float_right settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%w')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%d')">Day</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%m')">Month</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('%Y')">Year</a> | <a href="javascript:;" onclick="view.getListeningHistory('%Y%m')" class="unactive">Montly</a>
      </div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="historyLoader"/>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="music_bar"></div>
    </div>
    <div class="container"><hr /></div>
    <?php
    if ($logged_in === 'true') {
      ?>
      <div class="container">
        <?=form_open('', array('class' => '', 'id' => 'addListeningForm'), array('addListeningType' => 'form'))?>
          <div id="addListeningDateContainer" class="listening_date">Listening date: <input name="date" title="Change date" id="addListeningDate" class="number" value="<?=CUR_DATE?>" /></div>
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
      <h2>Recently listened <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="recentlyListenedLoader2" /> <span class="func_container"><i class="fa fa-refresh" id="refreshRecentAlbums"></i></span></h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="recentlyListenedLoader"/>
      <table id="recentlyListened" class="music_table" style="margin-top: -12px;"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor('recent?u=' . $username, 'More listenings', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Favorite albums</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="topAlbumLoader"/>
      <ul id="topAlbum" class="music_wall clearfix"><!-- Content is loaded with AJAX --></ul>
      <div class="more">
        <?=anchor('album?u=' . $username, 'More albums', array('title' => 'Browse more albums'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Favorite artist</h2>
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
  <div id="rightCont">
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
        $data_found = false;
         if ($top_artist) {
          $data_found = true;
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
        if ($top_album) {
          $data_found = true;
          ?>
          <tr>
            <td class="img64 tag_img">Genre</td>
            <td class="title">
              <?=anchor(array('genre', url_title($top_genre['name'])), $top_genre['name'])?>
              <div class="count"><span class="number"><?=$top_genre['count']?></span> listenings</div>
            </td>
          </tr>
          <?php
        }
        if ($top_album) {
          $data_found = true;
          ?>
          <tr>
            <td class="img64 tag_img">Nationality</td>
            <td class="title">
              <?=anchor(array('nationality', url_title($top_nationality['name'])), $top_nationality['name'])?>
              <div class="count"><span class="number"><?=$top_nationality['count']?></span> listenings</div>
            </td>
          </tr>
          <?php
        }
        if ($top_album) {
          $data_found = true;
          ?>
          <tr>
            <td class="img64 tag_img">Year</td>
            <td class="title">
              <?=anchor(array('year', url_title($top_year['year'])), $top_year['year'])?>
              <div class="count"><span class="number"><?=$top_year['count']?></span> listenings</div>
            </td>
          </tr>
          <?php
        }
        if ($data_found === false) {
          echo ERR_NO_DATA;
        }
        ?>
      </table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Shouts</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="musicShoutLoader" />
      <table id="musicShout" class="shout_table"><!-- Content is loaded with AJAX --></table>
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
      <h2>Listening formats</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListeningFormatTypesLoader"/>
      <table id="topListeningFormatTypes" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor('format?u=' . $username, 'More', array('title' => 'Browse more formats'))?>
      </div>
    </div>
    <div class="container">
      <h2>Genres</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topGenreLoader" />
      <table id="topGenre" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor('genre?u=' . $username, 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Keywords</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topKeywordLoader" />
      <table id="topKeyword" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor('keyword?u=' . $username, 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Nationalities</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topNationalityLoader" />
      <table id="topNationality" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor('nationality?u=' . $username, 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Years</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topYearLoader" />
      <table id="topYear" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor('year?u=' . $username, 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
  </div>