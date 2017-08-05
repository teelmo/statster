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
      <h1>Listenings <img src="/media/img/ajax-loader-circle.gif" alt="" class="hidden" id="recentlyListenedLoader2" /> <span class="func_container"><i class="fa fa-refresh" id="refreshRecentAlbums"></i></span>
      </h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="recentlyListenedLoader"/>
      <table id="recentlyListened" class="music_table" style="margin-top: -12px;"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div id="rightCont">
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
