<div id="headingCont" class="artist_heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
  <h1>
    <div><span class="stats">stats</span><span class="ter">ter</span><span class="separator"></span><span class="meta">reconcile with music</span></div>
    <div class="top_music">
      <div><?=anchor(array('music', url_title($top_artist['artist_name'])), $top_artist['artist_name'], array('title' => $top_artist['count'] . ' listenings', 'class' => 'tooltip'))?></div>
    </div>
  </h1>
</div>
<div class="clear"></div>
<div id="mainCont" class="heading_container">
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
    <div class="container welcome_container">
      <h1>Statster&nbsp; &middot; &middot; &middot; &nbsp;greetings!</h1>
      <p>Want to be reconciled with the music. Do it Statster like! and <a href="javascript:" id="toggleRegisterForm"><strong>register!</strong></a></p>
      <?=form_open('', array('class' => 'hidden', 'id' => 'registerForm'), array('addListeningType' => 'form'))?>
        <div><input type="text" autocomplete="off" tabindex="1" id="registerUsername" placeholder="Desired username" name="registerUsername" /></div>
        <div><input type="text" autocomplete="off" tabindex="1" id="registerEmail" placeholder="Enter your email" name="registerEmail" /></div>
        <div><input type="password" autocomplete="off" tabindex="1" id="registerEmail" placeholder="Enter a password" name="registerEmail" /></div>
        <div><input type="password" autocomplete="off" tabindex="1" id="registerEmail" placeholder="Re-enter your password" name="registerEmail" /></div>
        <p><input type="submit" name="registerSubmit" tabindex="2" id="registerSubmit" value="Register!" /></p>
      </form>
    </div>
    <div class="container">
      <h3>What is Statster?</h3>
      <p>Bacon ipsum dolor amet hamburger drumstick shankle, ball tip kevin tail pastrami doner swine frankfurter bresaola landjaeger. Capicola tenderloin beef ribs picanha pork loin corned beef. Pastrami beef ribs sirloin jerky landjaeger kielbasa. Meatball cow bresaola corned beef turkey, ribeye alcatra venison tail tongue andouille. Beef ribs pancetta meatball biltong hamburger. T-bone porchetta landjaeger ground round chuck meatball pork belly swine cow short ribs chicken jerky beef.</p>
      <p>T-bone frankfurter shoulder meatball. Beef meatloaf brisket short loin kielbasa bacon tail. Turducken beef ribs rump chicken, meatloaf picanha corned beef. Ham meatloaf cow tenderloin shoulder. Strip steak hamburger burgdoggen ham hock spare ribs venison rump. Pork loin corned beef leberkas, turducken meatloaf flank shoulder t-bone tenderloin.</p>
      <p>Pastrami bresaola turkey leberkas landjaeger pig salami. Kielbasa turkey jowl chicken jerky kevin. Cow short ribs ham frankfurter. Drumstick beef ribs meatball, biltong tri-tip brisket ham hock cupim corned beef. Ball tip turkey burgdoggen ribeye doner kevin. Prosciutto turkey biltong tenderloin. Leberkas frankfurter kielbasa, flank salami shank kevin shankle.</p>
      <p>Turducken kevin chicken fatback, brisket ribeye pork loin filet mignon. Ball tip spare ribs ham, leberkas tri-tip ham hock rump kevin strip steak chicken meatloaf bresaola turkey landjaeger venison. Meatloaf cow strip steak biltong rump bacon cupim jerky corned beef beef ribs ribeye alcatra. Ham pork belly rump jerky. Pork chop corned beef flank, hamburger shankle prosciutto turkey fatback sausage ribeye ball tip turducken burgdoggen tenderloin.</p>
     <p>Landjaeger pancetta drumstick pork chop beef ribs turducken cow beef flank jowl spare ribs pork. Tenderloin jowl flank kevin boudin, rump kielbasa pork pancetta spare ribs meatball filet mignon tri-tip bacon. Venison cupim brisket, hamburger sausage t-bone shoulder filet mignon strip steak beef. Landjaeger tail hamburger ham bresaola pancetta.</p>
   </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Top in <?=date('F', strtotime('first day of last month'))?></h2>
      <table class="side_table">
        <tr>
          <td class="img64 album_img">
            <?=anchor(array('music', url_title($top_album['artist_name']), url_title($top_album['album_name'])), '<div class="cover album_img img64" style="background-image:url(' . getAlbumImg(array('album_id' => $top_album['album_id'], 'size' => 64)) . ')"></div>', array('title' => 'Browse to album\'s page'))?>
          </td>
          <td class="title">
            <?=anchor(array('music', url_title($top_album['artist_name']), url_title($top_album['album_name'])), $top_album['album_name'], array('title' => $top_album['count'] . ' listenings'))?> <?=anchor(array('year', url_title($top_album['year'])), '<span class="album_year number">' . $top_album['year'] . '</span>', array('title' => 'Browse release year'))?>
            <div class="count"><span class="number"><?=$top_album['count']?></span> listenings</div>
          </td>
        </tr>
        <tr>
          <td class="img64 artist_img">
            <?=anchor(array('music', url_title($top_artist['artist_name'])), '<div class="cover artist_img img64" style="background-image:url(' . getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 64)) . ')"></div>', array('title' => 'Browse to artist\'s page'))?>
          </td>
          <td class="title">
            <?=anchor(array('music', url_title($top_artist['artist_name'])), $top_artist['artist_name'], array('title' => $top_artist['count'] . ' listenings'))?>
            <div class="count"><span class="number"><?=$top_artist['count']?></span> listenings</div>
          </td>
        </tr>
        <tr>
          <td class="img64 tag_img">
            Genre
          </td>
          <td class="title">
            <?=anchor(array('genre', url_title($top_genre['name'])), $top_genre['name'])?>
            <div class="count"><span class="number"><?=$top_genre['count']?></span> listenings</div>
          </td>
        </tr>
        <tr>
          <td class="img64 tag_img">
            Nationality
          </td>
          <td class="title">
            <?=anchor(array('nationality', url_title($top_nationality['name'])), $top_nationality['name'])?>
            <div class="count"><span class="number"><?=$top_nationality['count']?></span> listenings</div>
          </td>
        </tr>
        <tr>
          <td class="img64 tag_img">
            Year
          </td>
          <td class="title">
            <?=anchor(array('year', url_title($top_year['year'])), $top_year['year'])?>
            <div class="count"><span class="number"><?=$top_year['count']?></span> listenings</div>
          </td>
        </tr>
      </table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>Recently listened</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader" />
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h2>All time artists</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistLoader" />
      <table id="topArtist" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>