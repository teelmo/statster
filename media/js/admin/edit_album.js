$.extend(view, {
  // Init Edit album events.
  initEditAlbumEvents: function () {
    $('.artist_name').autocomplete({
      html:true,
      minLength:3,
      response: function () {
        $(this).removeClass('working');
      },
      select: function (event, ui) {
      },
      search: function () {
        $(this).addClass('working');
      },
      source:'/autoComplete/artist'
    });
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