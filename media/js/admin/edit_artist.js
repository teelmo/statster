$.extend(view, {
  // Init Edit album events.
  initEditArtistEvents: function () {
    $('.spotify_id').blur(function() {
      $(this).val(($(this).val().replace(/(https:\/\/open\.spotify\.com\/album\/)?([a-zA-Z0-9]{22})(.*)/, '$2')));
    });
  }
});

$(document).ready(function () {
  view.initEditArtistEvents();
});