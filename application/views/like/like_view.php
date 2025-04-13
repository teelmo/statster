<div class="heading_container">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><a href="/" class="statster"><span class="stats">stats</span><span class="ter">ter</span></a><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
  <div class="meta_container">
    <div class="meta">
      <div class="label">Albums loved</div>
      <div class="value">
        <?php
        echo anchor(array('love'), number_format($total_album_loves), array('class' => 'number'));
        if (isset($total_album_loves_user_count)) {
          echo '<span class="user_value">, <span class="number">' . $total_album_loves_user_count . '</span> by you</span>';
        }
        ?>
      </div>
    </div>
    <div class="meta">
      <div class="label">Artists faned</div>
      <div class="value">
        <?php
        echo anchor(array('fan'), number_format($total_artist_fans), array('class' => 'number'));
        if (isset($total_artist_fans_user_count)) {
          echo '<span class="user_value">, <span class="number">' . $total_artist_fans_user_count . '</span> by you</span>';
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
      <div class="lds-facebook loader" id="topLovedLoader"><div></div><div></div><div></div></div>
      <ul id="topLoved" class="music_wall"><!-- Content is loaded with AJAX --></ul>
      <div class="lds-facebook loader" id="topFanedLoader"><div></div><div></div><div></div></div>
      <ul id="topFaned" class="music_wall"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Loved</h2>
      <div class="lds-facebook loader" id="recentlyLovedLoader"><div></div><div></div><div></div></div>
      <table id="recentlyLoved" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('love'), 'More', array('title' => 'Browse more'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Faned</h2>
      <div class="lds-facebook loader" id="recentlyFanedLoader"><div></div><div></div><div></div></div>
      <table id="recentlyFaned" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('fan'), 'More', array('title' => 'Browse more'))?>
      </div>
    </div>
  </div>