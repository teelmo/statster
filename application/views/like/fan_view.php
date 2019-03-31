<div class="heading_container">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><span class="stats">stats</span><span class="ter">ter</span><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
</div>
<div class="main_container">
  <div class="page_links">
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('fan'), 'Fans')?>
    <?=anchor(array('love'), 'Loves')?>
  </div>
  <div class="left_container">
    <div class="container">
      <h1>Recently faned</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyFanedLoader" />
      <table id="recentlyFaned" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Most faned</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topFanedLoader"/>
      <table id="topFaned" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>