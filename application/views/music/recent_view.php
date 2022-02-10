<div class="heading_container">
  <div class="heading_cont main_heading_cont" style="background-image: url('<?=getArtistImg(array('artist_id' => $top_artist['artist_id'], 'size' => 300))?>');">
    <div class="info">
      <h1><span class="stats">stats</span><span class="ter">ter</span><span class="separator"></span><span class="meta">reconcile with music</span><div class="top_music"><?=anchor(array('music', date('Y', strtotime('last month')), date('m', strtotime('last month'))), 'Top in ' . date('F', strtotime('last month')))?></div></h1>
    </div>
  </div>
  <?php
  if (isset($total_count)) {
    ?>
    <div class="tag_meta">
      <div class="meta">
        <div class="label">Listenings</div>
        <div class="value number" data-value="<?=$total_count?>"><?=number_format($total_count)?></div>
      </div>
      <?php
      if (isset($user_count)) {
        ?>
        <div class="meta">
          <div class="label user_listening">
            <div class="user_listening_image" title="<?=intval(($user_count / $total_count) * 100)?>%">
              <svg viewBox="0 0 36 36">
                <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="rgba(182, 192, 191, 0.5)"; stroke-width="4"; stroke-dasharray="100, 100" />
                <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#666"; stroke-width="4"; stroke-dasharray="<?=intval(($user_count / $total_count) * 100)?>, 100" class="similarity_path" />
              </svg>
              <div class="user_listenings_img cover img32" style="background-image: url('<?=getUserImg(array('user_id' => $this->session->userdata('user_id'), 'size' => 32))?>');"></div>
            </div>
            <span class="user_value"><span class="value number" data-value="<?=$user_count?>"><?=anchor(array('user', url_title($this->session->userdata('username'))), number_format($user_count))?></span> in your library</span>
          </div>
        </div>
        <?php
      }
      ?>
    </div>
    <?php
  }
  ?>
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
      <?php
      if ($logged_in === 'true') {
        ?>
        <div class="container">
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
        <?php
      }
      ?>
      <h1>Listenings <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="recentlyListenedLoader2" /> <span class="func_container"><i class="fa fa-sync-alt" id="refreshRecentAlbums"></i></span>
      </h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader"/>
      <table id="recentlyListened" class="music_table" style="margin-top: -12px;"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h1>Statistics</h1>
      <h2>Top listeners</h2>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListenerLoader"/>
      <table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?php
        if (!empty($album_name)) {
          echo anchor(array('listener', url_title($artist_name), url_title($album_name)), 'See more', array('title' => 'Browse more listenings'));
        }
        elseif (!empty($artist_name)) {
          echo anchor(array('listener', url_title($artist_name)), 'See more', array('title' => 'Browse more listenings'));
        }
        ?>
      </div>
    </div>
  </div>
