<div class="heading_container">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><span class="stats">stats</span><span class="ter">ter</span><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
  <div class="tag_meta">
    <div class="meta">
      <div class="label">Album shouts</div>
      <div class="value number">
        <?php
        echo anchor(array('shout', 'album'), number_format($total_album_shouts));
        if (isset($total_album_shouts_user)) {
          echo ', <span class="rank"><span class="number">' . anchor(array('shout', 'album?' . $_GET['u']), number_format($total_album_shouts_user)). '</span> by ' . $_GET['u'] . '</span>';
        }
        ?>
      </div>
    </div>
    <div class="meta">
      <div class="label">Artist shouts</div>
      <div class="value number">
        <?php
        echo anchor(array('shout', 'artist'), number_format($total_artist_shouts));
        if (isset($total_artist_shouts_user)) {
          echo ', <span class="rank"><span class="number">' . anchor(array('shout', 'artist?' . $_GET['u']), number_format($total_artist_shouts_user)). '</span> by ' . $_GET['u'] . '</span>';
        }
        ?>
      </div>
    </div>
    <div class="meta">
      <div class="label">User shouts</div>
      <div class="value number">
        <?php
        echo anchor(array('shout', 'user'), number_format($total_user_shouts));
        if (isset($total_user_shouts_user)) {
          echo ', <span class="rank"><span class="number">' . anchor(array('shout', 'user?' . $_GET['u']), number_format($total_user_shouts_user)). '</span> by ' . $_GET['u'] . '</span>';
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
      <h1>Shouts</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="shoutLoader" />
      <table id="shout" class="shout_table"><!-- Content is loaded with AJAX --></table>
      <table id="albumShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
      <table id="artistShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
      <table id="userShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h1>Shouters</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="shoutersLoader" />
      <table id="shouters" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>