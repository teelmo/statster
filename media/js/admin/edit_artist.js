$.extend(view, {
  // Init Edit album events.
  initEditArtistEvents: function () {
    var $select = $('#associatedArtistAdd select');
    $select.chosen({ search_contains: true });
    $select.prioritizedChosenSearch();
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