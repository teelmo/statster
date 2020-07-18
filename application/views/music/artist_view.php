<div class="heading_container">
  <div class="heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist_id, 'artist_name' => $artist_name, 'size' => 300))?>')">
    <div class="info">
      <div class="float_left cover artist_img img174" style="background-image:url('<?=getArtistImg(array('artist_id' => $artist_id, 'artist_name' => $artist_name, 'size' => 174))?>')">
        <?php
        if ($spotify_uri !== FALSE) {
          ?>
          <a href="<?=$spotify_uri?>" class="spotify_link"><div class="spotify_container artist_spotify_container"></div></a>
          <?php
        }
        ?>
      </div>
      <div class="top_info artist_info">
        <h1>
          <?php
          echo $artist_name;
          if (!empty($this->session->userdata['user_id']) && in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
            echo anchor(array('admin', 'artist', $artist_id), '<span class="fa fa-pen-square"></span>');
          }
          ?>
        </h1>
        <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="tagsLoader"/>
        <ul id="tags"><!-- Content is loaded with AJAX --></ul>
        <div id="tagAdd" class="hidden">
          <select data-placeholder="Add metadata" class="chosen-select" multiple>
            <optgroup label="Genres" id="genre"></optgroup>
            <optgroup label="Keywords" id="keyword"></optgroup>
            <optgroup label="Nationality" id="nationality"></optgroup>
          </select>
          <button type="submit" id="submitTags" class="submit" title="Add"></button>
        </div>
      </div>
    </div>
  </div>
  <div class="artist_meta">
    <div class="meta">
      <div class="label">Listenings</div>
      <div class="value number"><?=anchor(array('recent', url_title($artist_name)), number_format($total_count))?><?=($most_listened_alltime !== false) ? ', ' . anchor(array('artist'), '<span class="rank">#<span class="number">' . $most_listened_alltime . '</span></span>') : ''?></div>
    </div>
    <div class="meta">
      <div class="label">Listeners</div>
      <div class="value number"><?=anchor(array('listener', url_title($artist_name)), number_format($listener_count))?></div>
    </div>
    <div class="meta">
      <div class="label">Added</div>
      <div class="value number"><?=anchor(array('year', $created), $created)?></div>
    </div>
    <?php
    if ($logged_in === 'true') {
      ?>
      <div class="meta">
        <div class="label user_listening">
          <div class="user_listening_image" title="<?=intval(($user_count / $total_count) * 100)?>%">
            <svg viewBox="0 0 36 36">
              <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="rgba(182, 192, 191, 0.5)"; stroke-width="4"; stroke-dasharray="100, 100" />
              <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#666"; stroke-width="4"; stroke-dasharray="<?=intval(($user_count / $total_count) * 100)?>, 100" class="similarity_path" />
            </svg>
            <div class="user_listenings_img cover img32" style="background-image: url('<?=getUserImg(array('user_id' => $this->session->userdata('user_id'), 'size' => 32))?>');"></div>
          </div>
          <span class="user_value"><span class="value number"><?=anchor(array('recent', url_title($artist_name) . '?u=' . $this->session->userdata('username')), number_format($user_count))?></span> in your library<?=($most_listened_alltime_user !== false) ? ', ' . anchor(array('artist' . '?u=' . $this->session->userdata('username')), '<span class="rank">#<span class="number">' . $most_listened_alltime_user . '</span></span>') : ''?></span>
          <span id="fan" class="like_toggle"><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="fanLoader"/><span class="like_msg"></span></span>
        </div>
      </div>
      <?php
    }
    ?>
  </div>
</div>
<div class="main_container">
  <div class="page_links">
    <?=anchor(array('format', url_title($artist_name)), 'Formats')?>
    <?=anchor(array('like', url_title($artist_name)), 'Likes')?>
    <?=anchor(array('listener', url_title($artist_name)), 'Listeners')?>
    <?=anchor(array('recent', url_title($artist_name)), 'Listenings')?>
    <?=anchor(array('shout', url_title($artist_name)), 'Shouts')?>
    <?=anchor(array('tag', url_title($artist_name)), 'Tags')?>
    <div class="float_right">
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="artistFanLoader"/>
      <ul id="artistFan" class="like_list no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div class="left_container">
    <?php
    if (!empty($bio_summary)) {
      ?>
      <div class="container">
        <h2>Biography</h2>
        <p class="summary"><?=nl2br(strip_tags($bio_summary))?></p>
        <div class="more">
          <?=anchor('', 'See more', array('title' => 'See full biography', 'id' => 'biographyMore'))?>
        </div>
        <p class="content hidden"><?=nl2br(strip_tags($bio_content))?></p>
        <div class="more">
          <?=anchor('', 'See less', array('title' => 'Suppress biograhpy', 'id' => 'biographyLess', 'class' => 'hidden'))?>
        </div>
      </div>
      <?php
    }
    ?>
    <div class="container">
      <h2>History <span class="line"></span></h2>
      <div class="float_right settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%w')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%d')">Day</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%m')">Month</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('%Y')">Year</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%Y%m')">Montly</a>
      </div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="historyLoader"/>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="music_bar"></div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Discography
        <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="discographyLoader" />
        <div class="func_container">
          <div class="value artist_album_value" data-value="<?=$artist_album?>"><?=ORDER_TEXTS[$artist_album]?></div>
          <ul class="subnav" data-name="artist_album" data-callback="artistAlbum" data-loader="discographyLoader">
            <li data-value="`count` DESC">Count</li>
            <li data-value="`albums`.`album_name` ASC">Name</li>
            <li data-value="`albums`.`year` DESC">Year</li>
          </ul>
        </div>
      </h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="artistAlbumLoader"/>
      <ul id="artistAlbum" class="music_list music_list_150 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Similar</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="similarArtistLoader"/>
      <ul id="similarArtist" class="music_list music_list_150 no_bullets"><!-- Content is loaded with AJAX --></ul>
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
              <input type="hidden" id="contentID" value="<?=$artist_id?>" />
              <input type="hidden" id="contentType" value="artist" />
              <div><textarea placeholder="Post a shout…" id="shoutText"></textarea></div>
              <div><button id="shoutSubmit">post shout</button></div>
            </td>
          </tr>
          <?php
        }
        ?>
      </table>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="shoutLoader"/>
      <table id="shout" class="shout_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Top listeners</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListenerLoader"/>
      <table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('listener', url_title($artist_name)), 'More', array('title' => 'Browse more listeners'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Latest listenings</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader"/>
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('recent', url_title($artist_name)), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Listening formats</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListeningFormatTypesLoader"/>
      <table id="topListeningFormatTypes" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('format', url_title($artist_name)), 'More', array('title' => 'Browse more formats'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Albums' comments</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="albumShoutLoader" />
      <table id="albumShout" class="shout_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('format', url_title($artist_name)), 'More', array('title' => 'Browse more shouts'))?>
      </div>
    </div>
  </div>