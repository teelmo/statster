<div class="heading_container">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><span class="stats">stats</span><span class="ter">ter</span><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
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
       <div class="search_container">
        <form action="/search" method="get" accept-charset="utf-8" class="search_form">
          <div><input type="text" class="middle search_text" autocomplete="off" tabindex="20" placeholder="Search music…" name="q" style="color: #000; width: auto; padding-left: 0;"/></div>
        </form>
      </div>
    </div>
    <div class="container">
      <?php
      if ($q !== '') {
        ?>
        <h1>Search: <?=$q?></h1>
        <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="searchResultLoader"/>
        <ul id="searchResult" class="search_list no_bullets"><!-- Content is loaded with AJAX --></ul>
        <?php
      }
      ?>
    </div>
  </div>
  <div class="right_container">
    <div class="container"></div>
  </div>