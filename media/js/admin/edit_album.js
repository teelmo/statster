$.extend(view, {
  // Init Edit album events.
  initEditAlbumEvents: function () {
    $('.artist_name').autocomplete({
      html:true,
      minLength:3,
      response: function () {
        $(this).removeClass('working');
      },
      select: function (event, ui) {
      },
      search: function () {
        $(this).addClass('working');
      },
      source:'/autoComplete/artist'
    });
  }
});

$(document).ready(function () {
  view.initEditAlbumEvents();
});