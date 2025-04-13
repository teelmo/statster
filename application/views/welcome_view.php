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
  <div class="left_container">
    <div class="container welcome_container">
      <div class="content">
        <h1>Statster &middot; &middot; &middot; Hi!</h1>
        <p>Want to be reconciled with the music. Do it Statster like! and <a href="javascript:" id="toggleRegisterForm"><strong>register!</strong></a></p>
        <?=form_open('', array('class' => 'hidden', 'id' => 'registerForm'), array('addListeningType' => 'form'))?>
          <div><input type="text" autocomplete="off" tabindex="1" id="registerUsername" placeholder="Desired username" name="registerUsername" /></div>
          <div><input type="text" autocomplete="off" tabindex="1" id="registerEmail" placeholder="Enter your email" name="registerEmail" /></div>
          <div><input type="password" autocomplete="off" tabindex="1" id="registerPass1" placeholder="Enter a password" name="registerPass1" /></div>
          <div><input type="password" autocomplete="off" tabindex="1" id="registerPass2" placeholder="Re-enter your password" name="registerPass2" /></div>
          <p><input type="submit" name="registerSubmit" tabindex="2" id="registerSubmit" value="Register!" /></p>
        </form>
      </div>
    </div>
    <div class="container">
      <h3>Statster is a web service for tracking your music</h3>
      <p>The story of Statster dates back to year 2007. Back then I was studying at the university where I learned some PHP and SQL.</p>
      <p>I had been tracking my listening habits by hand for several years before and then I realised I can use these new skills to make my life easier. The idea of Statster was born.</p>
      <p>I came up with the first version farely quickly. It was crappy but it worked. Luckily I was very passionate to develop the service further.</p>
      <p>In 2009 Statster had it's high point when almost 9,000 album listenings were registered into the system.</p>
      <p>Nowadays Statster is only used by me for my own purposes and the story continues.</p>
      <p>– Teemo Tebest, founder of Statster</p>
   </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Top in <?=date('F', strtotime('first day of last month'))?></h2>
      <table class="side_table">
        <tr>
          <td class="img32 album_img">
            <?=($top_album['album_id'] !== 0) ? anchor(array('music', url_title($top_album['artist_name']), url_title($top_album['album_name'])), '<div class="cover album_img img32" style="background-image:url(' . getAlbumImg(array('album_id' => $top_album['album_id'], 'size' => 32)) . ')"></div>', array('title' => 'Browse to album\'s page')) : ''?>
          </td>
          <td class="title">
            <?=($top_album['album_id'] !== 0) ? anchor(array('music', url_title($top_album['artist_name']), url_title($top_album['album_name'])), substrwords($top_album['album_name'], 80), array('title' => $top_album['count'] . ' listenings')) . ' ' . anchor(array('year', url_title($top_album['year'])), '<span class="album_year number">' . $top_album['year'] . '</span>', array('title' => 'Browse release year')) : ''?>
            <?=($top_album['count']) ? '<div class="count"><span class="number">' . $top_album['count'] . '</span> listenings</div>' : ''?>
          </td>
        </tr>
        <tr>
          <td class="img32 artist_img">
            <?=($top_artist['artist_id'] !== 0) ? anchor(array('music', url_title($top_artist['artist_name'])), '<div class="cover artist_img img32" style="background-image:url(' . getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 32)) . ')"></div>', array('title' => 'Browse to artist\'s page')) : ''?>
          </td>
          <td class="title">
             <?=($top_artist['artist_id'] !== 0) ? anchor(array('music', url_title($top_artist['artist_name'])), substrwords($top_artist['artist_name'], 80), array('title' => $top_artist['count'] . ' listenings')) : ''?>
            <?=($top_artist['count'] !== 0) ? '<div class="count"><span class="number">' . $top_artist['count'] . ' </span> listenings</div>' : ''?>
          </td>
        </tr>
        <tr>
          <td class="img32 tag_img">
            <?=($top_genre['count'] !== 0) ? '<i class="fa fa-music"></i>' : ''?>
          </td>
          <td class="title">
            <?=($top_genre['count'] !== 0) ? anchor(array('genre', url_title($top_genre['name'])), $top_genre['name']) : ''?>
            <?=($top_genre['count'] !== 0) ? '<div class="count"><span class="number">' . $top_genre['count'] . '</span> listenings</div>' : ''?>
          </td>
        </tr>
        <tr>
          <td class="img32 tag_img">
            <?=($top_nationality['count'] !== 0) ? '<i class="fa fa-flag"></i>' : ''?>
          </td>
          <td class="title">
            <?=($top_nationality['count'] !== 0) ? anchor(array('nationality', url_title($top_nationality['name'])), $top_nationality['name']) : ''?>
            <?=($top_nationality['count'] !== 0) ? '<div class="count"><span class="number">' . $top_nationality['count'] . '</span> listenings</div>' : ''?>
          </td>
        </tr>
        <tr>
          <td class="img32 tag_img">
            <?=($top_year['count'] !== 0) ? '<i class="fa fa-hashtag"></i>' : ''?>
          </td>
          <td class="title">
            <?=($top_year['count'] !== 0) ? anchor(array('year', url_title($top_year['year'])), $top_year['year']) : ''?>
            <?=($top_year['count'] !== 0) ? '<div class="count"><span class="number">' . $top_year['count'] . '</span> listenings</div>' : ''?>
          </td>
          <?php
          if ($top_album['count'] === 0 && $top_artist['count'] === 0 && $top_genre['count'] === 0 && $top_nationality['count'] === 0 && $top_year['count'] === 0) {
            ?>
            <tr><td class="title"><?=ERR_NO_RESULTS?></td></tr>
            <?php
          }
          ?>
        </tr>
      </table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Recently listened</h2>
      <div class="lds-facebook loader" id="recentlyListenedLoader"><div></div><div></div><div></div></div>
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>All time artists</h2>
      <div class="lds-facebook loader" id="topArtistLoader"><div></div><div></div><div></div></div>
      <table id="topArtist" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>