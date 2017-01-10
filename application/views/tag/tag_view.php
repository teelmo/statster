<div id="headingCont" class="artist_heading_cont tag_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist['artist_id'], 'size' => 300))?>')">
  <div class="inner">
    <div class="info">
      <div class="top_info tag_info">
        <h2><?=anchor(array(url_title($this->uri->segment(1))), ucfirst($tag_type), array('title' => $tag_type))?></h2>
        <h1><?=$tag_name?></h1>
      </div>
      <table class="tag_meta">
        <tr>
          <td class="label">Listenings</td>
          <td class="label">Listeners</td>
          <?php
          if ($logged_in === 'true') {
            ?>
            <td class="label user_listening" rowspan="3"><div class="user_listenings_img cover img32" style="background-image: url('<?=getUserImg(array('user_id' => $this->session->userdata('user_id'), 'size' => 32))?>');"></div><span class="user_value"><span class="value number"><?=number_format($user_count)?></span> in your library</span></td>
            <?php
          }
          ?>
        </tr>
        <tr>
          <td class="value number"><?=number_format($total_count)?></td>
          <td class="value number"><?=number_format($listener_count)?></td>
        </tr>
      </table>
    </div>
  </div>
</div>
<div class="clear"></div>
<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('genre'), 'Genres')?>
    <?=anchor(array('keyword'), 'Keywords')?>
    <?=anchor(array('nationality'), 'Nationalities')?>
    <?=anchor(array('year'), 'Years')?>
  </div>
  <div id="leftCont">
    <?php
    if (!empty($bio_summary)) {
      ?>
      <div class="container">
        <h2>Info</h2>
        <p class="summary"><?=nl2br($bio_summary)?></p>
        <div class="more moreDown">
          <?=anchor('', 'See more', array('title' => 'See full biography', 'id' => 'biographyMore'))?>
        </div>
        <p class="content hidden"><?=nl2br($bio_content)?></p>
        <div class="more moreUp ">
          <?=anchor('', 'See less', array('title' => 'Suppress biograhpy', 'id' => 'biographyLess', 'class' => 'hidden'))?>
        </div>
      </div>
      <?php
    }
    ?>
    <div class="container">
      <h2>History</h2>
      <div class="float_right settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%w')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%d')">Day</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%m')">Month</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('%Y')">Year</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%Y%m')">Montly</a>
      </div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="historyLoader"/>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="music_bar"></div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Top albums</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumLoader" />
      <ul id="topAlbum" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
      <div class="more">
        <?=anchor(array(url_title($this->uri->segment(1)), url_title($tag_name), 'album'), 'More', array('title' => 'Browse more albums'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Top artists</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistLoader" />
      <ul id="topArtist" class="music_list music_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
      <div class="more">
        <?=anchor(array(url_title($this->uri->segment(1)), url_title($tag_name), 'artist'), 'More', array('title' => 'Browse more artists'))?>
      </div>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Top listeners</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListenerLoader" />
      <table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Latest listenings</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader" />
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>