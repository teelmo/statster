<div class="heading_container">
  <div class="heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist_id, 'artist_name' => $artist_name, 'size' => 300))?>')">
    <div class="info">
      <div class="float_left cover artist_img img174" style="background-image:url('<?=getArtistImg(array('artist_id' => $artist_id, 'artist_name' => $artist_name, 'size' => 174))?>')">
        <?php
        if ($spotify_id !== FALSE) {
          ?>
          <a href="spotify:artist:<?=$spotify_id?>" class="spotify_link"><div class="spotify_container artist_spotify_container"></div></a>
          <?php
        }
        ?>
      </div>
      <div class="top_info artist_info">
        <h1>
          <?php
          echo $artist_name;
          if (!empty($this->session->userdata['user_id']) && in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
            echo anchor(array('admin', 'artist', $artist_id . '?redirect=' . $_SERVER['REQUEST_URI']), '<span class="fa fa-pen-square"></span>');
          }
          ?>
        </h1>
        <div class="lds-facebook inline" id="tagsLoader"><div></div><div></div><div></div></div>
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
  <div class="meta_container">
    <div class="meta">
      <div class="label">Listenings</div>
      <div class="value number"><span class="<?=($per_year === NULL) ? '' : 'data_per_year'?>" data-per-year="<?=$per_year?>"><?=anchor(array('recent', url_title($artist_name)), number_format($total_count))?></span><?=($most_listened_alltime !== false) ? ', ' . anchor(array('artist'), '<span class="rank">#<span class="number">' . $most_listened_alltime . '</span></span>') : ''?></div>
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
    if ($logged_in === 'true' && $total_count > 0) {
      ?>
      <div class="meta">
        <div class="label user_listening">
          <div class="user_listening_image" title="<?=($total_count > 0) ? intval(($user_count / $total_count) * 100) : 0?>%">
            <svg viewBox="0 0 36 36">
              <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="rgba(182, 192, 191, 0.5)"; stroke-width="4"; stroke-dasharray="100, 100" />
              <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#666"; stroke-width="4"; stroke-dasharray="<?=($total_count > 0) ? ($user_count / $total_count) * 100 : 0?>, 100" class="similarity_path" />
            </svg>
            <div class="user_listenings_img cover img32" style="background-image: url('<?=getUserImg(array('user_id' => $this->session->userdata('user_id'), 'size' => 32))?>');"></div>
          </div>
          <span class="user_value"><span class="value number"><span class="<?=($per_year_user === NULL) ? '' : 'data_per_year_user'?>" data-per-year="<?=$per_year_user?>"><?=anchor(array('recent', url_title($artist_name) . '?u=' . $this->session->userdata('username')), number_format($user_count))?></span></span> in your library<?=($most_listened_alltime_user !== false) ? ', ' . anchor(array('artist' . '?u=' . $this->session->userdata('username')), '<span class="rank">#<span class="number">' . $most_listened_alltime_user . '</span></span>') : ''?></span>
          <span id="fan" class="like_toggle"><div class="lds-facebook" id="fanLoader"><div></div><div></div><div></div></div><span class="like_msg"></span></span>
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
    <div class="artist_fan_container">
      <div class="lds-facebook inline" id="artistFanLoader"><div></div><div></div><div></div></div>
      <ul id="artistFan" class="like_list no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div class="left_container">
    <?php
    if (!empty($bio_summary)) {
      ?>
      <div class="container">
        <h3>Biography</h3>
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
      <h3>History <span class="line"></span></h3>
      <div class="settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%w')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%d')">Day</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%m')">Month</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('%Y')">Year</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%Y%m')">Montly</a>
      </div>
      <div class="lds-facebook" id="historyLoader"><div></div><div></div><div></div></div>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="music_bar"></div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Discography
        <span class="lds-ring hidden" id="discographyLoader"><div></div><div></div><div></div><div></div></span>
        <div class="func_container">
          <div class="value artist_album_value" data-value="<?=$artist_album?>"><?=ORDER_TEXTS[$artist_album]?></div>
          <ul class="subnav" data-name="artist_album" data-callback="artistAlbum" data-loader="discographyLoader">
            <li data-value="`count` DESC, `albums`.`year` DESC">Count</li>
            <li data-value="`albums`.`album_name` ASC">Name</li>
            <li data-value="`albums`.`year` DESC, `count` DESC">Year</li>
          </ul>
        </div>
      </h3>
      <div class="lds-facebook" id="artistAlbumLoader"><div></div><div></div><div></div></div>
      <ul id="artistAlbum" class="music_list music_list_150 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Similar</h3>
      <div class="lds-facebook" id="similarArtistLoader"><div></div><div></div><div></div></div>
      <ul id="similarArtist" class="music_list music_list_150 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Associated</h3>
      <div class="lds-facebook" id="associatedArtistLoader"><div></div><div></div><div></div></div>
      <ul id="associatedArtist" class="music_list music_list_150 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Shoutbox <span class="lds-ring hidden" id="shoutLoader2"><div></div><div></div><div></div><div></div></span><span id="shoutTotal"></span></h3>
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
      <div class="lds-facebook" id="shoutLoader"><div></div><div></div><div></div></div>
      <table id="shout" class="shout_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h2>Statistics</h2>
      <h3>Top listeners</h3>
      <div class="lds-facebook" id="topListenerLoader"><div></div><div></div><div></div></div>
      <table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('listener', url_title($artist_name)), 'More', array('title' => 'Browse more listeners'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Latest listenings</h3>
      <div class="lds-facebook" id="recentlyListenedLoader"><div></div><div></div><div></div></div>
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('recent', url_title($artist_name)), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Listening formats</h3>
      <div class="lds-facebook" id="topListeningFormatTypesLoader"><div></div><div></div><div></div></div>
      <table id="topListeningFormatTypes" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('format', url_title($artist_name)), 'More', array('title' => 'Browse more formats'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Albums' comments</h3>
      <div class="lds-facebook" id="albumShoutLoader"><div></div><div></div><div></div></div>
      <table id="albumShout" class="shout_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('format', url_title($artist_name)), 'More', array('title' => 'Browse more shouts'))?>
      </div>
    </div>
  </div>