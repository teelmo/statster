<div id="headingCont" class="artist_heading_cont" style="background-image: url('')">
  <div class="inner">
    <div class="float_left">
      <div class="cover no_cover img174" style="background-image:url('')"><span class="album_year"></span></div>
    </div>
    <div class="info">
      <div class="top_info tag_info">
        <h2><?=anchor(array('tag', url_title($this->uri->segment(2))), $tag_type, array('title' => $tag_type))?></h2>
        <h1><?=$tag_name?></h1>
      </div>
      <table class="tag_meta">
        <tr>
          <td class="label">Listenings</td>
          <td class="label">Listeners</td>
          <td class="label">Added in</td>
          <?php
          //if ($logged_in === TRUE) {
            /*?>
            <td class="label user_listening" rowspan="3"><div class="user_listenings_img cover img32" style="background-image: url('<?=getUserImg(array('user_id' => $this->session->userdata('user_id'), 'size' => 32))?>');"></div><span class="user_value"><span class="value"><?=anchor(array('recent', url_title($artist_name), url_title($album_name) . '?u=' . $this->session->userdata('username')), $user_count)?></span> in your library</span></td>
            <?php*/
          //}
          ?>
        </tr>
        <tr>
          <td class="value"><?/*=anchor(array('recent', url_title($artist_name), url_title($album_name)), number_format($total_count))*/?></td>
          <td class="value"><?/*=anchor(array('listener', url_title($artist_name), url_title($album_name)), number_format($listener_count))*/?></td>
          <td class="value"><?/*=anchor(array('tag', 'year', $created), $created)*/?></td>
        </tr>
      </table>
    </div>
  </div>
</div>
<div class="clear"></div>
<div id="mainCont" class="heading_container">
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
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('weekday')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('day')">Day</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('month')">Month</a> | <a href="javascript:;" onclick="view.getListeningHistory('year')" class="unactive">Year</a>
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
      <!-- <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="" /> -->
      <table id="" class="barTable"><!-- Content is loaded with AJAX --></table>
      <h2>Latest listenings</h2>
      <!-- <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="" /> -->
      <table id="" class="barTable"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>