<div id="headingCont">
  <div class="inner">
    <div class="float_left">
      <div class="cover user_img img174" style="background-image:url('<?=getUserImg(array('user_id' => $user_id, 'size' => 174))?>')"></div>
    </div>
    <div class="info">
      <div class="top_info user_info">
        <h1><?=($real_name) ? $real_name : $username ?></h1>
        <h4><span class="username"><?=$username?></span> • <?=($joined_year) ? 'active since ' . $joined_year : 'active since long time ago'?></h4>
        <div class="tags">
          <?php
          foreach ($tags as $tag) {
            ?>
            <span class="tag <?=$tag['type']?>"><?=anchor(array('tag', $tag['type'], url_title($tag['name'])), $tag['name'])?></span>
            <?php
          }
          ?>
        </div>
      </div>
      <table class="listening_count">
        <tr>
          <td class="label">Listenings</td>
          <td class="label">Artists</td>
          <td class="label">Albums</td>
        </tr>
        <tr>
          <td class="value"><?=$listening_count?></td>
          <td class="value"><?=$artist_count?></td>
          <td class="value"><?=$album_count?></td>
        </tr>
      </table>
    </div>
  </div>
</div>
<div class="clear"></div>
<div id="mainCont" class="heading_container">
  <div class="page_links">
    <?=anchor('recent?u=' . $username, 'Listenings')?>
    <?=anchor('artist?u=' . $username, 'Artists')?>
    <?=anchor('album?u=' . $username, 'Albums')?>
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
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="userListeningsLoader"/>
      <table id="userListenings" class="" data-graph-container-before="1" data-graph-type="column" data-graph-height="300" data-graph-color-1="rgba(182, 192, 191, 0.5)" data-graph-legend-disabled="1"><!-- Content is loaded with AJAX --></table>
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