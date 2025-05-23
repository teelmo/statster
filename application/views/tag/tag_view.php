<div class="heading_container">
  <div class="heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist['artist_id'], 'size' => 300))?>')">
    <div class="info">
      <div class="top_info tag_info">
        <h2><?=anchor(array(url_title($this->uri->segment(1))), ucfirst($tag_type), array('title' => $tag_type))?></h2>
        <h1><?=$tag_name?></h1>
        <ul id="tags">
          <li class="meta">Related</li>
          <?php
          foreach ($related as $key => $value) {
            switch ($tag_type) {
              case 'nationality':
                ?>
                <li class="tag <?=$tag_type?>"><?=anchor(array($tag_type, url_title($value['name'])), '<img src="/media/img/flag_img/' . strtolower($value['country_code']) . '.png"/ alt="' . $value['name'] . '" />')?>
                </li>
                <?php
                break;
              case 'genre':
                ?>
                <li class="tag <?=$tag_type?>"><?=anchor(array($tag_type, url_title($value['name'])), '<i class="fa fa-music"></i> ' . $value['name'])?>
                </li>
                <?php
                break;
              case 'keyword':
                ?>
                <li class="tag <?=$tag_type?>"><?=anchor(array($tag_type, url_title($value['name'])), '<i class="fa fa-tag"></i> ' . $value['name'])?>
                </li>
                <?php
                break;
              case 'year':
                ?>
                <li class="tag <?=$tag_type?>"><?=anchor(array($tag_type, url_title($value['year'])), '<i class="fa fa-hashtag"></i> ' . $value['year'])?>
                </li>
                <?php
                break;
              default:
                break;
            }
          }
          ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="meta_container">
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
<div class="main_container">
  <div class="page_links">
    <?=anchor(array('genre'), 'Genres')?>
    <?=anchor(array('keyword'), 'Keywords')?>
    <?=anchor(array('nationality'), 'Nationalities')?>
    <?=anchor(array('year'), 'Years')?>
  </div>
  <div class="left_container">
    <?php
    if (!empty($bio_summary)) {
      ?>
      <div class="container">
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
      <h3>Top albums
        <span class="lds-ring hidden" id="topAlbumLoader2"><div></div><div></div><div></div><div></div></span>
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
      </h3>
      <div class="lds-facebook" id="topAlbumLoader"><div></div><div></div><div></div></div>
      <ul id="topAlbum" class="music_wall clearfix"><!-- Content is loaded with AJAX --></ul>
      <div class="more">
        <?=anchor(array(url_title($this->uri->segment(1)), url_title($tag_name), 'album'), 'More', array('title' => 'Browse more albums'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Top artists
        <span class="lds-ring hidden" id="topArtistLoader2"><div></div><div></div><div></div><div></div></span>
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
      </h3>
      <div class="lds-facebook" id="topArtistLoader"><div></div><div></div><div></div></div>
      <ul id="topArtist" class="music_wall clearfix"><!-- Content is loaded with AJAX --></ul>
      <div class="more">
        <?=anchor(array(url_title($this->uri->segment(1)), url_title($tag_name), 'artist'), 'More', array('title' => 'Browse more artists'))?>
      </div>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h2>Statistics</h2>
      <h3>Top listeners</h3>
      <div class="lds-facebook" id="topListenerLoader"><div></div><div></div><div></div></div>
      <table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Latest listenings</h3>
      <div class="lds-facebook" id="recentlyListenedLoader"><div></div><div></div><div></div></div>
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>