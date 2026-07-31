<?php $this->load->view('templates/heading_shell_main'); ?>
  <div class="meta_container">
    <div class="meta">
      <div class="label">Albums loved</div>
      <div class="value">
        <?php
        echo anchor(array('love'), number_format($total_album_loves), array('class' => 'number'));
        if (isset($total_album_loves_user_count)) {
          echo '<span class="user_value">, <span class="number">' . $total_album_loves_user_count . '</span> by you</span>';
        }
        ?>
      </div>
    </div>
    <div class="meta">
      <div class="label">Artists faned</div>
      <div class="value">
        <?php
        echo anchor(array('fan'), number_format($total_artist_fans), array('class' => 'number'));
        if (isset($total_artist_fans_user_count)) {
          echo '<span class="user_value">, <span class="number">' . $total_artist_fans_user_count . '</span> by you</span>';
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
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('listener'), 'Listeners')?>
    <?=anchor(array('shout'), 'Shouts')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div class="left_container">
    <div class="container">
      <h2>Most liked</h2>
      <div class="lds-facebook" id="topLovedLoader"><div></div><div></div><div></div></div>
      <ul id="topLoved" class="music_wall"><!-- Content is loaded with AJAX --></ul>
      <div class="lds-facebook" id="topFanedLoader"><div></div><div></div><div></div></div>
      <ul id="topFaned" class="music_wall"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h2>Statistics</h2>
      <h3>Loved</h3>
      <div class="lds-facebook" id="recentlyLovedLoader"><div></div><div></div><div></div></div>
      <table id="recentlyLoved" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('love'), 'More', array('title' => 'Browse more'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Faned</h3>
      <div class="lds-facebook" id="recentlyFanedLoader"><div></div><div></div><div></div></div>
      <table id="recentlyFaned" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('fan'), 'More', array('title' => 'Browse more'))?>
      </div>
    </div>
  </div>