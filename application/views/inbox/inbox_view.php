<?php $this->load->view('templates/heading_shell_main'); ?>
</div>
<div class="main_container inbox_container">
<?php $this->load->view('templates/page_links_main'); ?>
  <div class="left_container">
    <div class="container">
      <h2><?=ucfirst($folder)?></h2>
      <div class="lds-facebook" id="inboxLoader"><div></div><div></div><div></div></div>
      <table id="inbox" class="shout_table"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
<?php $this->load->view('templates/page_links_inbox', array('folder' => $folder)); ?>
