<div class="heading_container">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><a href="/" class="statster"><span class="stats">stats</span><span class="ter">ter</span></a><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
  <div class="meta_container">
    <div class="meta">
      <div class="label">Album shouts</div>
      <div class="value">
        <?php
        echo anchor(array('shout', 'album'), number_format($total_album_shouts), array('class' => 'number'));
        if (isset($total_artist_shouts_user_count)) {
          echo '<span class="user_value">, <span class="number">' . $total_album_shouts_user_count . '</span> by you</span>';
        }
        ?>
      </div>
    </div>
    <div class="meta">
      <div class="label">Artist shouts</div>
      <div class="value">
        <?php
        echo anchor(array('shout', 'artist'), number_format($total_artist_shouts), array('class' => 'number'));
        if (isset($total_artist_shouts_user_count)) {
          echo '<span class="user_value">, <span class="number">' . $total_artist_shouts_user_count . '</span> by you</span>';
        }
        ?>
      </div>
    </div>
    <div class="meta">
      <div class="label">User shouts</div>
      <div class="value">
        <?php
        echo anchor(array('shout', 'user'), number_format($total_user_shouts), array('class' => 'number'));
        if (isset($total_artist_shouts_user_count)) {
          echo '<span class="user_value">, <span class="number">' . $total_user_shouts_user_count . '</span> by you</span>';
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
      <h2>Shouts</h2>
      <div class="lds-facebook" id="shoutLoader"><div></div><div></div><div></div></div>
      <table id="shout" class="shout_table"><!-- Content is loaded with AJAX --></table>
      <table id="albumShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
      <table id="artistShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
      <table id="userShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h2>Shouters</h2>
      <div class="lds-facebook" id="shoutersLoader"><div></div><div></div><div></div></div>
      <table id="shouters" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>