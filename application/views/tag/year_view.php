<div class="heading_container">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><a href="/" class="statster"><span class="stats">stats</span><span class="ter">ter</span></a><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
  <div class="meta_container">
    <div class="meta">
      <div class="label">Years</div>
      <div class="value number"><?=number_format($total_count)?></div>
    </div>
    <?php
    if (isset($user_count)) {
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
          <span class="user_value"><span class="value number"><?=anchor(array('user', url_title($this->session->userdata('username'))), number_format($user_count))?></span> in your library</span>
        </div>
      </div>
      <?php
    }
    ?>
  </div>
</div>
<div class="main_container">
  <div class="page_links">
    <?=anchor(array('genre'), 'Genres')?>
    <?=anchor(array('keyword'), 'Keywords')?>
    <?=anchor(array('nationality'), 'Nationalities')?>
    <?=anchor(array('year'), 'Years')?>
  </div>
  <div class="left_container">
    <div class="container">
      <h1>Top years
      <span class="lds-ring hidden" id="topYearLoader2"><div></div><div></div><div></div><div></div></span>
        <div class="func_container">
          <div class="value"><?=INTERVAL_TEXTS[$top_year_year]?></div>
          <ul class="subnav" data-name="top_year_year" data-callback="getYearsHistory" data-loader="topYearLoader2">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h1>
    </div>
    <div class="container">
      <div class="float_right settings">
        <a href="javascript:;" class="unactive" onclick="view.getAgeHistory()">Age</a> | <a href="javascript:;" class="" onclick="view.getYearsHistory('<?=$top_year_year?>', '%Y');">Year</a>
      </div>
      <div class="lds-facebook loader" id="historyLoader"><div></div><div></div><div></div></div>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="music_bar"></div>
    </div>
    <div class="container">
      <div class="lds-facebook loader" id="topYearLoader"><div></div><div></div><div></div></div>
      <table id="topYear" class="column_table full"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Top albums yearly</h2>
      <div class="lds-facebook loader" id="topAlbumYearlyLoader"><div></div><div></div><div></div></div>
      <ul id="topAlbumYearly" class="music_list music_list_150 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h1>Yearly</h1>
    </div>
    <div id="years"><!-- Content is loaded with AJAX --></div>
  </div>