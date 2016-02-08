<div id="headingCont" class="artist_heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
  <h1>
    <div><span class="stats">stats</span><span class="ter">ter</span><span class="separator"></span><span class="meta">reconcile with music</span></div>
    <div class="top_music">
      <div><?=anchor(array('music', url_title($top_artist['artist_name'])), $top_artist['artist_name'])?></div>
    </div>
  </h1>
</div>
<div class="clear"></div>
<div id="mainCont" class="heading_container">
  <div id="leftCont">
    <div class="container">
      <h1>People</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="userMosaikLoader"/>
      <ul id="userMosaik" class=""><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h2>Newest user</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id="newestUserLoader"/>
    </div>
    <div class="container">
      <h2>Activity</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader noIndent" id=""/>
    </div>
    <br />
  </div>
