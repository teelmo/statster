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
    <div class="container welcome_container">
      <div class="content">
        <h1>Statster&nbsp; &middot; &middot; &middot; &nbsp;page not found!</h1>
        <p>We are truly sorry this has happened to you 😔</p>
        <p>Please continue your journey from the navigation above.</p>
      </div>
    </div>
    <div class="container">
      <h3>If you think there is an error, please report the following details</h3>
      <p>Error code: <code>404 not found</code><br />Request url: <code><?='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']?></code><br />Request date: <code><?=date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])?></code></p>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h1>Statistics</h1>
    </div>
    <div class="container">
      <h2>Recently listened</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader" />
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>