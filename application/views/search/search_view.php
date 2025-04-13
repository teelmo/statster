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
    <?=anchor(array('listener'), 'Listeners')?>
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('shout'), 'Shouts')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div class="left_container">
    <div class="container">
       <div class="search_container full">
        <form action="/search" method="get" accept-charset="utf-8" class="search_form">
          <div class="autocomplete_container"><input type="text" class="middle search_text" autocomplete="off" tabindex="20" placeholder="Search music…" name="q" value="<?=urldecode(urldecode($q))?>" /><span class="lds-ring hidden"><div></div><div></div><div></div><div></div></span></div>
          <button disabled="disabled" type="submit" class="search_submit" title="Search!"></button>
        </form>
      </div>
    </div>
    <div class="container">
      <?php
      if ($q !== '') {
        ?>
        <h2>Search: <?=urldecode(urldecode($q))?></h2>
        <div class="lds-facebook" id="searchResultLoader"><div></div><div></div><div></div></div>
        <ul id="searchResult" class="search_list no_bullets"><!-- Content is loaded with AJAX --></ul>
        <?php
      }
      ?>
    </div>
  </div>
  <div class="right_container">
    <div class="container"></div>
  </div>