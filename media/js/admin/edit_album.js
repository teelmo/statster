$.extend(view, {
  // Init Edit album events.
  initEditAlbumEvents: function () {
    var $select = $('#artistAdd select');
    $select.chosen({ search_contains: true });
    $select.prioritizedChosenSearch();
    $('#artistAdd').show();
    $('.artist_names').hide();
    $('.spotify_id').blur(function() {
      $(this).val(($(this).val().replace(/(https:\/\/open\.spotify\.com\/album\/)?([a-zA-Z0-9]{22})(.*)/, '$2')));
    });
    $('.spotify_id').keyup(function() {
      $(this).val(($(this).val().replace(/(https:\/\/open\.spotify\.com\/album\/)?([a-zA-Z0-9]{22})(.*)/, '$2')));
    });
  }
});

$(document).ready(function () {
  app.setOverlayBackground('<?=getAlbumImg(array('album_id' => $album_id, 'size' => 300))?>');
  view.initEditAlbumEvents();
});