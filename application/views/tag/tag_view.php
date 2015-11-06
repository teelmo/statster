<div id="headingCont" class="artist_heading_cont" style="background-image: url('')">
  <div class="inner">
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
          <td class="value"><?/*=anchor(array('recent', url_title($artist_name), url_title($album_name)), $total_count)*/?></td>
          <td class="value"><?/*=anchor(array('listener', url_title($artist_name), url_title($album_name)), $listener_count)*/?></td>
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
  </div>
  <div id="leftCont">
    <div class="container">
      <h1>History</h1>
      <div class="float_right settings">
        <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('weekday')">Weekday</a> | <a href="javascript:;" class="unactive" onclick="view.getListeningHistory('day')">Day</a> | <a href="javascript:;" class="" onclick="view.getListeningHistory('month')">Month</a> | <a href="javascript:;" onclick="view.getListeningHistory('year')" class="unactive">Year</a>
      </div>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="historyLoader"/>
      <table id="history"><!-- Content is loaded with AJAX --></table>
      <div class="bar_chart"></div>
    </div>
  </div>

  <div id="rightCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Genres</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topGenreLoader" />
      <table id="topGenre" class="barTable"><!-- Content is loaded with AJAX --></table>
      <h2>Keywords</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topKeywordLoader" />
      <table id="topKeyword" class="barTable"><!-- Content is loaded with AJAX --></table>
      <?php
      /*
      <h2>Nationalities</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topNatinalityLoader" />
      <table id="topNationality" class="barTable"><!-- Content is loaded with AJAX --></table>
      <h2>Release Years</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topReleaseYearLoader" />
      <table id="topReleaseYear" class="barTable"><!-- Content is loaded with AJAX --></table>
      */
      ?>
    </div>
  </div>