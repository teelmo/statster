<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('shout'), 'Shouts')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <h1>Shouts</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="musicShoutLoader" />
      <table id="musicShout" class="shout_table"><!-- Content is loaded with AJAX --></table>
      <table id="albumShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
      <table id="artistShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
      <table id="userShout" class="shouts hidden"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div id="rightCont">
    <div class="container">
      <h1>Shouters</h1>
    </div>
    <div id="years"><!-- Content is loaded with AJAX --></div>
  </div>