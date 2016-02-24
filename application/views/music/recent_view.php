<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <h1>
        <?php
        if (!empty($album_name)) {
          ?>
          <div class="desc"><?=anchor(array('music', url_title($artist_name), url_title($album_name)), $artist_name . ' ' . DASH . ' ' . $album_name, array('title' => 'Browse to album\'s page'))?></div>
          <?php
        }
        elseif (!empty($artist_name)) {
          ?>
          <div class="desc"><?=anchor(array('music', url_title($artist_name)), $artist_name, array('title' => 'Browse to artist\'s page'))?></div>
          <?php
        }
        ?>Recently listened
      </h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader"/>
      <table id="recentlyListened" class="chart_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>

  <div id="rightCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Top listeners</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListenerLoader"/>
      <table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?php
        if (!empty($album_name)) {
          echo anchor(array('listener', url_title($artist_name), url_title($album_name)), 'See more', array('title' => 'Browse more listenings'));
        }
        elseif (!empty($artist_name)) {
          echo anchor(array('listener', url_title($artist_name)), 'See more', array('title' => 'Browse more listenings'));
        }
        ?>
      </div>
    </div>
  </div>
