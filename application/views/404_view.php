<?php $this->load->view('templates/heading_main'); ?>
    <div class="container welcome_container">
      <div class="content">
        <h2>Statster&nbsp; &middot; &middot; &middot; &nbsp;page not found!</h2>
        <p>We are truly sorry this has happened to you 😔</p>
        <p>Please continue your journey from the navigation above.</p>
      </div>
    </div>
    <div class="container">
      <h3>If you think there is an error, please report the following details</h3>
      <p>Error code: <code>404 not found</code><br />Request url: <code><?='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']?></code><br />Request date: <code><?=date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'])?></code></p>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h2>Statistics</h2>
    </div>
    <div class="container">
      <h3>Recently listened</h3>
      <div class="lds-facebook" id="recentlyListenedLoader"><div></div><div></div><div></div></div>
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>