<div class="heading_container">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><a href="/" class="statster"><span class="stats">stats</span><span class="ter">ter</span></a><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
</div>
<div class="main_container">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('format'), 'Formats')?>
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('listener'), 'Listeners')?>
    <?=anchor(array('shout'), 'Shouts')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div class="full_container">
    <div class="container">
      <h2>About</h2>
    </div>
    <div class="container">
      <p>Statster is a web service for collecting your album's listening data.</p>
    </div>
    <div class="container">
      <h3>Team</h3>
      <strong>Teemo Tebest</strong>
      <ul>
        <li>Founder and head developer.</li>
        <li>teemo (dot) tebest (at) gmail (dot) com</li>
      </ul>
      <br />
    </div>
    <div class="container">
      <p class="updated">
        Updated: 13 April 2025
      </p>
    </div>
  </div>