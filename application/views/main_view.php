<div id="headingCont">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><span class="stats">stats</span><span class="ter">ter</span><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
</div>
<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('format'), 'Formats')?>
    <?=anchor(array('listener'), 'Listeners')?>
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('shout'), 'Shouts')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <br />
      <?=form_open('', array('class' => '', 'id' => 'addListeningForm'), array('addListeningType' => 'form'))?>
        <div id="addListeningDateContainer" class="listening_date">Listening date: <input name="date" title="Change date" id="addListeningDate" class="number" value="<?=CUR_DATE?>" /></div>
        <div><input type="text" autocomplete="off" tabindex="1" id="addListeningText" placeholder="♪ ♪ ♪" name="addListeningText" /></div>
        <div><input type="submit" name="addListeningSubmit" tabindex="10" id="addListeningSubmit" value="statster" /></div>
        <div>
          <?php
          foreach(unserialize($this->session->formats) as $key => $format) {
            list($format, $format_type) = array_pad(explode(':', $format), 2, false);
            ?>
            <input type="radio" name="addListeningFormat" value="<?=(empty($format_type) ? $format : $format . ':' . $format_type)?>" id="format_<?=$key?>" class="hidden" /><label for="format_<?=$key?>"><img src="/media/img/format_img/<?=(empty($format_type) ? getFormatImg(array('format' => $format)) : getFormatTypeImg(array('format_type' => $format_type)))?>_logo.png" tabindex="<?=($key + 2)?>" class="listening_format desktop_format tooltip tooltipstered" title="<?=(empty($format_type) ? $format : $format_type)?>" alt="" /></label>
            <?php
          }
          ?>
        </div>
      </form>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Recently listened <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="recentlyListenedLoader2" /> <span class="func_container"><i class="fa fa-refresh" id="refreshRecentAlbums"></i></span></h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader"/>
      <table id="recentlyListened" class="music_table" style="margin-top: -12px;"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor('recent', 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Popular albums
        <div class="func_container">
          <div class="value">Last 30 days</div>
          <ul class="subnav">
            <li>Last 7 days</li>
            <li>Last 30 days</li>
            <li>Last 90 days</li>
            <li>Last 180 days</li>
            <li>Last 365 days</li>
            <li>All time</li>
          </ul>
        </div>
      </h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topAlbumLoader" />
      <ul id="topAlbum" class="music_wall"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Statistics</h1>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Popular artists
       <div class="func_container">
          <div class="value">Last 30 days</div>
          <ul class="subnav">
            <li>Last 7 days</li>
            <li>Last 30 days</li>
            <li>Last 90 days</li>
            <li>Last 180 days</li>
            <li>Last 365 days</li>
            <li>All time</li>
          </ul>
        </div>
      </h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistLoader" />
      <table id="topArtist" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h1>What's hot</h1>
      <h2>Top in <?=date('F', strtotime('first day of last month'))?></h2>
      <table class="side_table">
        <tr>
          <td class="img32 album_img">
            <?=anchor(array('music', url_title($top_album['artist_name']), url_title($top_album['album_name'])), '<div class="cover album_img img32" style="background-image:url(' . getAlbumImg(array('album_id' => $top_album['album_id'], 'size' => 32)) . ')"></div>', array('title' => 'Browse to album\'s page'))?>
          </td>
          <td class="title">
            <?=anchor(array('music', url_title($top_album['artist_name']), url_title($top_album['album_name'])), substrwords($top_album['album_name'], 80), array('title' => $top_album['count'] . ' listenings'))?> <?=anchor(array('year', url_title($top_album['year'])), '<span class="album_year number">' . $top_album['year'] . '</span>', array('title' => 'Browse release year'))?>
            <div class="count"><span class="number"><?=$top_album['count']?></span> listenings</div>
          </td>
        </tr>
        <tr>
          <td class="img32 artist_img">
            <?=anchor(array('music', url_title($top_artist['artist_name'])), '<div class="cover artist_img img32" style="background-image:url(' . getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 32)) . ')"></div>', array('title' => 'Browse to artist\'s page'))?>
          </td>
          <td class="title">
            <?=anchor(array('music', url_title($top_artist['artist_name'])), substrwords($top_artist['artist_name'], 80), array('title' => $top_artist['count'] . ' listenings'))?>
            <div class="count"><span class="number"><?=$top_artist['count']?></span> listenings</div>
          </td>
        </tr>
        <tr>
          <td class="img32 tag_img"><i class="fa fa-music"></i></td>
          <td class="title">
            <?=anchor(array('genre', url_title($top_genre['name'])), $top_genre['name'])?>
            <div class="count"><span class="number"><?=$top_genre['count']?></span> listenings</div>
          </td>
        </tr>
        <tr>
          <td class="img32 tag_img"><i class="fa fa-flag"></i></td>
          <td class="title">
            <?=anchor(array('nationality', url_title($top_nationality['name'])), $top_nationality['name'])?>
            <div class="count"><span class="number"><?=$top_nationality['count']?></span> listenings</div>
          </td>
        </tr>
        <tr>
          <td class="img32 tag_img"><i class="fa fa-hashtag"></i></td>
          <td class="title">
            <?=anchor(array('year', url_title($top_year['year'])), $top_year['year'])?>
            <div class="count"><span class="number"><?=$top_year['count']?></span> listenings</div>
          </td>
        </tr>
      </table>
      <h2>Hot albums <span class="func_container"><i class="fa fa-refresh" id="refreshHotAlbums"></i></span></h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recommentedTopAlbumLoader" />
      <table id="recommentedTopAlbum" class="side_table"><!-- Content is loaded with AJAX --></table>
      <h2>New releases <span class="func_container"><i class="fa fa-refresh" id="refreshNewAlbums"></i></span></h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recommentedNewAlbumLoader" />
      <table id="recommentedNewAlbum" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>