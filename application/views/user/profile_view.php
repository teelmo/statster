<?php $this->load->view('templates/heading_user_profile'); ?>
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
      <h3>History <span class="line"></span></h3>
      <div class="settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%w')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%d')">Day</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%m')">Month</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('%Y')">Year</a> | <a href="javascript:;" onclick="view.getListeningHistory('%Y%m')" class="unactive">Montly</a>
      </div>
      <div class="lds-facebook" id="historyLoader"><div></div><div></div><div></div></div>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="music_bar"></div>
    </div>
    <div class="container"><hr /></div>
    <?php
    if ($logged_in === 'true' && $username === $this->session->userdata('username')) {
      ?>
      <div class="container">
        <?=form_open('', array('class' => '', 'id' => 'addListeningForm'), array('addListeningType' => 'form'))?>
          <div id="addListeningDateContainer" class="listening_date">Listening date: <div class="listening_date_input"><input name="date" title="Change date" id="addListeningDate" class="number" value="" /><div class="calendar_container"></div></div></div>
          <div class="autocomplete_container"><input type="text" autocomplete="off" tabindex="1" id="addListeningText" placeholder="♪ ♪ ♪" name="addListeningText" /><span class="lds-ring hidden"><div></div><div></div><div></div><div></div></span></div>
          <div><input type="submit" name="addListeningSubmit" tabindex="10" id="addListeningSubmit" value="statster" /></div>
          <div>
            <?php
            foreach(unserialize($this->session->formats) as $key => $format) {
              list($format, $format_type) = array_pad(explode(':', $format), 2, false);
              ?>
              <input type="radio" name="addListeningFormat" value="<?=(empty($format_type) ? $format : $format . ':' . $format_type)?>" id="format_<?=$key?>" class="hidden" /><label for="format_<?=$key?>"><img src="/media/img/format_img/<?=(empty($format_type) ? getFormatImg(array('format' => $format)) : getFormatTypeImg(array('format_type' => $format_type)))?>_logo.png" tabindex="<?=($key + 2)?>" class="listening_format desktop_format tooltip" title="<?=(empty($format_type) ? $format : $format_type)?>" alt="" /></label>
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
      <h3>Recently listened<span class="lds-ring hidden" id="recentlyListenedLoader2"><div></div><div></div><div></div><div></div></span> <span class="func_container"><i class="mask-icon mask-icon-sync-alt" id="refreshRecentAlbums" aria-label="Refresh recently listened"></i></span></h3>
      <div class="lds-facebook" id="recentlyListenedLoader"><div></div><div></div><div></div></div>
      <table id="recentlyListened" class="music_table" style="margin-top: -12px;"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor('recent?u=' . $username, 'More listenings', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Favorite albums
        <span class="lds-ring hidden" id="topAlbumLoader2"><div></div><div></div><div></div><div></div></span>
        <div class="func_container">
          <div class="value top_album_value" data-value="<?=$top_album_profile?>"><?=INTERVAL_TEXTS[$top_album_profile]?></div>
          <ul class="subnav hidden" data-name="top_album_profile" data-callback="getTopAlbums" data-loader="topAlbumLoader2">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h3>
      <div class="lds-facebook" id="topAlbumLoader"><div></div><div></div><div></div></div>
      <ul id="topAlbum" class="music_wall clearfix"><!-- Content is loaded with AJAX --></ul>
      <div class="more">
        <?=anchor('album?u=' . $username, 'More albums', array('title' => 'Browse more albums'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Favorite artists
        <span class="lds-ring hidden" id="topArtistLoader2"><div></div><div></div><div></div><div></div></span>
        <div class="func_container">
          <div class="value top_artist_value" data-value="<?=$top_artist_profile?>"><?=INTERVAL_TEXTS[$top_artist_profile]?></div>
          <ul class="subnav hidden" data-name="top_artist_profile" data-callback="getTopArtists" data-loader="topArtistLoader2">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h3>
      <div class="lds-facebook" id="topArtistLoader"><div></div><div></div><div></div></div>
      <ul id="topArtist" class="music_wall clearfix"><!-- Content is loaded with AJAX --></ul>
      <div class="more">
        <?=anchor('artist?u=' . $username, 'More artists', array('title' => 'Browse more artists'))?>
      </div>
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
      <div class="lds-facebook" id="userShoutLoader"><div></div><div></div><div></div></div>
      <table id="userShout" class="shout_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
<?php $this->load->view('templates/profile_sidebar'); ?>
</div>
