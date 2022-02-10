<div class="heading_container">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><span class="stats">stats</span><span class="ter">ter</span><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
  <div class="tag_meta">
    <div class="meta">
      <div class="label">Albums loved</div>
      <div class="value number">
        <?php
        echo anchor(array('love'), number_format($total_album_loves));
        if (isset($total_album_loves_user)) {
          echo ', <span class="rank"><span class="number">' . anchor(array('love?u=' . $_GET['u']), number_format($total_album_loves_user)). '</span> by ' . $_GET['u'] . '</span>';
        }
        ?>
      </div>
    </div>
    <div class="meta">
     <div class="label">Artists faned</div>
     <div class="value number">
        <?php
        echo anchor(array('fan'), number_format($total_artist_fans));
        if (isset($total_artist_fans_user)) {
          echo ', <span class="rank"><span class="number">' . anchor(array('fan?u=' . $_GET['u']), number_format($total_artist_fans_user)). '</span> by ' . $_GET['u'] . '</span>';
        }
        ?>
      </div>
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
      <h1>Most liked</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topLovedLoader" />
      <ul id="topLoved" class="music_wall"><!-- Content is loaded with AJAX --></ul>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topFanedLoader" />
      <ul id="topFaned" class="music_wall"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Loved</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyLovedLoader" />
      <table id="recentlyLoved" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('love'), 'More', array('title' => 'Browse more'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Faned</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyFanedLoader" />
      <table id="recentlyFaned" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('fan'), 'More', array('title' => 'Browse more'))?>
      </div>
    </div>
  </div>