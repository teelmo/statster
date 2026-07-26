<?php $this->load->view('templates/heading_formats'); ?>
    <div class="container">
      <h2>Listening formats
        <?php
        if (isset($top_formats)) {
          ?>
          <span class="lds-ring hidden" id="topListeningFormatTypesLoader2"><div></div><div></div><div></div><div></div></span>
          <div class="func_container">
            <div class="value"><?=INTERVAL_TEXTS[$top_formats]?></div>
            <ul class="subnav hidden" data-name="top_formats" data-callback="getFormats" data-loader="topListeningFormatTypesLoader2">
              <li data-value="7">Last 7 days</li>
              <li data-value="30">Last 30 days</li>
              <li data-value="90">Last 90 days</li>
              <li data-value="180">Last 180 days</li>
              <li data-value="365">Last 365 days</li>
              <li data-value="overall">All time</li>
            </ul>
          </div>
          <?php
        }
        ?>
      </h2>
      <div class="lds-facebook" id="topListeningFormatTypesLoader"><div></div><div></div><div></div></div>
      <table id="topListeningFormatTypes" class="column_table full"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div class="right_container">
    <div class="container">
      <h2>Statistics</h2>
      <h3>Latest listenings</h3>
      <div class="lds-facebook" id="recentlyListenedLoader"><div></div><div></div><div></div></div>
      <table id="recentlyListened" class="side_table"><!-- Content is loaded with AJAX --></table>
      <div class="more">
        <?=anchor(array('recent'), 'More', array('title' => 'Browse more listenings'))?>
      </div>
    </div>
  </div>