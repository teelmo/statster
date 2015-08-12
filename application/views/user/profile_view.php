<div id="leftCont">
  <div class="container">
    <h1><div class="desc"><?=anchor(array('user'), 'Users', array('title' => 'Browse to artist\'s page'))?></div><?=$username?></h1>
  </div>
  <div class="container">
    <div class="float_left"><img src="<?=getUserImg(array('user_id' => $id, 'size' => 300))?>" alt="" class="userImg img300" /></div>
    <div class="user_info">
      <?php
      if (!empty($real_name)) {
        ?>
        <div><span class="value"><?=$real_name?></span></div>
        <?php
      }
      if (date_create($birthday) && !empty($birthday)) {
        ?>
        <div><span class="value"><?=date_diff(date_create($birthday), date_create('today'))->y;?> years</span></div>
        <?php
      }
      if (!empty($homepage) && $homepage != 'http://') {
        ?>
        <div><span class="value"><?=anchor($homepage, $homepage, array('title' => 'Homepage'))?></span></div>
        <?php
      }
      ?>
      <div><?=$about?></div>
    </div>
  </div>
  <div class="clear"></div>
  <div class="container">
    <h2>Recent listenings</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="recentlyListenedLoader"/>
    <table id="recentlyListened" class="chartTable"><!-- Content is loaded with AJAX --></table>
    <div class="more">
      <?=anchor('recent?u=' . $username, 'More listenings', array('title' => 'Browse more listenings'))?>
    </div>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h2>Favorite albums</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="topAlbumLoader"/>
    <ul id="topAlbum" class="chartList chartList124 noBullets"><!-- Content is loaded with AJAX --></ul>
    <div class="more">
      <?=anchor('album?u=' . $username, 'More albums', array('title' => 'Browse more albums'))?>
    </div>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h2>Favorite artist</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="topArtistLoader"/>
    <ul id="topArtist" class="chartList chartList124 noBullets"><!-- Content is loaded with AJAX --></ul>
    <div class="more">
      <?=anchor('artist?u=' . $username, 'More artists', array('title' => 'Browse more artists'))?>
    </div>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h2>Favorite genres</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id=""/>
  </div>
</div>

<div id="rightCont">
  <div class="container">
    <h1>Statistics</h1>
    <div>Number of listenings</div>
    <div>Joined</div>
    <h2>Activity</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id=""/>
  </div>
</div>