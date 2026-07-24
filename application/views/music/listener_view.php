<?php $this->load->view('templates/heading_formats'); ?>
    <div class="container">
      <h2>Top listeners</h2>
      <div class="lds-facebook" id="topListenerLoader"><div></div><div></div><div></div></div>
      <table id="topListener" class="column_table full"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h2>Statistics</h2>
      <h3>Latest listenings</h3>
      <div class="lds-facebook" id="recentlyListenedLoader"><div></div><div></div><div></div></div>
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?php
        if (!empty($album_name)) {
          echo anchor(array('recent', url_title($artist_name), url_title($album_name)), 'More', array('title' => 'Browse more listenings'));
        }
        elseif (!empty($artist_name)) {
          echo anchor(array('recent', url_title($artist_name)), 'More', array('title' => 'Browse more listenings'));
        }
        ?>
      </div>
    </div>
  </div>