<div class="heading_container">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><a href="/" class="statster"><span class="stats">stats</span><span class="ter">ter</span></a><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
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
      <h1><div class="desc"><?=anchor(array('format', url_title($format_name)), $format_name)?></div><?=$format_type_name?>
        <?php
        if (isset($top_format_type)) {
          ?>
          <span class="lds-ring hidden" id="topAlbum10Loader2"><div></div><div></div><div></div><div></div></span>
          <div class="func_container">
            <div class="value"><?=INTERVAL_TEXTS[$top_format_type]?></div>
            <ul class="subnav" data-name="top_format_type" data-callback="getTopAlbum10" data-loader="topAlbum10Loader2">
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
      </h1>
      <div class="lds-facebook loader" id="topAlbum10Loader"><div></div><div></div><div></div></div>
      <ul id="topAlbum10" class="music_list music_list_150 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container">
      <div class="lds-facebook loader" id="topAlbumLoader"><div></div><div></div><div></div></div>
      <table id="topAlbum" class="column_table full"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Listening formats</h2>
      <div class="lds-facebook loader" id="topListeningFormatTypesLoader"><div></div><div></div><div></div></div>
      <table id="topListeningFormatTypes" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('format'), 'More', array('title' => 'Browse more formats'))?>
      </div>
    </div>
  </div>