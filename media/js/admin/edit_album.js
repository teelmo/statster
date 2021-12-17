$.extend(view, {
  // Init Edit album events.
  initEditAlbumEvents: function () {
    $('#artistAdd select').chosen({
      search_contains: true
    });
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
  view.initEditAlbumEvents();
});