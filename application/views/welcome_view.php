<div id="headingCont" class="artist_heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
  <h1>
    <div><span class="stats">stats</span><span class="ter">ter</span><span class="separator"></span><span class="meta">reconcile with music</span></div>
    <div class="top_music">
      <div><?=anchor(array('music', url_title($top_artist['artist_name'])), $top_artist['artist_name'])?></div>
    </div>
  </h1>
</div>
<div class="clear"></div>
<div class="clear"></div>
<div id="mainCont" class="heading_container">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <h1>Statster&nbsp; &middot; &middot; &middot; &nbsp;greetings!</h1>
      <p>
        Want to be reconciled with the music. Do it Statster like!<!-- and <a href="javascript:" onclick="$('#registerForm').toggle(); return false;">register now!</a--> 
      </p>
      <?=form_open('', array('class' => 'hidden', 'id' => 'registerForm'), array('addListeningType' => 'form'))?>
        <div>
          <input type="text" autocomplete="off" tabindex="1" id="registerUsername" placeholder="Desired username" name="registerUsername" />
        </div>
        <div>
          <input type="text" autocomplete="off" tabindex="1" id="registerEmail" placeholder="Enter your email" name="registerEmail" />
        </div>
        <div>
          <input type="password" autocomplete="off" tabindex="1" id="registerEmail" placeholder="Enter a password" name="registerEmail" />
        </div>
        <div>
          <input type="password" autocomplete="off" tabindex="1" id="registerEmail" placeholder="Re-enter your password" name="registerEmail" />
        </div>
        <div>
          <p>
            <input type="submit" name="registerSubmit" tabindex="2" id="registerSubmit" value="Register!" />
          </p>
        </div>
      </form>
    </div>
    <style>
      .embed-container { 
        height: 0;
        max-width: 100%;
        overflow: hidden;
        padding-bottom: 56.25%;
        position: relative;
      }
      .embed-container iframe, 
      .embed-container object, 
      .embed-container embed { 
        height: 100%;
        left: 0;
        position: absolute;
        top: 0;
        width: 100%;
      }
    </style>
    <div class="container">
      <div class="container"><hr /></div>
      <div class="embed-container"><iframe src="http://www.youtube.com/embed/NmfzWpp0hMc" frameborder="0" allowfullscreen></iframe></div>
    </div>
  </div>

  <div id="rightCont">
    <div class="container">
      <h1>Recently listened</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader" />
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h1>All time top</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topArtistLoader" />
      <table id="topArtist" class="column_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>