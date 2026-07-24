<?php $this->load->view('templates/heading_album'); ?>
    <div class="container">
      <h2>Shouts</h2>
      <div class="lds-facebook" id="albumShoutLoader"><div></div><div></div><div></div></div>
      <table id="albumShout" class="shout_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h2>Statistics</h2>
      <h3>Top listeners</h3>
      <div class="lds-facebook" id="topListenerLoader"><div></div><div></div><div></div></div>
      <table id="topListener" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('listener', url_title($artist_name)), 'More', array('title' => 'Browse more listeners'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Latest listenings</h3>
      <div class="lds-facebook" id="recentlyListenedLoader"><div></div><div></div><div></div></div>
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('recent', url_title($artist_name)), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
  </div>