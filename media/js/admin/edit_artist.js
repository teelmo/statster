$.extend(view, {
  // Init Edit album events.
  initEditArtistEvents: function () {
    $('#associatedArtistAdd select').chosen({
      search_contains: true
    });
    $('#associatedArtistAdd').show();
    $('.associated_artist_names').hide();
    $('.spotify_id').blur(function() {
      $(this).val(($(this).val().replace(/(https:\/\/open\.spotify\.com\/artist\/)?([a-zA-Z0-9]{22})(.*)/, '$2')));
    });
    $('.spotify_id').keyup(function() {
      $(this).val(($(this).val().replace(/(https:\/\/open\.spotify\.com\/artist\/)?([a-zA-Z0-9]{22})(.*)/, '$2')));
    });
  }
});

$(document).ready(function () {
  view.initEditArtistEvents();
});