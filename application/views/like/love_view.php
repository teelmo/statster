<div class="heading_container">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><span class="stats">stats</span><span class="ter">ter</span><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
  <?php
  if (isset($total_count)) {
    ?>
    <div class="meta_container">
      <div class="meta">
        <div class="label">Loved</div>
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
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('fan'), 'Fans')?>
    <?=anchor(array('love'), 'Loves')?>
  </div>
  <div class="left_container">
    <div class="container">
      <h1>Recently Loved</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyLovedLoader" />
      <table id="recentlyLoved" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Most Loved</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topLovedLoader"/>
      <table id="topLoved" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>