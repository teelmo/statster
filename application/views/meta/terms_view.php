<div class="heading_container">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><a href="/" class="statster"><span class="stats">stats</span><span class="ter">ter</span></a><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
</div>
<div class="main_container">
  <div class="full_container">
    <div class="container">
      <h1>Terms of use</h1>
    </div>
    <div class="container">
      <p>Don't violate any rules, laws or common good behaviour.</p>
    </div>
    <div class="container">
      <p class="updated">
        Updated: 13 April 2025
      </p>
    </div>
  </div>
