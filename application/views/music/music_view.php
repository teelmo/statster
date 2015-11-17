<div id="headingCont" class="artist_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist_id, 'size' => 300))?>')">
  <div class="inner">
    <div class="float_left">
      <div class="cover album_img img174" style="background-image:url('<?=getAlbumImg(array('album_id' => $album_id, 'size' => 174))?>')"><span class="album_year"><?=anchor(array('tag', 'year', $year), $year, array('class' => 'album_year'))?></span></div>
    </div>
    <div class="info">
      <div class="top_info album_info">
        <?php
        if ($spotify_id) {
          ?>
          <a href="spotify:album:<?=$spotify_id?>" class="spotify_link"><div class="spotify_container album_spotify_container" style="background-image:url('<?=getAlbumImg(array('album_id' => $album_id, 'size' => 64))?>')"></div></a>
          <?php
        }
        ?>
        <br />
        <h4 class="meta">#1 in <?=date('F', strtotime('-1 month'))?></h4>
        <h3><?=anchor(array('music', $artist_name, $album_name), $album_name)?> <span class="meta">by</span> <?=anchor(array('music', $artist_name), $artist_name)?></h3>
        <h4 class="meta">listened <?=$count?> times</h4>
      </div>
      <table class="album_meta">
        <tr>
          <td class="label">Listenings</td>
          <td class="label">Listeners</td>
          <td class="label">Added in</td>
          <?php
          if ($logged_in === TRUE) {
            ?>
            <td class="label user_listening" rowspan="3"><div class="user_listenings_img cover img32" style="background-image: url('<?=getUserImg(array('user_id' => $this->session->userdata('user_id'), 'size' => 32))?>');"></div><span class="user_value"><span class="value"><?=anchor(array('recent', url_title($artist_name), url_title($album_name) . '?u=' . $this->session->userdata('username')), $user_count)?></span> in your library</span></td>
            <?php
          }
          ?>
        </tr>
        <tr>
          <td class="value"><?=anchor(array('recent', url_title($artist_name), url_title($album_name)), $total_count)?></td>
          <td class="value"><?=anchor(array('listener', url_title($artist_name), url_title($album_name)), $listener_count)?></td>
          <td class="value"><?=anchor(array('tag', 'year', $created), $created)?></td>
        </tr>
      </table>
    </div>
  </div>
</div>
<div class="clear"></div>
<div id="mainCont" class="heading_container">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>History</h2>
      <div class="float_right settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('weekday')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('day')">Day</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('month')">Month</a> | <a href="javascript:;" onclick="view.getListeningHistory('year')" class="unactive">Year</a>
      </div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="historyLoader"/>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="bar_chart"></div>
    </div>
    <div class="container"><hr /></div>
    <div id="leftContInner">
      <div class="container">
        <h2>Browse</h2>
        <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="popularGenreLoader" />
        <table id="popularGenre" class="genreTable"><!-- Content is loaded with AJAX --></table>
      </div>
    </div>
    <div id="leftContOuter">
      <div class="container">
        <h2>Popular albums</h2>
        <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="popularAlbumLoader" />
        <table id="popularAlbum" class="side_table"><!-- Content is loaded with AJAX --></table>
      </div>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Likes</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyLikedLoader" />
      <table id="recentlyLiked" class="side_table"><!-- Content is loaded with AJAX --></table>
      <table id="recentlyFaned" class="recentlyLiked hidden"><!-- Content is loaded with AJAX --></table>
      <table id="recentlyLoved" class="recentlyLiked hidden"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>