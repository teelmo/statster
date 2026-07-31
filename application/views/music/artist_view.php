<?php $this->load->view('templates/heading_artist', array('is_current_page' => true)); ?>
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
          <ul class="subnav hidden" data-name="artist_album" data-callback="artistAlbum" data-loader="discographyLoader">
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