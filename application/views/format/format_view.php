<div id="mainCont">
  <div class="page_links">
    <?=anchor(array('album'), 'Albums')?>
    <?=anchor(array('artist'), 'Artists')?>
    <?=anchor(array('format'), 'Formats')?>
    <?=anchor(array('listener'), 'Listeners')?>
    <?=anchor(array('like'), 'Likes')?>
    <?=anchor(array('shout'), 'Shouts')?>
    <?=anchor(array('tag'), 'Tags')?>
  </div>
  <div id="leftCont">
    <div class="container">
      <h1>Listening formats</h1>
      <img src="/media/img/ajax-loader-bar.gif" alt="" class="loader" id="topListeningFormatTypesLoader"/>
      <table id="topListeningFormatTypes" class="column_table full"><!-- Content is loaded with AJAX --></table>
    </div>
  </div>
  <div id="rightCont">
   
  </div>