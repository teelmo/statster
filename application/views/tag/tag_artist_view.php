<div id="headingCont">
  <div class="heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $artist['artist_id'], 'size' => 300))?>')">
    <div class="info">
      <div class="top_info tag_info">
        <h1><?=anchor(array(url_title($this->uri->segment(1)), url_title($tag_name)), ucfirst($tag_name), array('title' => $tag_name))?></h1>
      </div>
    </div>
  </div>
  <div class="tag_meta">
    <div class="meta">
      <div class="label">Listenings</div>
      <div class="value number"><?=number_format($total_count)?></div>
    </div>
    <div class="meta">
      <div class="label">Listeners</div>
      <div class="value number"><?=number_format($listener_count)?></div>
    </div>
    <?php
    if ($logged_in === 'true') {
      ?>
      <div class="meta">
        <div class="label user_listening" rowspan="3"><div class="user_listenings_img cover img32" style="background-image: url('<?=getUserImg(array('user_id' => $this->session->userdata('user_id'), 'size' => 32))?>');"></div><span class="user_value"><span class="value number"><?=number_format($user_count)?></span> in your library</span></div>
      </div>
      <?php
    }
    ?>
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
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtist10Loader"/>
      <ul id="topArtist10" class="music_list music_list_150 no_bullets"><!-- Content is loaded with AJAX --></ul>
    </div>
    <div class="container">
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistLoader"/>
      <table id="topArtist" class="column_table full"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1><?=$side_title?></h1>
    </div>
    <div id="sideTable"><!-- Content is loaded with AJAX --></div>
  </div>