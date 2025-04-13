<div class="heading_container">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><a href="/" class="statster"><span class="stats">stats</span><span class="ter">ter</span></a><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
  <div class="meta_container">
    <div class="meta">
      <div class="label">Genres</div>
      <div class="value">
        <?php
        echo anchor(array('genre'), number_format($total_genres), array('class' => 'number'));
        if (isset($total_genres_user_count)) {
          echo '<span class="user_value">, <span class="number">' . $total_genres_user_count . '</span> by you</span>';
        }
        ?>
      </div>
    </div>
    <div class="meta">
      <div class="label">Keywords</div>
      <div class="value">
        <?php
        echo anchor(array('keyword'), number_format($total_keywords), array('class' => 'number'));
        if (isset($total_keywords_user_count)) {
          echo '<span class="user_value">, <span class="number">' . $total_keywords_user_count . '</span> by you</span>';
        }
        ?>
      </div>
    </div>
    <div class="meta">
      <div class="label">Nationalities</div>
      <div class="value">
        <?php
        echo anchor(array('nationality'), number_format($total_nationalities), array('class' => 'number'));
        if (isset($total_nationalities_user_count)) {
          echo '<span class="user_value">, <span class="number">' . $total_nationalities_user_count . '</span> by you</span>';
        }
        ?>
      </div>
    </div>
    <div class="meta">
      <div class="label">Years</div>
      <div class="value">
        <?php
        echo anchor(array('year'), number_format($total_years), array('class' => 'number'));
        if (isset($total_years_user_count)) {
          echo '<span class="user_value">, <span class="number">' . $total_years_user_count . '</span> by you</span>';
        }
        ?>
      </div>
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
    <div class="container clearfix">
      <h1 class="genre_heading"><span class="value not_available">Genres</span> <span class="lds-ring hidden" id="topGenreLoader3"><div></div><div></div><div></div><div></div></span></h1>
      <div class="lds-facebook" id="topAlbumGenreLoader"><div></div><div></div><div></div></div>
      <ul id="topAlbumGenre" class="music_wall clearfix"><!-- Content is loaded with AJAX --></ul>
      <div class="lds-facebook" id="topArtistGenreLoader"><div></div><div></div><div></div></div>
      <ul id="topArtistGenre" class="music_wall clearfix"><!-- Content is loaded with AJAX --></ul>
      <div class="more genre_link not_available">More</div>
    </div>
    <div class="container clearfix">
      <h1 class="keyword_heading"><span class="value not_available">Keywords</span> <span class="lds-ring hidden" id="topKeywordLoader3"><div></div><div></div><div></div><div></div></span></h1>
      <div class="lds-facebook" id="topAlbumKeywordLoader"><div></div><div></div><div></div></div>
      <ul id="topAlbumKeyword" class="music_wall clearfix"><!-- Content is loaded with AJAX --></ul>
      <div class="lds-facebook" id="topArtistKeywordLoader"><div></div><div></div><div></div></div>
      <ul id="topArtistKeyword" class="music_wall clearfix"><!-- Content is loaded with AJAX --></ul>
      <div class="more keyword_link not_available">More</div>
    </div>
    <div class="container clearfix">
      <h1 class="nationality_heading"><span class="value not_available">Nationalities</span> <span class="lds-ring hidden" id="topNationalityLoader3"><div></div><div></div><div></div><div></div></span></h1>
      <div class="lds-facebook" id="topAlbumNationalityLoader"><div></div><div></div><div></div></div>
      <ul id="topAlbumNationality" class="music_wall clearfix"><!-- Content is loaded with AJAX --></ul>
      <div class="lds-facebook" id="topArtistNationalityLoader"><div></div><div></div><div></div></div>
      <ul id="topArtistNationality" class="music_wall clearfix"><!-- Content is loaded with AJAX --></ul>
      <div class="more nationality_link not_available">More</div>
    </div>
    <div class="container clearfix">
      <h1 class="year_heading"><span class="value not_available">Years</span> <span class="lds-ring hidden" id="topYearLoader3"><div></div><div></div><div></div><div></div></span></h1>
      <div class="lds-facebook" id="topAlbumYearLoader"><div></div><div></div><div></div></div>
      <ul id="topAlbumYear" class="music_wall clearfix"><!-- Content is loaded with AJAX --></ul>
      <div class="more year_link not_available">More</div>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h1>Hot</h1>
      <h2>
        Genres
        <span class="lds-ring hidden" id="topGenreLoader2"><div></div><div></div><div></div><div></div></span>
        <div class="func_container">
          <div class="value top_genre_value" data-value="<?=$top_genre_tags?>"><?=INTERVAL_TEXTS[$top_genre_tags]?></div>
          <ul class="subnav" data-name="top_genre_tags" data-callback="topGenre" data-loader="topGenreLoader2, #topGenreLoader3">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h2>
      <div class="lds-facebook" id="topGenreLoader"><div></div><div></div><div></div></div>
      <table id="topGenre" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('genre'), 'More', array('title' => 'Browse more genres'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>
        Keywords
        <span class="lds-ring hidden" id="topKeywordLoader2"><div></div><div></div><div></div><div></div></span>
        <div class="func_container">
          <div class="value top_keyword_value" data-value="<?=$top_keyword_tags?>"><?=INTERVAL_TEXTS[$top_keyword_tags]?></div>
          <ul class="subnav" data-name="top_keyword_tags" data-callback="topKeyword" data-loader="topKeywordLoader2, #topKeywordLoader3">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h2>
      <div class="lds-facebook" id="topKeywordLoader"><div></div><div></div><div></div></div>
      <table id="topKeyword" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('keyword'), 'More', array('title' => 'Browse more keywords'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>
        Nationalities
        <span class="lds-ring hidden" id="topNationalityLoader2"><div></div><div></div><div></div><div></div></span>
        <div class="func_container">
          <div class="value top_nationality_value" data-value="<?=$top_nationality_tags?>"><?=INTERVAL_TEXTS[$top_nationality_tags]?></div>
          <ul class="subnav" data-name="top_nationality_tags" data-callback="topNationality" data-loader="topNationalityLoader2, #topNationalityLoader3">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h2>
      <div class="lds-facebook" id="topNationalityLoader"><div></div><div></div><div></div></div>
      <table id="topNationality" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('nationality'), 'More', array('title' => 'Browse more nationalities'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>
        Years
        <span class="lds-ring hidden" id="topYearLoader2"><div></div><div></div><div></div><div></div></span>
        <div class="func_container">
          <div class="value top_year_value" data-value="<?=$top_year_tags?>"><?=INTERVAL_TEXTS[$top_year_tags]?></div>
          <ul class="subnav" data-name="top_year_tags" data-callback="topYear" data-loader="topYearLoader2, #topYearLoader3">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h2>
      <div class="lds-facebook" id="topYearLoader"><div></div><div></div><div></div></div>
      <table id="topYear" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('year'), 'More', array('title' => 'Browse more years'))?>
      </div>
    </div>
  </div>