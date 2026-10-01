<?php $this->load->view('templates/heading_shell_main'); ?>
</div>
<div class="main_container">
<?php $this->load->view('templates/page_links_main'); ?>
<?php $this->load->view('templates/page_links_inbox', array('folder' => $folder)); ?>
  <div class="full_container">
    <div class="container">
      <h2><?=ucfirst($folder)?></h2>
      <div class="lds-facebook" id="inboxLoader"><div></div><div></div><div></div></div>
      <table id="inbox" class="shout_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
