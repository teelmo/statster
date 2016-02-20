<div id="headingCont" class="artist_heading_cont tag_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist['artist_id'], 'size' => 300))?>')">
  <div class="inner">
    <div class="info">
      <div class="top_info tag_info">
        <h2><?=anchor(array(url_title($this->uri->segment(1)), url_title($tag_name)), $tag_name, array('title' => $tag_name))?></h2>
        <h1><?=ucfirst($type) . 's'?></h1>
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
<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('genre'), 'Genres')?>
    <?=anchor(array('keyword'), 'Keywords')?>
    <?=anchor(array('nationality'), 'Nationalities')?>
    <?=anchor(array('year'), 'Years')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <h1><?=$title?></h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topMusic10Loader"/>
      <ul id="topMusic10" class="chart_list chart_list_124 no_bullets"><!-- Content is loaded with AJAX --></ul>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topMusicLoader"/>
      <table id="topMusic" class="column_table full"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>

  <div id="rightCont">
    <div class="container">
      <h1>Yearly</h1>
    </div>
    <div id="years"><!-- Content is loaded with AJAX --></div>
  </div>