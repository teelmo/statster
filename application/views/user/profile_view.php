<div id="headingCont" class="artist_heading_cont profile_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>')" title="#1 artist: <?=$artist_name?>">
  <div class="inner">
    <div class="float_left">
      <div class="cover user_img img174" style="background-image:url('<?=getUserImg(array('user_id' => $user_id, 'size' => 174))?>')"></div>
    </div>
    <div class="info">
      <div class="top_info user_info">
        <h1><?=$username?></h1>
        <h4><span class="username"><?=($real_name) ? $real_name : $username ?></span><span class="meta"> • <?=($joined_year) ? 'active since ' . $joined_year : 'active since long time ago'?></span></h4>
        <div class="tags">
          <?php
          foreach ($tags as $tag) {
            ?>
            <span class="tag <?=$tag['type']?>"><?=anchor(array($tag['type'], url_title($tag['name']) . '?u=teelmo'), $tag['name'])?></span>
            <?php
          }
          ?>
        </div>
      </div>
      <table class="user_meta">
        <tr>
          <td class="label">Listenings</td>
          <td class="label">Albums</td>
          <td class="label">Artists</td>
        </tr>
        <tr>
          <td class="value"><?=anchor(array('recent?u=', $username), number_format($listening_count))?></td>
          <td class="value"><?=anchor(array('album?u=' . $username), number_format($album_count))?></td>
          <td class="value"><?=anchor(array('artist?u=' . $username), number_format($artist_count))?></td>
        </tr>
      </table>
    </div>
  </div>
</div>
<div class="clear"></div>
<div id="mainCont" class="heading_container">
  <div class="page_links">
    <?=anchor('recent?u=' . $username, 'Listenings')?>
    <?=anchor('album?u=' . $username, 'Albums')?>
    <?=anchor('artist?u=' . $username, 'Artists')?>
    <?=anchor('tag?u=' . $username, 'Tags')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <div class="user_info">
        <?php
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
      <h2>History</h2>
      <div class="float_right settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('weekday')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('day')">Day</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('month')">Month</a> | <a href="javascript:;" onclick="view.getListeningHistory('year')" class="">Year</a>
      </div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="historyLoader"/>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="bar_chart"></div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Recent listenings</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="recentlyListenedLoader"/>
      <table id="recentlyListened" class="chart_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor('recent?u=' . $username, 'More listenings', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Favorite albums</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="topAlbumLoader"/>
      <ul id="topAlbum" class="chart_list chart_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
      <div class="more">
        <?=anchor('album?u=' . $username, 'More albums', array('title' => 'Browse more albums'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Favorite artist</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="topArtistLoader"/>
      <ul id="topArtist" class="chart_list chart_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
      <div class="more">
        <?=anchor('artist?u=' . $username, 'More artists', array('title' => 'Browse more artists'))?>
      </div>
    </div>
  </div>

  <div id="rightCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Likes</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyLikedLoader" />
      <table id="recentlyLiked" class="side_table"><!-- Content is loaded with AJAX --></table>
      <table id="recentlyFaned" class="recentlyLiked hidden"><!-- Content is loaded with AJAX --></table>
      <table id="recentlyLoved" class="recentlyLiked hidden"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>