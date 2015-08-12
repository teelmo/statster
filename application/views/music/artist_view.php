<div id="leftCont">
  <div class="container">
    <span id="fan"><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="fanLoader"/></span>
    <h1><?=$artist_name?></h1>
  </div>
  <div class="container">
    <div class="tags">
      <?php
      foreach ($tags as $tag) {
        ?>
        <span class="tag <?=$tag['type']?>"><?=anchor(array('tag', $tag['type'], url_title($tag['name'])), $tag['name'])?></span>
        <?php
      }
      if ($logged_in === TRUE) {
        ?>
        <span class="tag moretags" id="moretags"><a href="javascript:;">+</a></span>
        <div class="tag" id="tagAdd">
          <select id="tagAddSelect" data-placeholder="Add metadata" class="chzn-select" multiple>
            <optgroup label="Genres" id="genre">
            </optgroup>
            <optgroup label="Keywords" id="keyword">
            </optgroup>
            <optgroup label="Nationality" id="nationality">
            </optgroup>
          </select>
        </div>
        <?php
      }
      ?>
    </div>
  </div>
  <div class="container">
    <div class="float_left">
      <img src="<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>" alt="" class="artistImg img300" />
    </div>
    <div class="artist_info">
      <div class="listening_count">
        <div class="nowrap"><span class="value"><?=$total_count?></span> <span class="label"> times listened</span></div>
        <?php
        if (!empty($user_count)) {
          ?>
          <div class="nowrap"><span class="value"><?=$user_count?></span> <span class="label">in your library</span></div>
          <?php
        }
        ?>
      </div>
      <h3 class="artist_fan">Artist's fans</h3>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="artistFanLoader"/>
      <ul id="artistFan" class="like_list no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div class="clear"></div>
  <div class="container">
    <h2>Biography</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="artistBioLoader"/>
    <div id="artistBio"><!-- Content is loaded with AJAX --></div>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h2>Albums</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="artistAlbumLoader"/>
    <ul id="artistAlbum" class="chart_list chart_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h2>Similar</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="similarArtistLoader"/>
    <ul id="similarArtist" class="chart_list chart_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
  </div>
  <!--
  <div class="container"><hr /></div>
  <div class="container">
    <h2>Shoutbox</h2>
  </div>
  -->
</div>
<div id="rightCont">
  <div class="container">
    <h1>Statistics</h1>
    <h2>Top listeners</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListenerLoader"/>
    <table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table>
    <div class="more">
      <?=anchor(array('listener', url_title($artist_name)), 'More listeners', array('title' => 'Browse more listenings'))?>
    </div>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h2>Latest listenings</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader"/>
    <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
    <div class="more">
      <?=anchor(array('recent', url_title($artist_name)), 'More listenings', array('title' => 'Browse more listenings'))?>
    </div>
  </div>
  <div class="container"><hr /></div>
  <div class="container">
    <h2>On tour</h2>
    <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="artistEventLoader"/>
    <table id="artistEvent" class="side_table"><!-- Content is loaded with AJAX --></table>
    <div class="more">
      <?=anchor('http://www.last.fm/music/' . urlencode($artist_name) . '/+events', 'More events', array('title' => 'Browse more events'))?>
    </div>
  </div>
</div>