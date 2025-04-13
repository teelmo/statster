<div class="heading_container">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><a href="/" class="statster"><span class="stats">stats</span><span class="ter">ter</span></a><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
  <?php
  if (isset($total_count)) {
    ?>
    <div class="meta_container">
      <div class="meta">
        <div class="label">Albums</div>
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
    <?php
  }
  ?>
</div>
<div class="main_container">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('format'), 'Formats')?>
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('listener'), 'Listeners')?>
    <?=anchor(array('shout'), 'Shouts')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div class="left_container">
    <div class="container">
      <h2><?=$title?>
        <?php
        if (isset($top_album_album)) {
          ?>
          <span class="lds-ring hidden" id="topAlbum10Loader2"><div></div><div></div><div></div><div></div></span>
          <div class="func_container">
            <div class="value"><?=INTERVAL_TEXTS[$top_album_album]?></div>
            <ul class="subnav" data-name="top_album_album" data-callback="getTopAlbum10" data-loader="topAlbum10Loader2">
              <li data-value="7">Last 7 days</li>
              <li data-value="30">Last 30 days</li>
              <li data-value="90">Last 90 days</li>
              <li data-value="180">Last 180 days</li>
              <li data-value="365">Last 365 days</li>
              <li data-value="overall">All time</li>
            </ul>
          </div>
          <?php
        }
        ?>
      </h2>
      <div class="lds-facebook" id="topAlbum10Loader"><div></div><div></div><div></div></div>
      <ul id="topAlbum10" class="music_list music_list_150 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container">
      <div class="lds-facebook" id="topAlbumLoader"><div></div><div></div><div></div></div>
      <table id="topAlbum" class="column_table full"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container">
      <div class="more"><?=anchor(array('album', 'mosaic'), 'Mosaic')?></div>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h2><?=$side_title?></h2>
    </div>
    <div id="sideTable"><!-- Content is loaded with AJAX --></div>
  </div>