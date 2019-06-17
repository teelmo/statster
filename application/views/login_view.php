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
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('listener'), 'Listeners')?>
    <?=anchor(array('shout'), 'Shouts')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div class="left_container">
    <div class="container">
      <h1>Login</h1>
        <?=form_open('', array('class' => '', 'id' => 'loginForm'), array('addListeningType' => 'form'))?>
        <div>
          <input type="text" class="" tabindex="2" id="loginUsername" placeholder="Enter username" name="registerUsername" />
        </div>
        <div>
          <input type="password" class="" tabindex="2" id="loginPassword" placeholder="Enter password" name="registerEmail" />
        </div>
        <br />
        <div>
          <p><input type="submit" name="loginSubmit" tabindex="2" id="loginSubmit" value="Login" /></p>
        </div>
      </form>
    </div>
    <div class="container"><hr /></div>
  </div>
  <div class="right_container">
    <div class="container"></div>
  </div>