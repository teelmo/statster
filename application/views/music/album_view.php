<div class="heading_container"> 
  <div class="heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>')">
    <div class="info">
      <div class="float_left cover album_img img174" style="background-image:url('<?=getAlbumImg(array('album_id' => $album_id, 'size' => 174))?>')">
        <?php
        if ($spotify_id !== FALSE) {
          ?>
          <a href="spotify:album:<?=$spotify_id?>" class="spotify_link"><div class="spotify_container album_spotify_container"></div></a>
          <?php
        }
        ?>
        <?=($most_listened_releaseyear !== false) ? '<span class="rank">#<span class="number">' . $most_listened_releaseyear . '</span></span>' : ''?>
        <span class="album_year number"><?=anchor(array('year', $year), $year)?></span>
      </div>
      <div class="top_info album_info">
        <h2><?=implode('<span class="artist_separator">, </span>', array_map(function($artist) { return anchor(array('music', url_title($artist['artist_name'])), $artist['artist_name']);}, $artists))?></h2>
        <h1>
          <?php
          echo $album_name;
          if (!empty($this->session->userdata['user_id']) && in_array($this->session->userdata['user_id'], ADMIN_USERS)) {
            echo anchor(array('admin', 'album', $album_id . '?artist=' . $artist_name), '<span class="fa fa-pen-square"></span>');
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
      <div class="value number"><span class="<?=($per_year === NULL) ? '' : 'data_per_year'?>" data-per-year="<?=$per_year?>"><?=anchor(array('recent', url_title($artist_name), url_title($album_name)), number_format($total_count))?></span><?=($most_listened_alltime !== false) ? ', ' . anchor(array('album'), '<span class="rank">#<span class="number">' . $most_listened_alltime . '</span></span>') : ''?></div>
    </div>
    <div class="meta">
      <div class="label">Listeners</div>
      <div class="value number"><?=anchor(array('listener', url_title($artist_name), url_title($album_name)), number_format($listener_count))?></div>
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
          <div class="user_listening_image" title="<?=($total_count > 0) ? intval(($user_count / $total_count) * 100) : 0?>%">
            <svg viewBox="0 0 36 36">
              <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke-width="4" stroke-dasharray="100, 100" />
              <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke-width="4" stroke-dasharray="<?=($total_count > 0) ? ($user_count / $total_count) * 100 : 0?>, 100" class="similarity_path" />
            </svg>
            <div class="user_listenings_img cover img32" style="background-image: url('<?=getUserImg(array('user_id' => $this->session->userdata('user_id'), 'size' => 32))?>');"></div>
          </div>
          <span class="user_value"><span class="value number"><span class="<?=($per_year_user === NULL) ? '' : 'data_per_year_user'?>" data-per-year="<?=$per_year_user?>"><?=anchor(array('recent', url_title($artist_name), url_title($album_name) . '?u=' . $this->session->userdata('username')), number_format($user_count))?></span></span> in your library<?=($most_listened_alltime_user !== false) ? ', ' . anchor(array('album' . '?u=' . $this->session->userdata('username')), '<span class="rank">#<span class="number">' . $most_listened_alltime_user . '</span></span>') : ''?></span>
          <span id="love" class="like_toggle"><div class="lds-facebook" id="loveLoader"><div></div><div></div><div></div></div><span class="like_msg"></span></span>
          <span id="quick_add_listening" class="quick_add_listening">
            <span class="fa fa-plus-square"></span>
            <ul class="subnav">
              <?php
              foreach(unserialize($this->session->formats) as $key => $format) {
                list($format, $format_type) = array_pad(explode(':', $format), 2, false);
                ?>
                  <li data-value="<?=(empty($format_type) ? $format : $format . ':' . $format_type)?>"><img src="/media/img/format_img/format_icons/<?=(empty($format_type) ? getFormatImg(array('format' => $format)) : getFormatTypeImg(array('format_type' => $format_type)))?>.png" tabindex="<?=($key + 2)?>" class="middle icon listening_format_type" title="<?=(empty($format_type) ? $format : $format_type)?>" alt="" /> <?=$format_type?></li>
                <?php
              }
              ?>
            </ul>
          </span>
        </div>
      </div>
      <?php
    }
    ?>
  </div>
</div>
<div class="main_container">
  <div class="page_links">
    <?=anchor(array('format', url_title($artist_name), url_title($album_name)), 'Formats')?>
    <?=anchor(array('like', url_title($artist_name), url_title($album_name)), 'Likes')?>
    <?=anchor(array('listener', url_title($artist_name), url_title($album_name)), 'Listeners')?>
    <?=anchor(array('recent', url_title($artist_name), url_title($album_name)), 'Listenings')?>
    <?=anchor(array('shout', url_title($artist_name), url_title($album_name)), 'Shouts')?>
    <?=anchor(array('tag', url_title($artist_name), url_title($album_name)), 'Tags')?>
    <div class="float_right">
      <div class="lds-facebook" id="albumLoveLoader"><div></div><div></div><div></div></div>
      <ul id="albumLove" class="like_list no_bullets"><!-- Content is loaded with AJAX --></ul>
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
    else {
      ?>
      <br />
      <?php
    }
    ?>
    <div class="container">
      <h2>History <span class="line"></span></h2>
      <div class="float_right settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%w')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%d')">Day</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%m')">Month</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('%Y')">Year</a> | <a href="javascript:;" onclick="view.getListeningHistory('%Y%m')" class="unactive">Montly</a>
      </div>
      <div class="lds-facebook" id="historyLoader"><div></div><div></div><div></div></div>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="music_bar"></div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Discography
        <span class="lds-ring hidden" id="discographyLoader"><div></div><div></div><div></div><div></div></span>
        <div class="func_container">
          <div class="value artist_album_value" data-value="<?=$artist_album?>"><?=ORDER_TEXTS[$artist_album]?></div>
          <ul class="subnav" data-name="artist_album" data-callback="artistAlbum" data-loader="discographyLoader">
            <li data-value="`count` DESC, `albums`.`year` DESC">Count</li>
            <li data-value="`albums`.`album_name` ASC">Name</li>
            <li data-value="`albums`.`year` DESC, `count` DESC">Year</li>
          </ul>
        </div>
      </h2>
      <div class="lds-facebook" id="artistAlbumLoader"><div></div><div></div><div></div></div>
      <ul id="artistAlbum" class="music_list music_list_150 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Similar</h2>
      <div class="lds-facebook" id="similarArtistLoader"><div></div><div></div><div></div></div>
      <ul id="similarArtist" class="music_list music_list_150 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Associated</h2>
      <div class="lds-facebook" id="associatedArtistLoader"><div></div><div></div><div></div></div>
      <ul id="associatedArtist" class="music_list music_list_150 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Shoutbox <span class="lds-ring hidden" id="shoutLoader2"><div></div><div></div><div></div><div></div></span><span id="shoutTotal"></span></h2>
      <table class="shout_table">
        <?php
        if ($logged_in === 'true') {
          ?>
          <tr class="post_shout">
            <td class="img user_img">
              <?=anchor(array('user', url_title($this->session->userdata('username'))), '<div class="cover user_img img64" style="background-image:url(' . $this->session->userdata('user_image') . ')"></div>', array('title' => 'Browse to user\'s page'))?>
            </td>
            <td class="textarea" colspan="2">
              <input type="hidden" id="contentID" value="<?=$album_id?>" />
              <input type="hidden" id="contentType" value="album" />
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
      <h1>Statistics</h1>
      <h2>Top listeners</h2>
      <div class="lds-facebook" id="topListenerLoader"><div></div><div></div><div></div></div>
      <table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('listener', url_title($artist_name), url_title($album_name)), 'More', array('title' => 'Browse more listeners'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Latest listenings</h2>
      <div class="lds-facebook" id="recentlyListenedLoader"><div></div><div></div><div></div></div>
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('recent', url_title($artist_name), url_title($album_name)), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Listening formats</h2>
      <div class="lds-facebook" id="topListeningFormatTypesLoader"><div></div><div></div><div></div></div>
      <table id="topListeningFormatTypes" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('format', url_title($artist_name), url_title($album_name)), 'More', array('title' => 'Browse more formats'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Artist's comments</h2>
      <div class="lds-facebook" id="artistShoutLoader"><div></div><div></div><div></div></div>
      <table id="artistShout" class="shout_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('shout', url_title($artist_name), url_title($album_name)), 'More', array('title' => 'Browse more shouts'))?>
      </div>
    </div>
  </div>