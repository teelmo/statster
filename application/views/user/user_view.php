<div id="headingCont">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><span class="stats">stats</span><span class="ter">ter</span><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
</div>
<div id="mainCont">
  <div id="leftCont">
    <div class="container">
      <h1>People</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="userMosaikLoader"/>
      <ul id="userMosaik" class="user_list user_list_150"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Top listeners</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListenerLoader" />
      <table id="topListener" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('listener'), 'More', array('title' => 'Browse more'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Latest shouts</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="musicShoutLoader" />
      <table id="musicShout" class="shout_table"><!-- Content is loaded with AJAX --></table>
      <table id="albumShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
      <table id="artistShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
      <table id="userShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('shout'), 'More', array('title' => 'Browse more'))?>
      </div>
    </div>
    <br />
  </div>
