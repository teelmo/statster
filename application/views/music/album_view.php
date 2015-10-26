<div id="headingCont">
  <div class="inner">
    <div class="float_left">
      <div class="cover album_img img174" style="background-image:url('<?=getAlbumImg(array('album_id' => $album_id, 'size' => 174))?>')"></div>
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
        <h2><?=anchor(array('music', $artist_name), $artist_name)?></h2>
        <h1><?=$album_name?><img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="albumLoveLoader"/><ul id="albumLove" class="like_list no_bullets"><!-- Content is loaded with AJAX --></ul></h1>
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
              <select data-placeholder="Add metadata" class="chosen-select" multiple>
                <optgroup label="Genres" id="genre"></optgroup>
                <optgroup label="Keywords" id="keyword"></optgroup>
                <optgroup label="Nationality" id="nationality"></optgroup>
              </select>
            </div>
            <?php
          }
          ?>
        </div>
      </div>
      <table class="listening_count">
        <tr>
          <td class="label">Listenings</td>
          <td class="label">Your library</td>
        </tr>
        <tr>
          <td class="value"><?=$total_count?></td>
          <td class="value"><?=$user_count?></td>
        </tr>
      </table>
    </div>
  </div>
</div>
<div class="clear"></div>
<div id="mainCont" class="heading_container">
  <div class="page_links">
    <?=anchor(array('listener', $artist_name, $album_name), 'Listeners')?>
    <?=anchor(array('recent', $artist_name, $album_name), 'Listenings')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <h2 id="biography">Biography</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="artistBioLoader"/>
      <div id="artistBio"><!-- Content is loaded with AJAX --></div>
    </div>
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
      <h2>Artist's albums</h2>
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
        <?=anchor(array('listener', url_title($artist_name), url_title($album_name)), 'More listeners', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Latest listenings</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader"/>
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('recent', url_title($artist_name), url_title($album_name)), 'More listenings', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
  </div>