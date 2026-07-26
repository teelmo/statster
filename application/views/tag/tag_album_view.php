<?php $this->load->view('templates/heading_tag'); ?>
    <div class="container">
      <h2><?=$title?>
        <span class="lds-ring hidden" id="topAlbum10Loader2"><div></div><div></div><div></div><div></div></span>
        <div class="func_container">
          <div class="value"><?=INTERVAL_TEXTS[$top_album_tag_album]?></div>
          <ul class="subnav hidden" data-name="top_album_<?=$type?>_album" data-callback="topAlbum10" data-loader="topAlbum10Loader2">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h2>
      <div class="lds-facebook" id="topAlbum10Loader"><div></div><div></div><div></div></div>
      <ul id="topAlbum10" class="music_list music_list_150 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container">
      <div class="lds-facebook" id="topAlbumLoader"><div></div><div></div><div></div></div>
      <table id="topAlbum" class="column_table full"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h2><?=$side_title?></h2>
    </div>
    <div id="sideTable"><!-- Content is loaded with AJAX --></div>
  </div>