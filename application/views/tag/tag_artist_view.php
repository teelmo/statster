<div class="heading_container">
  <div class="heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist['artist_id'], 'size' => 300))?>')">
    <div class="info">
      <div class="top_info tag_info">
        <h2><?=anchor(array(url_title($this->uri->segment(1))), ucfirst($tag_type), array('title' => $tag_type))?></h2>
        <h1><?=anchor(array(url_title($this->uri->segment(1)), url_title($tag_name)), ucfirst($tag_name), array('title' => $tag_name))?></h1>
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
             <span class="user_value"><span class="value number"><?=number_format($user_count)?></span> in your library</span>
            <div class="user_listenings_img cover img32" style="background-image: url('<?=getUserImg(array('user_id' => $this->session->userdata('user_id'), 'size' => 32))?>');"></div>
          </div>
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
    <div class="container">
      <h1><?=$title?>
        <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="topArtist10Loader2" />
        <div class="func_container">
          <div class="value"><?=INTERVAL_TEXTS[$top_artist_tag_artist]?></div>
          <ul class="subnav" data-name="top_artist_<?=$type?>_artist" data-callback="topArtist10" data-loader="topArtist10Loader2">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtist10Loader"/>
      <ul id="topArtist10" class="music_list music_list_150 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container">
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistLoader"/>
      <table id="topArtist" class="column_table full"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h1><?=$side_title?></h1>
    </div>
    <div id="sideTable"><!-- Content is loaded with AJAX --></div>
  </div>