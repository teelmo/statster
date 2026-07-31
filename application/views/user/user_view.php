<?php $this->load->view('templates/heading_shell_main'); ?>
</div>
<div class="main_container">
  <div class="left_container">
    <div class="container">
      <h2>People</h2>
      <div class="lds-facebook" id="userMosaicLoader"><div></div><div></div><div></div></div>
      <ul id="userMosaic" class="user_list user_list_150"><!-- Content is loaded with AJAX --></ul>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h2>Statistics</h2>
      <h3>Top listeners
        <span class="lds-ring hidden" id="topListenerLoader2"><div></div><div></div><div></div><div></div></span>
        <div class="func_container">
          <div class="value"><?=INTERVAL_TEXTS[$top_listener_user]?></div>
          <ul class="subnav hidden" data-name="top_listener_user" data-callback="getTopListeners" data-loader="topListenerLoader2">
            <li data-value="7">Last 7 days</li>
            <li data-value="30">Last 30 days</li>
            <li data-value="90">Last 90 days</li>
            <li data-value="180">Last 180 days</li>
            <li data-value="365">Last 365 days</li>
            <li data-value="overall">All time</li>
          </ul>
        </div>
      </h3>
      <div class="lds-facebook" id="topListenerLoader"><div></div><div></div><div></div></div>
      <table id="topListener" class="column_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('listener'), 'More', array('title' => 'Browse more'))?>
      </div>
    </div>
    <div class="container"><hr /></div>
    <div class="container">
      <h3>Latest shouts</h3>
      <div class="lds-facebook" id="musicShoutLoader"><div></div><div></div><div></div></div>
      <table id="musicShout" class="shout_table"><!-- Content is loaded with AJAX --></table>
      <table id="albumShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
      <table id="artistShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
      <table id="userShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('shout'), 'More', array('title' => 'Browse more'))?>
      </div>
    </div>
    <br />
  </div>
