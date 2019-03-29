  <div id="headingCont">
  <div class="heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist['artist_id'], 'size' => 300))?>')">
    <div class="info">
      <div class="top_info tag_info">
        <h2><?=anchor(array(url_title($this->uri->segment(1))), ucfirst($tag_type), array('title' => $tag_type))?></h2>
        <h1><?=$tag_name?></h1>
      </div>
    </div>
  </div>
  <div class="tag_meta">
    <div class="meta">
      <div class="label">Listenings</div>
      <div class="value number"><?=number_format($total_count)?></div>
    </div>
    <div class="meta">
      <div class="label">Listeners</div>
      <div class="value number"><?=number_format($listener_count)?></div>
    </div>
    <?php
    if ($logged_in === 'true') {
      ?>
      <div class="meta">
        <div class="label user_listening">
          <div class="user_listening_image" title="<?=intval(($user_count / $total_count) * 100)?>%">
            <svg viewBox="0 0 36 36">
              <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="rgba(182, 192, 191, 0.5)"; stroke-width="4"; stroke-dasharray="100, 100" />
              <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#666"; stroke-width="4"; stroke-dasharray="<?=(($user_count / $total_count) * 100)?>, 100" class="similarity_path" />
            </svg>
            <div class="user_listenings_img cover img32" style="background-image: url('<?=getUserImg(array('user_id' => $this->session->userdata('user_id'), 'size' => 32))?>');"></div>
          </div>
          <span class="user_value"><span class="value number"><?=number_format($user_count)?></span> in your library</span>
        </div>
      </div>
      <?php
    }
    ?>
  </div>
</div>
<div class="clear"></div>
<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('genre'), 'Genres')?>
    <?=anchor(array('keyword'), 'Keywords')?>
    <?=anchor(array('nationality'), 'Nationalities')?>
    <?=anchor(array('year'), 'Years')?>
  </div>
  <div id="leftCont">
    <?php
    if (!empty($bio_summary)) {
      ?>
      <div class="container">
        <h2>Info</h2>
        <p class="summary"><?=nl2br($bio_summary)?></p>
        <div class="more moreDown">
          <?=anchor('', 'See more', array('title' => 'See full biography', 'id' => 'biographyMore'))?>
        </div>
        <p class="content hidden"><?=nl2br($bio_content)?></p>
        <div class="more moreUp ">
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
      <h2>Top albums
        <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="topAlbumLoader2" />
        <div class="func_container">
          <div class="value"><?=INTERVAL_TEXTS[$top_album_tag]?></div>
          <ul class="subnav" data-name="top_album_tag_<?=$tag_type?>" data-callback="getTopAlbums" data-loader="topAlbumLoader2">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumLoader" />
      <ul id="topAlbum" class="music_wall clearfix"><!-- Content is loaded with AJAX --></ul>
      <div class="more">
        <?=anchor(array(url_title($this->uri->segment(1)), url_title($tag_name), 'album'), 'More', array('title' => 'Browse more albums'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Top artists
        <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="topArtistLoader2" />
        <div class="func_container">
          <div class="value"><?=INTERVAL_TEXTS[$top_artist_tag]?></div>
          <ul class="subnav" data-name="top_artist_tag_<?=$tag_type?>" data-callback="getTopArtists" data-loader="topArtistLoader2">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistLoader" />
      <ul id="topArtist" class="music_wall clearfix"><!-- Content is loaded with AJAX --></ul>
      <div class="more">
        <?=anchor(array(url_title($this->uri->segment(1)), url_title($tag_name), 'artist'), 'More', array('title' => 'Browse more artists'))?>
      </div>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Top listeners</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListenerLoader" />
      <table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Latest listenings</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader" />
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>