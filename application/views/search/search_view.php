<?php $this->load->view('templates/heading_shell_main'); ?>
</div>
<div class="main_container">
<?php $this->load->view('templates/page_links_main'); ?>
  <div class="left_container">
    <div class="container">
       <div class="search_container full">
        <form action="/search" method="get" accept-charset="utf-8" class="search_form">
          <div class="autocomplete_container"><input type="text" class="middle search_text" autocomplete="off" tabindex="20" placeholder="Search music…" name="q" value="<?=urldecode(urldecode($q))?>" /><span class="lds-ring hidden"><div></div><div></div><div></div><div></div></span></div>
          <button disabled="disabled" type="submit" class="search_submit" title="Search!" aria-label="Search"></button>
        </form>
      </div>
    </div>
    <div class="container">
      <?php
      if ($q !== '') {
        ?>
        <h2>Search: <?=urldecode(urldecode($q))?></h2>
        <div class="lds-facebook" id="searchResultLoader"><div></div><div></div><div></div></div>
        <ul id="searchResult" class="search_list no_bullets"><!-- Content is loaded with AJAX --></ul>
        <?php
      }
      ?>
    </div>
  </div>
  <div class="right_container">
    <div class="container"></div>
  </div>