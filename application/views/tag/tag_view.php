<div id="headingCont" class="artist_heading_cont tag_heading_cont" style="background-image: url('')">
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
          if ($logged_in === TRUE) {
            ?>
            <td class="label user_listening" rowspan="3"><div class="user_listenings_img cover img32" style="background-image: url('<?=getUserImg(array('user_id' => $this->session->userdata('user_id'), 'size' => 32))?>');"></div><span class="user_value"><span class="value"><?=number_format($user_count)?></span> in your library</span></td>
            <?php
          }
          ?>
        </tr>
        <tr>
          <td class="value"><?=number_format($total_count)?></td>
          <td class="value"><?=number_format($listener_count)?></td>
        </tr>
      </table>
    </div>
  </div>
</div>
<div class="clear"></div>
<div id="mainCont" class="heading_container_no_image">
  <div class="page_links">
    <?=anchor(array('genre'), 'Genres')?>
    <?=anchor(array('keyword'), 'Keywords')?>
    <?=anchor(array('year'), 'Release years')?>
    <?=anchor(array('nationality'), 'Nationalities')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <h2>History</h2>
      <div class="float_right settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%w')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%d')">Day</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('%m')">Month</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%Y')">Year</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('%Y%m')">Montly</a>
      </div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="historyLoader"/>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="bar_chart"></div>
      <h2>Top albums</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumLoader" />
      <ul id="topAlbum" class="chart_list chart_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
      <h2>Top artists</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistLoader" />
      <ul id="topArtist" class="chart_list chart_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>

  <div id="rightCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Top listeners</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListenerLoader" />
      <table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table>
      <h2>Latest listenings</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader" />
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>