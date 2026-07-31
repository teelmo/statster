<?php $this->load->view('templates/heading_shell_main'); ?>
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
  <div class="full_container">
    <div class="container">
      <h2>About</h2>
    </div>
    <div class="container">
      <p>Statster is a web service for collecting your album's listening data.</p>
    </div>
    <div class="container">
      <h3>Team</h3>
      <strong>Teemo Tebest</strong>
      <ul>
        <li>Founder and head developer.</li>
        <li>teemo (dot) tebest (at) gmail (dot) com</li>
      </ul>
      <br />
    </div>
    <div class="container">
      <p class="updated">
        Updated: 13 April 2025
      </p>
    </div>
  </div>